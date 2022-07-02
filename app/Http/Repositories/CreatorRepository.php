<?php

namespace App\Http\Repositories;
use App\Http\Resources\CreatorResource;
use App\Models\Creator;
use App\Models\TypeContent;
use Illuminate\Http\Request;

class CreatorRepository
{
    protected $creator;

    public function __construct(Creator $creator)
    {
        $this->creator = $creator;
    }

    public function all()
    {
        CreatorResource::withoutWrapping();
        return CreatorResource::collection(Creator::all());
    }


    public function find($id)
    {
        CreatorResource::withoutWrapping();
        return new CreatorResource(Creator::find($id));
    }

    public function store(Request $request)
    {
        $creatorStore = new Creator();
        $creatorStore->name= $request->get('name');
        $creatorStore->eng_name= $request->get('eng_name');
        $creatorStore->kinopoisk_id= $request->get('kinopoisk_id');
        $creatorStore->ivi_id= $request->get('ivi_id');
        $creatorStore->bio= $request->get('bio');
        $creatorStore->avatar= $request->get('avatar');
        $creatorStore->save();
        return response('Успешно добавлен', 200);
    }

    public function update(Request $request, $id)
    {

    }

    public function destroy($id)
    {
        $creatorDestroy = Creator::findOrFail($id);
        if ($creatorDestroy->delete())
            return response('Успешно удалено!', 200);
    }

    public function getByName($name){
        return Creator::where('name', $name)->first();
    }
}
