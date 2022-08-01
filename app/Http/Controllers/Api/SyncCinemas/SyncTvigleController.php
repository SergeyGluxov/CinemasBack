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

class SyncTvigleController extends Controller
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

    public function syncTvigleFilms()
    {
        $token = '';
        $allCount = 0;
        $freeCount = 0;
        $films = array();
        for ($currentPage = 1; $currentPage <= 12; $currentPage++) {
            //Авторизация
            $clientCollection = new Client(
                [
                    'headers' =>
                        [
                            'Authorization' => 'Token eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhcHBfaWQiOiJ0dmlnbGVfc21hcnRfdHZfMiIsInZlcnNpb24iOiIyLjAiLCJkZXZpY2VfaWQiOiIxMjM0NTY3ODkiLCJleHAiOjE2NTkzNzk0MTEsIm1hc2siOiIwMCJ9.-d905D0BMozW0Ynch2y4bZzb9xCG-5uOWz9hepRotio',
                            'Cookie'=>'is_auth=false',
                            'Accept-Encoding'=>'compress;q=0.5, gzip;q=1.0',
                            'Content-Type'=>'application/json; charset=UTF-8',
                            'Accept'=>'application/json',
                        ]
                ]);

            $responseCollection = $clientCollection->post('https://www.tvigle.ru/app_init/');
            dd($clientCollection);



            $encoded = json_encode($responseCollection, DEFINED('JSON_INVALID_UTF8_IGNORE')? JSON_INVALID_UTF8_IGNORE: 0);

            dd($encoded);


            $jsonFormattedResult = json_encode($responseCollection->getBody()->getContents(), true,);
            $token = $jsonFormattedResult['token'];

            /*
            $clientCollection = new Client();
            $responseCollection = $clientCollection->get('https://premier.one/uma-api/feeds/cardgroup/250?alias=free&style=portrait&link=https%253A%252F%252Ftnt-premier.ru&title=&bg_url=https%253A%252F%252Ftnt-premier.ru%252Fimg%252Fdevices-4.1168fded.png&picture_type=card_group&quantity=6&sort=publication_d&origin__type=hwi%2Crtb&is_active=1&system=hwi_vod_id&page=' . $currentPage);
            $jsonFormattedResult = json_decode($responseCollection->getBody()->getContents(), true);

            foreach ($jsonFormattedResult['results'] as $contentItem) {
                $allCount++;
                if ($contentItem['object']['type']['name'] == 'movie') {
                    $freeCount++;
                    array_push($films, $contentItem['object']['name']);
                }
            }*/
        }

        dd($films);
        return redirect('/home');
    }


}
