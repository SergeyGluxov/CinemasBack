<?php

namespace App\Http\Repositories;

use App\Http\Resources\UserRoleResource;
use App\Models\UsersContents;
use App\Models\UsersRoles;
use Illuminate\Http\Request;

class UserRoleRepository
{
    protected $userRole;

    public function __construct(UsersRoles $userRole)
    {
        $this->userRole = $userRole;
    }

    public function all()
    {
        UserRoleResource::withoutWrapping();
        return UserRoleResource::collection(UsersRoles::all());
    }


    public function find($id)
    {
    }

    public function store(Request $request)
    {
        $userRoleStore = UsersRoles::where('user_id', $request->get('user_id'))
            ->where('role_id', $request->get('role_id'))->first();
        if(empty($userRoleStore)){
            $userRoleStore = new UsersRoles();
            $userRoleStore->user_id= $request->get('user_id');
            $userRoleStore->role_id= $request->get('role_id');
            $userRoleStore->save();
        }
        return response('Запись обновлена', 200);
    }

    public function update(Request $request, $id)
    {

    }

    public function destroy($id)
    {
        $userRoleDestroy = UsersRoles::findOrFail($id);
        if ($userRoleDestroy->delete())
            return response('Успешно удалено!', 200);
    }

}
