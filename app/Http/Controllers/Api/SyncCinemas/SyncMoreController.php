<?php

namespace App\Http\Controllers\Api\SyncCinemas;


use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Repositories\ContentCreatorRepository;
use App\Http\Repositories\ContentGenreRepository;
use App\Http\Repositories\ContentRepository;
use App\Http\Repositories\CreatorRepository;
use App\Http\Repositories\EpisodeRepository;
use App\Http\Repositories\GenreRepository;
use App\Http\Repositories\ReleaseRepository;
use App\Http\Repositories\SeasonRepository;
use App\Http\Repositories\TypeContentRepository;
use App\Models\Country;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class SyncMoreController extends Controller
{
    protected $creatorRepository;
    protected $contentRepository;
    protected $genreRepository;
    protected $typeContentRepository;
    protected $contentGenreRepository;
    protected $contentCreatorRepository;
    protected $releaseRepository;
    protected $seasonRepository;
    protected $episodeRepository;

    public function __construct(CreatorRepository $creatorRepository,
                                ContentRepository $contentRepository,
                                GenreRepository $genreRepository,
                                TypeContentRepository $typeContentRepository,
                                ContentGenreRepository $contentGenreRepository,
                                ContentCreatorRepository $contentCreatorRepository,
                                ReleaseRepository $releaseRepository,
                                SeasonRepository $seasonRepository,
                                EpisodeRepository $episodeRepository
    )
    {
        $this->creatorRepository = $creatorRepository;
        $this->contentRepository = $contentRepository;
        $this->genreRepository = $genreRepository;
        $this->typeContentRepository = $typeContentRepository;
        $this->contentGenreRepository = $contentGenreRepository;
        $this->contentCreatorRepository = $contentCreatorRepository;
        $this->releaseRepository = $releaseRepository;
        $this->seasonRepository = $seasonRepository;
        $this->episodeRepository = $episodeRepository;
    }

    public function syncMoreFilms(Request $request)
    {
        $type = $request->get('type');

        $clientCollection = new Client();
        $responseCollection = $clientCollection->get('https://more.tv/api/v4/web/projects?filter%5Bcategory%5D%5B0%5D=' . $type . '&filter%5BsubscriptionType%5D%5B0%5D=FREE&filter%5BisSeoSuitable%5D=true&sort%5B0%5D=viewTypeId&sort%5B1%5D=-keyRank&page%5Boffset%5D=18&page%5Blimit%5D=18');
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


            if ($typeContent->title == "Сериал") {
                $client = new Client();
                $response = $client->get('https://api.more.tv/v2/app/Projects/' . $contentItem['id'] . '/seasons');
                $jsonFormattedResult = json_decode($response->getBody()->getContents(), true);
                $seasonInfo = $jsonFormattedResult['data'];
                foreach ($seasonInfo as $item) {
                    //Создаем сезон
                    $seasonDB = $this->seasonRepository->findFromTitle($content->id, $item['title']);

                    $storeSeason = Request::create('POST');
                    $storeSeason->request->add(['content_id' => $content->id]);
                    $storeSeason->request->add(['title' => $item['title']]);
                    if (empty($seasonDB)) {
                        $this->seasonRepository->store($storeSeason);
                    } else {
                        $this->seasonRepository->update($storeSeason, $seasonDB->id);
                    }

                    //Получаем сезон
                    $season = $this->seasonRepository->findFromTitle($content->id, $item['title']);

                    $client = new Client();
                    $responseEpisode = $client->get('https://more.tv/api/v4/web/seasons/' . $item['id'] . '/tracks');
                    $responseEpisodeJson = json_decode($responseEpisode->getBody()->getContents(), true);
                    $episodes = $responseEpisodeJson['data'];


                    foreach ($episodes as $episode) {
                        //Забираем или обновляем релиз
                        //Проверить, существует ли эпизод
                        $episodeDB = $this->episodeRepository->findFromTitle($season->id, $episode['title']);
                        $storeEpisode = Request::create('POST');
                        $storeEpisode->request->add(['season_id' => $season->id]);
                        $storeEpisode->request->add(['title' => $episode['title']]);
                        if (isset($episode['trackFreezeGallery'])) {
                            $poster = array_values($episode['trackFreezeGallery']['JPEG'])[count($episode['trackFreezeGallery']['JPEG']) - 1]['url'];
                            $storeEpisode->request->add(['poster' => $poster]);
                        }
                        if (empty($episodeDB)) {
                            $this->episodeRepository->store($storeEpisode);
                        } else {
                            $this->episodeRepository->update($storeEpisode, $episodeDB->id);
                        }

                        //Получаем сезон
                        $episodeDB = $this->episodeRepository->findFromTitle($season->id, $episode['title']);

                        $isReleaseFound = false;
                        foreach ($content->releases as $item) {
                            if ($item->cinema == "MORE") {
                                $isReleaseFound = true;
                            }
                        }
                        if (!$isReleaseFound) {
                            $storeRelease = Request::create('POST');
                            $storeRelease->request->add(['content_id' => $content->id]);
                            $storeRelease->request->add(['episode_id' => $episodeDB->id]);
                            $storeRelease->request->add(['cinema' => 'MORE']);
                            $storeRelease->request->add(['type' => 'web']);
                            $storeRelease->request->add(['url' => 'https://odysseus.more.tv/player/1788/' . $episode['hubId'] . '?p2p=0&startAt=0&web_version=2.55.8-eng&autoplay=1']);
                            $this->releaseRepository->store($storeRelease);
                        }
                    }
                }
            } else {
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

            }

            $a++;
        }
        return redirect('/home');
    }


}
