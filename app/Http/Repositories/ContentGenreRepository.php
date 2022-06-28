<?php

namespace App\Http\Repositories;
use App\Http\Resources\ContentGenreResource;
use App\Http\Resources\ContentResource;
use App\Models\Content;
use App\Models\ContentsGenres;
use Illuminate\Http\Request;

class ContentGenreRepository
{
    protected $contentGenre;

    public function __construct(ContentsGenres $contentGenre)
    {
        $this->contentGenre = $contentGenre;
    }

    public function all(){
        ContentGenreResource::withoutWrapping();
        return ContentGenreResource::collection(ContentsGenres::all());
    }


    public function find($id){}

    public function store(Request $request)
    {
        $contentGenreStore = new ContentsGenres();
        $contentGenreStore->content_id= $request->get('content_id');
        $contentGenreStore->genre_id= $request->get('genre_id');
        $contentGenreStore->save();
        return response('Успешно добавлен', 200);
    }

    public function update(Request $request, $id)
    {

    }

    public function destroy($id)
    {
        $contentGenreDestroy = ContentsGenres::findOrFail($id);
        if ($contentGenreDestroy->delete())
            return response('Успешно удалено!', 200);
    }
}
