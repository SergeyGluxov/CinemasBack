<?php

namespace App\Http\Repositories;

use App\Http\Resources\ReleaseResource;
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
        $releaseStore->cinema = $request->get('cinema');
        $releaseStore->type = $request->get('type');
        $releaseStore->url = $request->get('url');
        $releaseStore->save();
        return response('Успешно добавлен', 200);
    }

    public function update(Request $request, $id)
    {
        $releaseStore = Release::find($id);
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

}

