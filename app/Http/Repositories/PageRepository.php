<?php

namespace App\Http\Repositories;
use App\Http\Resources\FeedResource;
use App\Http\Resources\PageResource;
use App\Models\Feed;
use App\Models\Page;
use Illuminate\Http\Request;

class PageRepository
{
    protected $collection;

    public function __construct(Page $collection)
    {
        $this->collection = $collection;
    }

    public function all()
    {
        PageResource::withoutWrapping();
        return PageResource::collection(Page::all());
    }


    public function find($id)
    {
        PageResource::withoutWrapping();
        return new PageResource(Page::find($id));
    }

    public function store(Request $request)
    {
        $collectionStore = new Page();
        $collectionStore->title= $request->get('title');
        $collectionStore->save();
        return response('Успешно добавлен', 200);
    }

    public function update(Request $request, $id)
    {

    }

    public function destroy($id)
    {
        $collectionDestroy = Page::findOrFail($id);
        if ($collectionDestroy->delete())
            return response('Успешно удалено!', 200);
    }
}
