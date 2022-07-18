<?php

namespace App\Http\Controllers\Api\SyncCinemas;


use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Repositories\ContentCreatorRepository;
use App\Http\Repositories\ContentGenreRepository;
use App\Http\Repositories\ContentRepository;
use App\Http\Repositories\CreatorRepository;
use App\Http\Repositories\GenreRepository;
use App\Http\Repositories\TypeContentRepository;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class SyncCinemasController extends Controller
{
    protected $creatorRepository;
    protected $contentRepository;
    protected $genreRepository;
    protected $typeContentRepository;
    protected $contentGenreRepository;
    protected $contentCreatorRepository;

    public function __construct(CreatorRepository $creatorRepository,
                                ContentRepository $contentRepository,
                                GenreRepository $genreRepository,
                                TypeContentRepository $typeContentRepository,
                                ContentGenreRepository $contentGenreRepository,
                                ContentCreatorRepository $contentCreatorRepository
    )
    {
        $this->creatorRepository = $creatorRepository;
        $this->contentRepository = $contentRepository;
        $this->genreRepository = $genreRepository;
        $this->typeContentRepository = $typeContentRepository;
        $this->contentGenreRepository = $contentGenreRepository;
        $this->contentCreatorRepository = $contentCreatorRepository;
    }

    public function syncIviFilm()
    {

        $clientCollection = new Client();
        $responseCollection = $clientCollection->get('https://api2.ivi.ru/mobileapi/catalogue/v5/?category=14&paid_type=AVOD&from=0&to=29&withpreorderable=true&app_version=870&session=5f50069b8192933799505857_1640229232-0VSsdXnINDlnif4VJDNujuA&session_data=eyJ1aWQiOjgxOTI5MzM3OTk1MDU4NTd9.YNKr9A.s6dN769-1ipCvd3mfwm2vrPOb-8');
        $jsonFormattedResult = json_decode($responseCollection->getBody()->getContents(), true);

        //Todo a - тестовое ограничение на количество запросов из ленты бесплатного контента
        $a = 0;
        foreach ($jsonFormattedResult['result'] as $contentItem) {
            if ($a == 30) break;
            $client = new Client();
            $response = $client->get('https://api2.ivi.ru/mobileapi/videoinfo/v7/?id=' . $contentItem['id'] . '&fields=id%2Ctitle%2Cfake%2Cpreorderable%2Chru%2Ccontent_paid_types%2Csubscription_names%2Ccompilation_hru%2Ckind%2Cadditional_data%2Crestrict%2Chd_available%2Chd_available_all%2C3d_available%2C3d_available_all%2Cuhd_available%2Cuhd_available_all%2Chdr10_available%2Chdr10_available_all%2Cdv_available%2Cdv_available_all%2Cfullhd_available%2Cfullhd_available_all%2Chdr10plus_available%2Chdr10plus_available_all%2Chas_5_1%2Cshields%2Civi_pseudo_release_date%2Cartists%2Cbudget%2Ccategories%2Ccountry%2Cdescription%2Csynopsis%2Cduration%2Cduration_minutes%2Cgenres%2Cgross_russia%2Cgross_usa%2Cgross_world%2Cimdb_rating%2Civi_rating_10%2Ckp_rating%2Crating%2Cseason%2Corig_title%2Cyear%2Cyears%2Cepisode%2Csubsites_availability%2Csubtitles%2Ccompilation%2Ccompilation_title%2Cdrm_only%2Chas_awards%2Cused_to_be_paid%2Civi_release_date%2Csharing_disabled%2Civi_rating_10_count%2Chas_comments%2Chas_reviews%2Clocalizations%2Cbest_watch_before%2Cthumbs%2Cposters&app_version=870&session=ef75c6824682118185663737_1670526595-09QR1b1flUUV3rsLeQstITw&session_data=eyJ1aWQiOjQ2ODIxMTgxODU2NjM3Mzd9.YqD0BA.Mne1AYKjiS-A-TdZcKYB16jIv8k');
            $jsonFormattedResult = json_decode($response->getBody()->getContents(), true);
            $item = $jsonFormattedResult['result'];
            $request = Request::create('POST');
            $request->request->add(['title' => $item['title']]);
            $request->request->add(['description' => $item['description']]);
            $request->request->add(['rating' => $item['kp_rating']]);
            $request->request->add(['restrict' => $item['restrict']]);
            $request->request->add(['year' => $item['year']]);
            $request->request->add(['country' => Helper::getCountryIvi($item['country'])]);
            $request->request->add(['duration' => $item['localizations'][0]['duration']]);
            $request->request->add(['kinopoisk_id' => $contentItem['kp_id']]);
            $request->request->add(['poster' => $item['posters'][0]['url']]);

            //Задать тип контента
            foreach ($item['categories'] as $type) {
                $typeContent = $this->typeContentRepository->findFromTitle(Helper::getTypeContent($type));
                if (!empty($typeContent)) {
                    $request->request->add(['type_content_id' => $typeContent->id]);
                }
            }


            $contentStore = $this->contentRepository->findFromTitle($item['title']);
            if (empty($contentStore)) {
                $this->contentRepository->store($request);
            } else {
                $this->contentRepository->update($request, $contentStore->id);
            }

            //Берем сохраненную модель
            $content = $this->contentRepository->findFromTitle($item['title']);
            //Задать связи с жанрами
            foreach ($item['genres'] as $genre) {
                $genre = $this->genreRepository->findFromTitle(Helper::getGenreIvi($genre));
                if (!empty($genre)) {
                    $isExist = $this->contentGenreRepository->isDepencyExist($item['title'], $genre->id);
                    if (!$isExist) {
                        $storeContentGenre = Request::create('POST');
                        $storeContentGenre->request->add(['content_id' => $content->id]);
                        $storeContentGenre->request->add(['genre_id' => $genre->id]);
                        $this->contentGenreRepository->store($storeContentGenre);
                    }
                }
            }

            //Забираем актеров и прочих
            $client = new Client();
            $responseCreators = $client->get('https://api2.ivi.ru/mobileapi/video/persons/v5/?id=' . $contentItem['id']);
            $creatorsRest = json_decode($responseCreators->getBody()->getContents(), true)['result'];
            foreach ($creatorsRest as $typeCreator) {
                foreach ($typeCreator['persons'] as $person) {
                    if (!empty($person['name'])) {
                        $creator = $this->creatorRepository->getByName($person['name']);
                        if (empty($creator)) {
                            $storeCreator = Request::create('POST');
                            $storeCreator->request->add(['name' => $person['name']]);
                            $storeCreator->request->add(['eng_name' => $person['eng_title']]);
                            $storeCreator->request->add(['kinopoisk_id' => $person['kp_id']]);
                            $storeCreator->request->add(['bio' => $person['bio']]);
                            $avatar = null;
                            foreach ($person['images'] as $img) {
                                $avatar = $img['path'];
                            }
                            $storeCreator->request->add(['avatar' => $avatar]);
                            $this->creatorRepository->store($storeCreator);
                            $creator = $this->creatorRepository->getByName($person['name']);
                        }
                        //Чтоб не было дублей при обновлении связи контента и актеров
                        $isExist = $this->contentCreatorRepository->isDepencyExist($item['title'], $creator->id);
                        if (!$isExist) {
                            $storeContentCreator = Request::create('POST');
                            $storeContentCreator->request->add(['content_id' => $content->id]);
                            $storeContentCreator->request->add(['creator_id' => $creator->id]);
                            $this->contentCreatorRepository->store($storeContentCreator);
                        }
                    }
                }
            }
            $a++;
        }
        return redirect('/home');
    }

    public function syncMoreFilms()
    {
        //Забираем актеров и прочих
        $client = new Client();
        $responseCreators = $client->get('https://api2.ivi.ru/mobileapi/video/persons/v5/?id=471475');
        $creatorsRest = json_decode($responseCreators->getBody()->getContents(), true)['result'];
        foreach ($creatorsRest as $typeCreator) {
            foreach ($typeCreator['persons'] as $person) {
                dd($person);
            }
        }

        return $this->syncCiemasRepository->syncMoreFilms();
    }


}
