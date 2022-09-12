<?php

namespace App\Http\Repositories;

use App\Http\Resources\SeasonResource;
use App\Models\Content;
use App\Models\Season;
use Illuminate\Http\Request;

class SeasonRepository
{
    protected $season;

    public function __construct(Season $season)
    {
        $this->season = $season;
    }

    public function all()
    {
        SeasonResource::withoutWrapping();
        return SeasonResource::collection(Season::all());
    }


    public function find($id)
    {
        SeasonResource::withoutWrapping();
        return new SeasonResource(Season::find($id));
    }

    public function store(Request $request)
    {
        $seasonStore = new Season();
        $seasonStore->content_id = $request->get('content_id');
        $seasonStore->title = $request->get('title');
        $seasonStore->save();
        return response('Успешно добавлен', 200);
    }

    public function update(Request $request, $id)
    {
        $seasonStore = Season::find($id);
        $seasonStore->content_id = $request->get('content_id');
        $seasonStore->title = $request->get('title');
        $seasonStore->save();
        return response('Запись обновлена', 200);
    }

    public function destroy($id)
    {
        $seasonDestroy = Season::findOrFail($id);
        if ($seasonDestroy->delete())
            return response('Успешно удалено!', 200);
    }

    public function getContentSeason(Request $request){
        $content = Content::where('id', $request->get('content_id'))->firstOrFail();
        SeasonResource::withoutWrapping();
        return SeasonResource::collection($content->seasons);
    }

}

