<?php

namespace App\Http\Controllers;

use App\Repositories\RoleRepository;
use App\SendResponseTrait;
use Exception;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    use SendResponseTrait;

    private RoleRepository $roleRepository;

    public function __construct(RoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    public function getAll()
    {
        try {
            $roles = $this->roleRepository->getAll();
            return $this->sendResponse($roles, 'Sukses mendapatkan semua data role', 200);
        } catch (Exception $e) {
            return $this->sendResponse(null, $e->getMessage(), $e->getCode());
        }
    }

    public function store(Request $request)
    {
        try {
            $store = $this->roleRepository->store($request->all());
            return $this->sendResponse($store, 'Sukses membuat data role', 200);
        } catch (Exception $e) {
            return $this->sendResponse(null, $e->getMessage(), 400);
        }
    }
}
