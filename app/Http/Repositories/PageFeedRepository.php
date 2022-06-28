<?php

namespace App\Http\Repositories;
use App\Http\Resources\FeedContentResource;
use App\Http\Resources\ContentResource;
use App\Http\Resources\PageFeedResource;
use App\Models\Content;
use App\Models\FeedsContents;
use App\Models\PagesFeeds;
use Illuminate\Http\Request;

class PageFeedRepository
{
    protected $pageFeed;

    public function __construct(PagesFeeds $pageFeed)
    {
        $this->pageFeed = $pageFeed;
    }

    public function all(){
        PageFeedResource::withoutWrapping();
        return PageFeedResource::collection(PagesFeeds::all());
    }

    public function find($id)
    {
        PageFeedResource::withoutWrapping();
        return new PageFeedResource(PagesFeeds::find($id));
    }

    public function store(Request $request)
    {
        $pageFeedStore = new PagesFeeds();
        $pageFeedStore->feed_id= $request->get('feed_id');
        $pageFeedStore->page_id= $request->get('page_id');
        $pageFeedStore->save();
        return response('Успешно добавлен', 200);
    }

    public function update(Request $request, $id)
    {

    }

    public function destroy($id)
    {
        $pageFeedDestroy = PagesFeeds::findOrFail($id);
        if ($pageFeedDestroy->delete())
            return response('Успешно удалено!', 200);
    }
}
