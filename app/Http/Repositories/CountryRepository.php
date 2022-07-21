<?php

namespace App\Http\Repositories;
use App\Http\Resources\ContentCreatorResource;
use App\Http\Resources\CountryResource;
use App\Models\Content;
use App\Models\ContentsCreators;
use App\Models\Country;
use Illuminate\Http\Request;

class CountryRepository
{
    protected $country;

    public function __construct(Country $country)
    {
        $this->country = $country;
    }

    public function all(){
        CountryResource::withoutWrapping();
        return CountryResource::collection(Country::all());
    }

    public function take($count){
        CountryResource::withoutWrapping();
        return CountryResource::collection(Country::limit($count)->get());
    }


    public function find($id){}

    public function store(Request $request)
    {
        $country = new Country();
        $country->title= $request->get('title');
        $country->save();
        return response('Успешно добавлен', 200);
    }

    public function update(Request $request, $id)
    {

    }

    public function destroy($id)
    {
        $country = Country::findOrFail($id);
        if ($country->delete())
            return response('Успешно удалено!', 200);
    }
}
