<?php

namespace App\Http\Repositories;
use App\Http\Resources\ContentCreatorResource;
use App\Models\Content;
use App\Models\ContentsCreators;
use Illuminate\Http\Request;

class ContentCreatorRepository
{
    protected $contentCreator;

    public function __construct(ContentsCreators $contentCreator)
    {
        $this->contentCreator = $contentCreator;
    }

    public function all(){
        ContentCreatorResource::withoutWrapping();
        return ContentCreatorResource::collection(ContentsCreators::all());
    }


    public function find($id){}

    public function store(Request $request)
    {
        $contentCreatorStore = new ContentsCreators();
        $contentCreatorStore->content_id= $request->get('content_id');
        $contentCreatorStore->creator_id= $request->get('creator_id');
        $contentCreatorStore->save();
        return response('Успешно добавлен', 200);
    }

    public function update(Request $request, $id)
    {

    }

    public function destroy($id)
    {
        $contentCreatorDestroy = ContentsCreators::findOrFail($id);
        if ($contentCreatorDestroy->delete())
            return response('Успешно удалено!', 200);
    }


    public function isDepencyExist($title, $genreId){
        $content = Content::where('title', $title)->first();
        if(empty($content)){
            return false;
        }
        $contentCreator = ContentsCreators::where('content_id', $content->id)
            ->where('creator_id',$genreId)->first();
        if(!empty($contentCreator)){
            return true;
        }else{
            return false;
        }
    }
}
