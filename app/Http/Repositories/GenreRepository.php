<?php

namespace App\Http\Repositories;
use App\Http\Resources\GenreResource;
use App\Models\Genre;
use Illuminate\Http\Request;

class GenreRepository
{
    protected $genre;

    public function __construct(Genre $genre)
    {
        $this->genre = $genre;
    }

    public function all()
    {
        GenreResource::withoutWrapping();
        return GenreResource::collection(Genre::all());
    }


    public function find($id)
    {
        GenreResource::withoutWrapping();
        return new GenreResource(Genre::find($id));
    }

    public function store(Request $request)
    {
        $genreStore = new Genre();
        $genreStore->title= $request->get('title');
        $genreStore->save();
        return response('Успешно добавлен', 200);
    }

    public function update(Request $request, $id)
    {

    }

    public function destroy($id)
    {
        $genreDestroy = Genre::findOrFail($id);
        if ($genreDestroy->delete())
            return response('Успешно удалено!', 200);
    }
}
