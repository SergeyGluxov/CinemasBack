<?php

namespace App\Http\Repositories;

use App\Helpers\Helper;
use App\Models\Content;
use App\Models\FeedsContents;
use App\Models\Genre;
use App\Models\TypeContent;
use GuzzleHttp\Client;

class SyncCinemasRepository
{
    protected $content;

    public function __construct(Content $content)
    {
        $this->content = $content;
    }


    public function syncIviFilm()
    {

        $clientCollection = new Client();
        $responseCollection = $clientCollection->get('https://api2.ivi.ru/mobileapi/catalogue/v5/?category=14&paid_type=AVOD&from=0&to=29&withpreorderable=true&app_version=870&session=5f50069b8192933799505857_1640229232-0VSsdXnINDlnif4VJDNujuA&session_data=eyJ1aWQiOjgxOTI5MzM3OTk1MDU4NTd9.YNKr9A.s6dN769-1ipCvd3mfwm2vrPOb-8');
        $jsonFormattedResult = json_decode($responseCollection->getBody()->getContents(), true);

        $a = 0;
        foreach ($jsonFormattedResult['result'] as $contentItem) {
            if ($a == 5) break;

            $client = new Client();
            $response = $client->get('https://api2.ivi.ru/mobileapi/videoinfo/v7/?id=' . $contentItem['id'] . '&fields=id%2Ctitle%2Cfake%2Cpreorderable%2Chru%2Ccontent_paid_types%2Csubscription_names%2Ccompilation_hru%2Ckind%2Cadditional_data%2Crestrict%2Chd_available%2Chd_available_all%2C3d_available%2C3d_available_all%2Cuhd_available%2Cuhd_available_all%2Chdr10_available%2Chdr10_available_all%2Cdv_available%2Cdv_available_all%2Cfullhd_available%2Cfullhd_available_all%2Chdr10plus_available%2Chdr10plus_available_all%2Chas_5_1%2Cshields%2Civi_pseudo_release_date%2Cartists%2Cbudget%2Ccategories%2Ccountry%2Cdescription%2Csynopsis%2Cduration%2Cduration_minutes%2Cgenres%2Cgross_russia%2Cgross_usa%2Cgross_world%2Cimdb_rating%2Civi_rating_10%2Ckp_rating%2Crating%2Cseason%2Corig_title%2Cyear%2Cyears%2Cepisode%2Csubsites_availability%2Csubtitles%2Ccompilation%2Ccompilation_title%2Cdrm_only%2Chas_awards%2Cused_to_be_paid%2Civi_release_date%2Csharing_disabled%2Civi_rating_10_count%2Chas_comments%2Chas_reviews%2Clocalizations%2Cbest_watch_before%2Cthumbs%2Cposters&app_version=870&session=ef75c6824682118185663737_1670526595-09QR1b1flUUV3rsLeQstITw&session_data=eyJ1aWQiOjQ2ODIxMTgxODU2NjM3Mzd9.YqD0BA.Mne1AYKjiS-A-TdZcKYB16jIv8k');
            $jsonFormattedResult = json_decode($response->getBody()->getContents(), true);
            $item = $jsonFormattedResult['result'];
            $contentStore = Content::where('title', $item['title'])->first();

            if (empty($contentStore)) {
                $contentStore = new Content();
            }
            $contentStore->title = $item['title'];
            $contentStore->description = $item['description'];
            $contentStore->rating = $item['kp_rating'];
            $contentStore->restrict = intval($item['restrict']);
            $contentStore->year = $item['year'];
            $contentStore->country = Helper::getCountryIvi($item['country']);
            $contentStore->duration = $item['localizations'][0]['duration'];
            $contentStore->kinopoisk_id = $contentItem['kp_id'];
            foreach ($item['categories'] as $type) {
                $typeContent = TypeContent::where('title', Helper::getTypeContent($type))->first();
                if (!empty($typeContent)) {
                    $contentStore->type_content_id = $typeContent->id;
                }
            }
            $contentStore->save();

            foreach ($item['genres'] as $genre) {
                $genre = Genre::where('title', Helper::getGenreIvi($genre))->first();
                if (!empty($genre)) {
                    $contentStore = Content::where('title', $item['title'])->first();
                    if (!empty($contentStore)) {
                        $contentGenreStore = new FeedsContents();
                        $contentGenreStore->content_id = $contentStore->id;
                        $contentGenreStore->genre_id = $genre->id;
                        $contentGenreStore->save();
                    }
                }
            }
            $a++;
        }
        return redirect('/home');
    }

    public function syncMoreFilms()
    {
        $client = new Client();
        $response = $client->get('https://more.tv/api/v4/web/projects?filter%5Bcategory%5D%5B0%5D=MOVIE&filter%5BsubscriptionType%5D%5B0%5D=FREE&filter%5BisSeoSuitable%5D=true&sort%5B0%5D=viewTypeId&sort%5B1%5D=-keyRank&page%5Boffset%5D=18&page%5Blimit%5D=18');
        $jsonFormattedResult = json_decode($response->getBody()->getContents(), true);
        $a = 0;
        foreach ($jsonFormattedResult['data']['projects'] as $item) {
            if ($a < 10) {
                $contentStore = Content::where('title', $item['title'])->first();
                if (empty($contentStore)) {
                    $contentStore = new Content();
                }
                $contentStore->title = $item['title'];
                $contentStore->description = $item['description'];
                $contentStore->rating = $item['ratingKinopoisk'];
                $contentStore->restrict = 22;
                $contentStore->year = 2021;
                $contentStore->country = 2019;
                $contentStore->duration = 0;
                $contentStore->type_content_id = 1;
                $contentStore->save();
                $a++;
            } else {
                break;
            }
        }
        return redirect('/home');
    }
}

