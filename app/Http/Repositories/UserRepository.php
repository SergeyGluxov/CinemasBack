<?php

namespace App\Http\Repositories;


use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserRepository
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function all()
    {
        UserResource::withoutWrapping();
        return UserResource::collection(User::all());
    }


    public function find($id)
    {
        UserResource::withoutWrapping();
        return new UserResource(User::find($id));
    }


    public function update(Request $request, $id)
    {
        $userStore = User::find($id);
        $userStore->name = $request->get('name');
        $userStore->save();
        return response('Информация о пользователе обновлена', 200);
    }

    public function destroy($id)
    {
        $userDestroy = User::findOrFail($id);
        if ($userDestroy->delete())
            return response('Пользователь успешно удален!', 200);
    }


    public function profile(Request $request)
    {
        UserResource::withoutWrapping();
        return new UserResource($request->user());
    }

}

