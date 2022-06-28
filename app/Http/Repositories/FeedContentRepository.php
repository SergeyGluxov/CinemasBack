<?php

namespace App\Http\Repositories;
use App\Http\Resources\FeedContentResource;
use App\Http\Resources\ContentResource;
use App\Models\Content;
use App\Models\FeedsContents;
use Illuminate\Http\Request;

class FeedContentRepository
{
    protected $contentGenre;

    public function __construct(FeedsContents $contentGenre)
    {
        $this->contentGenre = $contentGenre;
    }

    public function all(){
        FeedContentResource::withoutWrapping();
        return FeedContentResource::collection(FeedsContents::all());
    }


    public function find($id){}

    public function store(Request $request)
    {
        $contentGenreStore = new FeedsContents();
        $contentGenreStore->feed_id= $request->get('feed_id');
        $contentGenreStore->content_id= $request->get('content_id');
        $contentGenreStore->save();
        return response('Успешно добавлен', 200);
    }

    public function update(Request $request, $id)
    {

    }

    public function destroy($id)
    {
        $contentGenreDestroy = FeedsContents::findOrFail($id);
        if ($contentGenreDestroy->delete())
            return response('Успешно удалено!', 200);
    }
}
