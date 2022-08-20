<?php

namespace App\Http\Controllers\Api\Roles;

use App\Http\Controllers\Controller;
use App\Http\Repositories\RolesRepository;
use Illuminate\Http\Request;

class RolesController extends Controller
{
    protected $rolesRepository;

    public function __construct(RolesRepository $rolesRepository)
    {
        $this->rolesRepository = $rolesRepository;
    }

    public function index(Request $request)
    {
        return $this->rolesRepository->all();
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        return $this->rolesRepository->store($request);
    }

    public function storeUserRole(Request $request)
    {
        return $this->rolesRepository->storeUserRole($request);
    }

    public function show($id)
    {
        return $this->rolesRepository->find($id);
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function deleteRole(Request $request)
    {
        return $this->rolesRepository->deleteRole($request);
    }

    public function destroy($id)
    {
        return $this->rolesRepository->destroy($id);
    }
}
