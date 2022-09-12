<?php

namespace App\Http\Repositories;

use App\Http\Resources\ReleaseResource;
use App\Models\Content;
use App\Models\Episode;
use App\Models\Release;
use Illuminate\Http\Request;

class ReleaseRepository
{
    protected $release;

    public function __construct(Release $release)
    {
        $this->release = $release;
    }

    public function all()
    {
        ReleaseResource::withoutWrapping();
        return ReleaseResource::collection(Release::all());
    }


    public function find($id)
    {
        ReleaseResource::withoutWrapping();
        return new ReleaseResource(Release::find($id));
    }

    public function store(Request $request)
    {
        $releaseStore = new Release();
        $releaseStore->content_id = $request->get('content_id');
        $releaseStore->episode_id = $request->get('episode_id');
        $releaseStore->cinema = $request->get('cinema');
        $releaseStore->type = $request->get('type');
        $releaseStore->url = $request->get('url');
        $releaseStore->save();
        return response('Успешно добавлен', 200);
    }

    public function update(Request $request, $id)
    {
        $releaseStore = Release::find($id);
        $releaseStore->content_id = $request->get('content_id');
        $releaseStore->cinema = $request->get('cinema');
        $releaseStore->type = $request->get('type');
        $releaseStore->url = $request->get('url');
        $releaseStore->save();
        return response('Запись обновлена', 200);
    }

    public function destroy($id)
    {
        $releaseDestroy = Release::findOrFail($id);
        if ($releaseDestroy->delete())
            return response('Успешно удалено!', 200);
    }

    public function getContentRelease(Request $request){
        $content = Content::where('id', $request->get('content_id'))->first();
        ReleaseResource::withoutWrapping();
        return ReleaseResource::collection($content->releases);
    }

    public function getEpisodeRelease(Request $request){
        $episode = Episode::where('id', $request->get('episode_id'))->first();
        ReleaseResource::withoutWrapping();
        return ReleaseResource::collection($episode->releases);
    }

}

