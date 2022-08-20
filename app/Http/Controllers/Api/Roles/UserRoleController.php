<?php

namespace App\Http\Controllers\Api\Roles;

use App\Http\Controllers\Controller;
use App\Http\Repositories\UserRoleRepository;
use Illuminate\Http\Request;

class UserRoleController extends Controller
{
    protected $userRolesRepository;

    public function __construct(UserRoleRepository $userRolesRepository)
    {
        $this->userRolesRepository = $userRolesRepository;
    }

    public function index(Request $request)
    {
        return $this->userRolesRepository->all();
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        return $this->userRolesRepository->store($request);
    }


    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        return $this->userRolesRepository->destroy($id);
    }
}
