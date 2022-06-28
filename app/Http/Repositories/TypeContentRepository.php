<?php

namespace App\Http\Repositories;
use App\Http\Resources\TypeContentResource;
use App\Models\TypeContent;
use Illuminate\Http\Request;

class TypeContentRepository
{
    protected $typeContent;

    public function __construct(TypeContent $typeContent)
    {
        $this->typeContent = $typeContent;
    }

    public function all()
    {
        TypeContentResource::withoutWrapping();
        return TypeContentResource::collection(TypeContent::all());
    }


    public function find($id)
    {
        TypeContentResource::withoutWrapping();
        return new TypeContentResource(TypeContent::find($id));
    }

    public function store(Request $request)
    {
        $typeContentStore = new TypeContent();
        $typeContentStore->title = $request->get('title');
        $typeContentStore->save();
        return response('Успешно добавлен', 200);
    }

    public function update(Request $request, $id)
    {

    }

    public function destroy($id)
    {
        $typeContentDestroy = TypeContent::findOrFail($id);
        if ($typeContentDestroy->delete())
            return response('Успешно удалено!', 200);
    }
}
