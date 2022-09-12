<?php

namespace App\Http\Repositories;

use App\Http\Resources\EpisodeResource;
use App\Models\Season;
use App\Models\Episode;
use Illuminate\Http\Request;

class EpisodeRepository
{
    protected $episode;

    public function __construct(Episode $episode)
    {
        $this->episode = $episode;
    }

    public function all()
    {
        EpisodeResource::withoutWrapping();
        return EpisodeResource::collection(Episode::all());
    }


    public function find($id)
    {
        EpisodeResource::withoutWrapping();
        return new EpisodeResource(Episode::find($id));
    }

    public function store(Request $request)
    {
        $store = new Episode();
        $store->season_id = $request->get('season_id');
        $store->title = $request->get('title');
        $store->save();
        return response('Успешно добавлен', 200);
    }

    public function update(Request $request, $id)
    {
        $store = Episode::find($id);
        $store->season_id = $request->get('season_id');
        $store->title = $request->get('title');
        $store->save();
        return response('Запись обновлена', 200);
    }

    public function destroy($id)
    {
        $destroy = Episode::findOrFail($id);
        if ($destroy->delete())
            return response('Успешно удалено!', 200);
    }

    public function getSeasonEpisode(Request $request)
    {
        $season = Season::where('id', $request->get('season_id'))->firstOrFail();
        EpisodeResource::withoutWrapping();
        return EpisodeResource::collection($season->Episodes);
    }

    public function findFromTitle($season_id, $title)
    {
        return Episode::where('season_id', $season_id)->where('title', $title)->first();

    }

}

