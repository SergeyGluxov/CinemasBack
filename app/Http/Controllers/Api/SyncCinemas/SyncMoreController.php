<?php

namespace App\Http\Controllers\Api\SyncCinemas;


use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Repositories\ContentCreatorRepository;
use App\Http\Repositories\ContentGenreRepository;
use App\Http\Repositories\ContentRepository;
use App\Http\Repositories\CreatorRepository;
use App\Http\Repositories\GenreRepository;
use App\Http\Repositories\ReleaseRepository;
use App\Http\Repositories\TypeContentRepository;
use App\Models\Country;
use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;

class SyncMoreController extends Controller
{
    protected $creatorRepository;
    protected $contentRepository;
    protected $genreRepository;
    protected $typeContentRepository;
    protected $contentGenreRepository;
    protected $contentCreatorRepository;
    protected $releaseRepository;

    public function __construct(CreatorRepository $creatorRepository,
                                ContentRepository $contentRepository,
                                GenreRepository $genreRepository,
                                TypeContentRepository $typeContentRepository,
                                ContentGenreRepository $contentGenreRepository,
                                ContentCreatorRepository $contentCreatorRepository,
                                ReleaseRepository $releaseRepository
    )
    {
        $this->creatorRepository = $creatorRepository;
        $this->contentRepository = $contentRepository;
        $this->genreRepository = $genreRepository;
        $this->typeContentRepository = $typeContentRepository;
        $this->contentGenreRepository = $contentGenreRepository;
        $this->contentCreatorRepository = $contentCreatorRepository;
        $this->releaseRepository = $releaseRepository;
    }

    public function syncMoreFilms()
    {

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://more.tv/api/v4/web/projects?filter%5Bcategory%5D%5B0%5D=MOVIE&filter%5BsubscriptionType%5D%5B0%5D=FREE&filter%5BisSeoSuitable%5D=true&sort%5B0%5D=viewTypeId&sort%5B1%5D=-keyRank&page%5Boffset%5D=18&page%5Blimit%5D=18",// your preferred link
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                // Set Here Your Requesred Headers
                'Content-Type: application/json',
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            dd($err);
        } else {
            dd(json_decode($response));
        }






        $response = Http::get('https://more.tv/api/v4/web/projects?filter%5Bcategory%5D%5B0%5D=MOVIE&filter%5BsubscriptionType%5D%5B0%5D=FREE&filter%5BisSeoSuitable%5D=true&sort%5B0%5D=viewTypeId&sort%5B1%5D=-keyRank&page%5Boffset%5D=18&page%5Blimit%5D=18');
        dd($response->body());
        $cookieJar = CookieJar::fromArray([
            'Cookie' => '__lhash_=fe61d15f25baab9e62807d25caec02ab'
        ], 'more.tv');

        $clientCollection = new Client(
            [
                'headers' => [
                    'User-Agent' => 'PostmanRuntime/7.26.8',
                    'Host'=>'more.tv',
                    'Accept-Encoding'=>'gzip, deflate, br',
                    'Accept'=>'*/*',
                    'Postman-Token'=>'f15adc77-1b88-4d22-8f0b-3c47ab60a740'
                ],
                'cookies'=>$cookieJar,
                'allow_redirects' => true,
                'decode_content' => true
            ]
        );
        $responseCollection = $clientCollection->get('https://more.tv/api/v4/web/projects?filter%5Bcategory%5D%5B0%5D=MOVIE&filter%5BsubscriptionType%5D%5B0%5D=FREE&filter%5BisSeoSuitable%5D=true&sort%5B0%5D=viewTypeId&sort%5B1%5D=-keyRank&page%5Boffset%5D=18&page%5Blimit%5D=18');
        $jsonFormattedResult = json_decode($responseCollection->getBody()->getContents(), true);

        //Todo a - тестовое ограничение на количество запросов из ленты бесплатного контента
        $a = 0;
        foreach ($jsonFormattedResult['data']['projects'] as $contentItem) {
            if ($a == 5) break;
            $client = new Client();
            $response = $client->get('https://api.more.tv/v3/app/projects/' . $contentItem['id']);
            $jsonFormattedResult = json_decode($response->getBody()->getContents(), true);
            $item = $jsonFormattedResult['data'];
            $request = Request::create('POST');
            $request->request->add(['title' => $item['title']]);
            $request->request->add(['description' => $item['description']]);
            $request->request->add(['rating' => $item['ratingKinopoisk']]);
            $request->request->add(['restrict' => $item['minAge']]);
            //todo convert releaseDate to year
            if (!empty($item['releaseDate'])) {
                $request->request->add(['year' => Carbon::parse($item['releaseDate'])->year]);
            }
            $request->request->add(['duration' => null]);
            $request->request->add(['kinopoisk_id' => 0]);
            $request->request->add(['poster' => $item['projectPosterGallery']['JPEG']['W500H710']['url']]);


            $country = Country::where('title', $item['countries'][0]['label'])->first();
            if (!empty($country)) {
                $request->request->add(['country_id' => $country->id]);
            }

            //Задать тип контента
            $typeContent = $this->typeContentRepository->findFromTitle(Helper::getTypeContentMore($item['type']));

            if (!empty($typeContent)) {
                $request->request->add(['type_content_id' => $typeContent->id]);
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
                $genre = $this->genreRepository->findFromTitle(Helper::getGenreMore($genre['id']));
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
            if (!empty($item['cast'])) {
                foreach ($item['cast']['shortCast'] as $person) {
                    if (!empty($person['name'])) {
                        $creator = $this->creatorRepository->getByName($person['name']);
                        if (empty($creator)) {
                            $storeCreator = Request::create('POST');
                            $storeCreator->request->add(['name' => $person['name']]);
                            $storeCreator->request->add(['avatar' => $person['photo']]);
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


            //Забираем или обновляем релиз

            $client = new Client();
            $response = $client->get('https://more.tv/api/v4/web/Projects/' . $contentItem['id'] . '/CurrentTrack');
            $jsonFormattedResult = json_decode($response->getBody()->getContents(), true);
            $releaseInfo = $jsonFormattedResult['data'];


            $isReleaseFound = false;
            foreach ($content->releases as $item) {
                if ($item->cinema == "MORE") {
                    $isReleaseFound = true;
                }
            }
            if (!$isReleaseFound) {
                $storeRelease = Request::create('POST');
                $storeRelease->request->add(['content_id' => $content->id]);
                $storeRelease->request->add(['cinema' => 'MORE']);
                $storeRelease->request->add(['type' => 'web']);
                $storeRelease->request->add(['url' => 'https://odysseus.more.tv/player/1788/' . $releaseInfo['hubId'] . '?p2p=0&startAt=0&web_version=2.55.8-eng&autoplay=1']);
                $this->releaseRepository->store($storeRelease);
            }

            $a++;
        }
        return redirect('/home');
    }


}
