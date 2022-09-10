<?php

namespace App\Http\Repositories;

use App\Http\Resources\ProfileResource;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use stdClass;

class ProfileRepository
{
    protected $profile;

    public function __construct(Profile $profile)
    {
        $this->profile = $profile;
    }

    public function userProfile()
    {
        $user = auth()->user();
        $profile = Profile::where('user_id', $user->id)->where('is_selected', 1)->first();
        if (empty($profile)) {
            return response()->json(null);
        }
        ProfileResource::withoutWrapping();
        return new ProfileResource($profile);
    }

    public function createOrSelectProfile(Request $request)
    {
        $user = auth()->user();
        $profiles = Profile::where('user_id', $user->id)->get();
        if ($profiles->isEmpty()) {
            //Если у пользователя нет профиля
            //По умолчанию выберем этот профиль
            $request->request->add(['is_selected' => 1]);
            $this->store($request);
            $profile = Profile::where('user_id', $user->id)->where('is_selected', 1)->first();
        } else {
            $profilesWithProvider = Profile::where('user_id', $user->id)
                ->where('provider', $request->get('provider'))
                ->get();
            if ($profilesWithProvider->isEmpty()) {
                $this->store($request);
            }
            $profiles = Profile::where('user_id', $user->id)->get();
            foreach ($profiles as $item) {
                if ($item->provider == $request->get('provider')) {
                    $item->is_selected = 1;
                    $item->save();
                } else {
                    $item->is_selected = 0;
                    $item->save();
                }
            }
            $profile = Profile::where('user_id', $user->id)->where('is_selected', 1)->first();
        }
        ProfileResource::withoutWrapping();
        return new ProfileResource($profile);
    }

    public function all()
    {
        ProfileResource::withoutWrapping();
        return ProfileResource::collection(Profile::all());
    }


    public function find($id)
    {
        ProfileResource::withoutWrapping();
        return new ProfileResource(Profile::find($id));
    }

    public function store(Request $request)
    {
        $profileStore = new Profile();
        $profileStore->user_id = $request->get('user_id');
        $profileStore->provider = $request->get('provider');
        $profileStore->email = $request->get('email');
        $profileStore->avatar = $request->get('avatar');
        $profileStore->save();
        return response('Успешно добавлен', 200);
    }

    public function update(Request $request, $id)
    {
        $profileStore = Profile::find($id);
        $profileStore->user_id = $request->get('user_id');
        $profileStore->provider = $request->get('provider');
        $profileStore->email = $request->get('email');
        $profileStore->avatar = $request->get('avatar');
        $profileStore->save();
        return response('Запись обновлена', 200);
    }

    public function destroy($id)
    {
        $profileDestroy = Profile::findOrFail($id);
        if ($profileDestroy->delete())
            return response('Успешно удалено!', 200);
    }

}

