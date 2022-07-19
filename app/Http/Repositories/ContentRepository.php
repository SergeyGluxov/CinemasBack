<?php

namespace App\Http\Repositories;
use App\Http\Resources\ContentResource;
use App\Http\Resources\ContentShortCollection;
use App\Models\Content;
use App\Models\Genre;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class ContentRepository
{
    protected $content;

    public function __construct(Content $content)
    {
        $this->content = $content;
    }

    public function all()
    {
        return new ContentShortCollection(Content::paginate(15));
    }


    public function find($id)
    {
        ContentResource::withoutWrapping();
        return new ContentResource(Content::find($id));
    }

    public function store(Request $request)
    {
        $contentStore = new Content();
        $contentStore->title= $request->get('title');
        $contentStore->description= $request->get('description');
        $contentStore->rating= $request->get('rating');
        $contentStore->restrict= $request->get('restrict');
        $contentStore->year= $request->get('year');
        $contentStore->country= $request->get('country');
        $contentStore->duration= $request->get('duration');
        $contentStore->type_content_id= $request->get('type_content_id');
        $contentStore->kinopoisk_id= $request->get('kinopoisk_id');
        $contentStore->poster= $request->get('poster');
        $contentStore->save();
        return response('Успешно добавлен', 200);
    }

    public function update(Request $request, $id)
    {
        $contentStore = Content::find($id);
        $contentStore->title= $request->get('title');
        $contentStore->description= $request->get('description');
        $contentStore->rating= $request->get('rating');
        $contentStore->restrict= $request->get('restrict');
        $contentStore->year= $request->get('year');
        $contentStore->country= $request->get('country');
        $contentStore->duration= $request->get('duration');
        $contentStore->type_content_id= $request->get('type_content_id');
        $contentStore->kinopoisk_id= $request->get('kinopoisk_id');
        $contentStore->poster= $request->get('poster');
        $contentStore->save();
        return response('Запись обновлена', 200);
    }

    public function destroy($id)
    {
        $contentDestroy = Content::findOrFail($id);
        if ($contentDestroy->delete())
            return response('Успешно удалено!', 200);
    }

    public function syncIviFilm()
    {
        $client = new Client();
        $response = $client->get('https://api2.ivi.ru/mobileapi/videoinfo/v7/?id=419192&fields=id%2Ctitle%2Cfake%2Cpreorderable%2Chru%2Ccontent_paid_types%2Csubscription_names%2Ccompilation_hru%2Ckind%2Cadditional_data%2Crestrict%2Chd_available%2Chd_available_all%2C3d_available%2C3d_available_all%2Cuhd_available%2Cuhd_available_all%2Chdr10_available%2Chdr10_available_all%2Cdv_available%2Cdv_available_all%2Cfullhd_available%2Cfullhd_available_all%2Chdr10plus_available%2Chdr10plus_available_all%2Chas_5_1%2Cshields%2Civi_pseudo_release_date%2Cartists%2Cbudget%2Ccategories%2Ccountry%2Cdescription%2Csynopsis%2Cduration%2Cduration_minutes%2Cgenres%2Cgross_russia%2Cgross_usa%2Cgross_world%2Cimdb_rating%2Civi_rating_10%2Ckp_rating%2Crating%2Cseason%2Corig_title%2Cyear%2Cyears%2Cepisode%2Csubsites_availability%2Csubtitles%2Ccompilation%2Ccompilation_title%2Cdrm_only%2Chas_awards%2Cused_to_be_paid%2Civi_release_date%2Csharing_disabled%2Civi_rating_10_count%2Chas_comments%2Chas_reviews%2Clocalizations%2Cbest_watch_before%2Cthumbs%2Cposters%2Ckp_id&app_version=870&session=ef75c6824682118185663737_1670526595-09QR1b1flUUV3rsLeQstITw&session_data=eyJ1aWQiOjQ2ODIxMTgxODU2NjM3Mzd9.YqD0BA.Mne1AYKjiS-A-TdZcKYB16jIv8k');
        $jsonFormattedResult = json_decode($response->getBody()->getContents(), true);
        $item = $jsonFormattedResult['result'];
        dd($item);
        $contentStore = Content::where('title',$item['title'])->first();

        if(empty($contentStore)){
            $contentStore = new Content();
        }
        $contentStore->title = $item['title'];
        $contentStore->description = $item['description'];
        $contentStore->rating = $item['kp_rating'];
        $contentStore->restrict= 22;
        $contentStore->year = $item['year'];
        $contentStore->country = 2019;
        $contentStore->duration = $item['localizations'][0]['duration'];
        $contentStore->poster = $item['posters'][0]['url'];
        $contentStore->kinopoisk_id= $request->get('kp_id');
        $contentStore->type_content_id= 1;
        $contentStore->save();

        /*foreach ($jsonFormattedResult as $item) {
            $genre = new Genre();
            $genre->title=$item['title'];
            $genre->save();
            $contentStore = new Content();
            $contentStore->title = $item['title'];
            $contentStore->description = $item['description'];
            $contentStore->rating = $item['kp_rating'];
            $contentStore->restrict= 18;
            $contentStore->year = $item['year'];
            $contentStore->country = 2022;
            $contentStore->duration = $item['localizations'][0]['duration'];
            $contentStore->type_content_id= 1;
            $contentStore->save();
        }*/
        return redirect('/home');
    }

    public function syncMoreFilms()
    {
        $client = new Client();
        $response = $client->get('https://more.tv/api/v4/web/projects?filter%5Bcategory%5D%5B0%5D=MOVIE&filter%5BsubscriptionType%5D%5B0%5D=FREE&filter%5BisSeoSuitable%5D=true&sort%5B0%5D=viewTypeId&sort%5B1%5D=-keyRank&page%5Boffset%5D=18&page%5Blimit%5D=18');
        $jsonFormattedResult = json_decode($response->getBody()->getContents(), true);
        $a = 0;
        foreach ($jsonFormattedResult['data']['projects'] as $item) {
            if($a<10){
                $contentStore = Content::where('title',$item['title'])->first();
                if(empty($contentStore)){
                    $contentStore = new Content();
                }
                $contentStore->title = $item['title'];
                $contentStore->description = $item['description'];
                $contentStore->rating = $item['ratingKinopoisk'];
                $contentStore->restrict= 22;
                $contentStore->year = 2021;
                $contentStore->country = 2019;
                $contentStore->duration = 0;
                $contentStore->type_content_id= 1;
                $contentStore->save();
                $a++;
            }else{
                break;
            }
        }
        return redirect('/home');
    }



    public function findFromTitle($title){
        return Content::where('title', $title)->first();
    }
}

