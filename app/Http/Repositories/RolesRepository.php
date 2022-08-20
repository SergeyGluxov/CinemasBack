<?php

namespace App\Http\Repositories;

use App\Http\Resources\RolesResource;
use App\Models\Role;
use App\Models\UsersRoles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RolesRepository
{
    protected $role;

    public function __construct(Role $role)
    {
        $this->role = $role;
    }

    public function all()
    {
        RolesResource::withoutWrapping();
        return RolesResource::collection(Role::all());
    }

    public function store(Request $request)
    {
        $role = new Role();
        $role->name = $request->get('name');
        $role->save();
        return response('Роль добавлена', 200);
    }

    public function storeUserRole(Request $request)
    {
        $roleuser = new UsersRoles();
        $roleuser->user_id = $request->get('user_id');
        $roleuser->role_id = $request->get('role_id');
        $roleuser->save();
        return response('Создан новый сотрудник!', 200);
    }

    public function find($id)
    {
        RolesResource::withoutWrapping();
        return new RolesResource(Role::find($id));
    }

    public function deleteRole($request)
    {
        $id = $request->get('id');
        $appointmentDestroy = Role::findOrFail($id);
        if ($appointmentDestroy->delete())
            return response('Должность удалена!', 200);
    }

    public function destroy($id)
    {
        DB::table('users_roles')->where('user_id', '=', $id)->delete();
        return response('Сотрудник уволен!', 200);
    }
}
