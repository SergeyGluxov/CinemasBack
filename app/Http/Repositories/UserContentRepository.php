<?php

namespace App\Http\Repositories;

use App\Http\Resources\ContentShortCollection;
use App\Http\Resources\FeedContentResource;
use App\Http\Resources\ContentResource;
use App\Http\Resources\UserContentResource;
use App\Models\Content;
use App\Models\FeedsContents;
use App\Models\UsersContents;
use Illuminate\Http\Request;

class UserContentRepository
{
    protected $userContent;

    public function __construct(UsersContents $userContent)
    {
        $this->userContent = $userContent;
    }

    public function all()
    {
        UserContentResource::withoutWrapping();
        return UserContentResource::collection(UsersContents::all());
    }


    public function find($id)
    {
    }

    public function store(Request $request)
    {
        $store = new UsersContents();
        $store->user_id = $request->get('user_id');
        $store->content_id = $request->get('content_id');
        $store->save();
        return response('Успешно добавлен', 200);
    }

    public function update(Request $request, $id)
    {

    }

    public function destroy($id)
    {
        $destroy = UsersContents::findOrFail($id);
        if ($destroy->delete())
            return response('Успешно удалено!', 200);
    }


    public function favorite()
    {
        return new ContentShortCollection(auth()->user()->contents()->paginate(15));
    }


    public function addFavorite(Request $request)
    {
        $store = new UsersContents();
        $store->user_id = auth()->user()->id;
        $store->content_id = $request->get('content_id');
        $store->save();
        return response(json_encode(true), 200);
    }

    public function removeFavorite(Request $request)
    {
        $deleteItem = UsersContents::where('user_id', auth()->user()->id)
            ->where('content_id', $request->get('content_id'))->get();
        foreach ($deleteItem as $item) {
            $item->delete();
        }
        return response(json_encode(true), 200);
    }
}
