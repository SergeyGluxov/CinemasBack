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
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class SyncMoreCinemasController extends Controller
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

    public function syncMoreFilms(Request $request)
    {

        $type = $request->get('type');
        for ($to = 0; $to < 48; $to += 24) {
            $clientCollection = new Client();
            $responseCollection = $clientCollection->get('https://more.tv/api/v4/web/projects?filter%5Bcategory%5D%5B0%5D=' . $type . '&filter%5BsubscriptionType%5D%5B0%5D=FREE&filter%5BisSeoSuitable%5D=true&sort%5B0%5D=viewTypeId&sort%5B1%5D=-keyRank&page%5Boffset%5D='.$to.'&page%5Blimit%5D=18');
            $jsonFormattedResult = json_decode($responseCollection->getBody()->getContents(), true);
            //Todo a - тестовое ограничение на количество запросов из ленты бесплатного контента
            foreach ($jsonFormattedResult['data']['projects'] as $contentItem) {
                $client = new Client();
                $response = $client->get('https://api.more.tv/v3/app/projects/' . $contentItem['id']);
                $jsonFormattedResult = json_decode($response->getBody()->getContents(), true);
                $content = $jsonFormattedResult['data'];
                $this->saveContent($content);
                $this->saveGenre($content);
                $this->saveCreators($content);
                $this->saveRelease($content);
            }
        }
        return redirect('/home');
    }


    public function saveRelease($item)
    {
        $content = $this->contentRepository->findFromTitle($item['title']);
        $client = new Client();
        $response = $client->get('https://more.tv/api/v4/web/Projects/' . $item['id'] . '/CurrentTrack');
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

    }

    public function saveContent($item)
    {
        $request = Request::create('POST');
        $request->request->add(['title' => $item['title']]);
        $request->request->add(['description' => $item['description']]);
        if (isset($item['kp_rating'])) {
            $request->request->add(['rating' => $item['ratingKinopoisk']]);
        }
        $request->request->add(['restrict' => $item['minAge']]);
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

        //Задать категорию
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
    }

    public function saveGenre($item)
    {
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
    }


    public function saveCreators($item)
    {
        //Забираем актеров и прочих
        $content = $this->contentRepository->findFromTitle($item['title']);
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
    }

    public function getCategory(array $items)
    {
        foreach ($items as $type) {
            $typeContent = $this->typeContentRepository->findFromTitle(Helper::getTypeContent($type));
            if (!empty($typeContent)) {
                return $typeContent;
            }
        }
    }

}