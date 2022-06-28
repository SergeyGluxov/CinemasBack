<?php

namespace App\Http\Repositories;
use App\Http\Resources\FeedResource;
use App\Models\Feed;
use Illuminate\Http\Request;

class FeedRepository
{
    protected $collection;

    public function __construct(Feed $collection)
    {
        $this->collection = $collection;
    }

    public function all()
    {
        FeedResource::withoutWrapping();
        return FeedResource::collection(Feed::all());
    }


    public function find($id)
    {
        FeedResource::withoutWrapping();
        return new FeedResource(Feed::find($id));
    }

    public function store(Request $request)
    {
        $collectionStore = new Feed();
        $collectionStore->title= $request->get('title');
        $collectionStore->save();
        return response('Успешно добавлен', 200);
    }

    public function update(Request $request, $id)
    {

    }

    public function destroy($id)
    {
        $collectionDestroy = Feed::findOrFail($id);
        if ($collectionDestroy->delete())
            return response('Успешно удалено!', 200);
    }
}
