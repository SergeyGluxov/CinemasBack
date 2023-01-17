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
use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class SyncRutubeController extends Controller
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

    public function syncSmotrim()
    {
        $clientCollection = new Client();
        $responseCollection = $clientCollection->get('https://rutube.ru/api/feeds/movies/');
        $jsonFormattedResult = json_decode($responseCollection->getBody()->getContents(), true);

        //Todo a - тестовое ограничение на количество запросов из ленты бесплатного контента
        $a = 0;
        foreach ($jsonFormattedResult['tabs'] as $tab) {
            //$a = 0;
            foreach ($tab['resources'] as $feeds) {
                if ($feeds['content_type']['model'] === 'subscriptionfilms') {
                    //Пагинация
                    $page = 1;
                    do {
                        $feedClient = new Client(['http_errors' => false]);
                        $nextPage = $feeds['url'] . '?page=' . $page;
                        $feedsResponseCollection = $feedClient->get($nextPage);
                        if ($feedsResponseCollection->getStatusCode() != 200 || $page == 3) {
                            break;
                        }

                        $feedsResponse = json_decode($feedsResponseCollection->getBody()->getContents(), true);
                        foreach ($feedsResponse['results'] as $contentItem) {
                            //if ($a == 3) break;
                            if (!$contentItem['object']['is_paid']) {
                                if (isset($contentItem)) {
                                    $content = $contentItem['object'];
                                    $this->saveContent($content);
                                    /*
                                    $this->saveCreators($content);
                                    $this->saveRelease($content);*/
                                    $a++;
                                }
                            }
                        }
                        $page++;
                    } while (true);
                }

            }
        }

        return redirect('/home');
    }


    public function saveRelease($contentItem, $metaId)
    {
        $content = $this->contentRepository->findFromTitle($contentItem['name']);
        //Забираем или обновляем релиз
        $isReleaseFound = false;
        foreach ($content->releases as $item) {
            if ($item->cinema == "RUTUBE") {
                $isReleaseFound = true;
            }
        }

        if (!$isReleaseFound) {
            $client = new Client(['http_errors' => false]);
            $clientRequest = $client->get('https://rutube.ru/api/play/options/' . $metaId);
            if ($clientRequest->getStatusCode() == 200) {
                $response = json_decode($clientRequest->getBody()->getContents(), true);
                $storeRelease = Request::create('POST');
                $storeRelease->request->add(['content_id' => $content->id]);
                $storeRelease->request->add(['cinema' => 'RUTUBE']);
                $storeRelease->request->add(['type' => 'web']);
                $storeRelease->request->add(['url' =>
                    <<<HERE
                        <iframe id="my-player" width="720" height="405" src="https://rutube.ru/embed/$metaId" frameborder="0" frameborder="0" allowfullscreen webkitAllowFullScreen mozallowfullscreen allowfullscreen allow="encrypted-media"></iframe>
                        <script type="text/javascript">
                            window.addEventListener('message', function (event) {
                                var message = JSON.parse(event.data);
                                switch (message.type) {
                                    case 'player:rollState':
                                        if(message.data.state==='complete')
                                            play();
                                        break;
                                }
                            })
                            function play() {
                                var player = document.getElementById('my-player');
                                player.contentWindow.postMessage(JSON.stringify({
                                type: 'player:play',
                                data: {}
                                }), '*');
                                player.contentWindow.postMessage(JSON.stringify({
                                type: 'player:setCurrentTime',
                                data: {
                                    time: 0
                                }
                                }), '*');
                            }
                            </script>;
                HERE]);
                $this->releaseRepository->store($storeRelease);
            }

        }

    }


    public function saveContent($item)
    {
        if (isset($item['type'])) {
            if (isset($item['type']['title'])) {
                if ($item['type']['title'] === 'Фильм' || $item['type']['title'] === 'Сериал') {
                    $request = Request::create('POST');
                    $request->request->add(['title' => $item['name']]);
                    $request->request->add(['description' => $item['description']]);
                    //$request->request->add(['restrict' => $item['age']]);
                    //$request->request->add(['year' => $item['year']]);
                    /*if (isset($item['kp_id'])) {
                        $request->request->add(['kinopoisk_id' => $item['kp_id']]);
                    }*/
                    $request->request->add(['poster' => $item['picture']]);

                    foreach ($item['countries'] as $countryItem) {
                        $country = Country::where('title', $countryItem['name'])->first();
                        if (!empty($country)) {
                            $request->request->add(['country_id' => $country->id]);
                        }
                    }

                    //Задать категорию
                    $typeContent = $this->getCategory($item['type']['title']);
                    if (!empty($typeContent)) {
                        $request->request->add(['type_content_id' => $typeContent->id]);
                    }

                    $contentStore = $this->contentRepository->findFromTitle($item['name']);

                    $clientMeta = new Client(['http_errors' => false]);
                    $responseMeta = $clientMeta->get($item['content']);
                    if ($responseMeta->getStatusCode() == 200) {
                        $jsonFormattedResultMeta = json_decode($responseMeta->getBody()->getContents(), true);
                        $metaId = null;
                        foreach ($jsonFormattedResultMeta['results'] as $metaItem) {
                            $request->request->add(['duration' => $metaItem['duration']]);
                            $metaId = $metaItem['id'];
                        }

                        if (empty($contentStore)) {
                            $this->contentRepository->store($request);
                        } else {
                            $this->contentRepository->update($request, $contentStore->id);
                        }

                        $this->saveGenre($item);
                        $this->saveRelease($item, $metaId);
                    }

                }
            }
        }
    }

    public function saveGenre($item)
    {
        $content = $this->contentRepository->findFromTitle($item['name']);
        //Задать связи с жанрами
        foreach ($item['genres'] as $genre) {
            $genre = $this->genreRepository->findFromTitle($genre['name']);
            if (!empty($genre)) {
                $isExist = $this->contentGenreRepository->isDepencyExist($item['name'], $genre->id);
                if (!$isExist) {
                    $storeContentGenre = Request::create('POST');
                    $storeContentGenre->request->add(['content_id' => $content->id]);
                    $storeContentGenre->request->add(['genre_id' => $genre->id]);
                    $this->contentGenreRepository->store($storeContentGenre);
                }
            }
        }
    }


    public function getCategory($type)
    {
        $typeContent = $this->typeContentRepository->findFromTitle(Helper::getTypeContentRutube($type));
        if (!empty($typeContent)) {
            return $typeContent;
        }
    }

}
