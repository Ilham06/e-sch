<?php

namespace App\Http\Controllers;

use App\Enum\ApiResponseEnum;
use App\Http\Requests\CreateRoleRequest;
use App\Repositories\RoleRepository;
use App\SendResponseTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

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
        $roles = $this->roleRepository->getAll();
        return $this->sendResponse($roles, ApiResponseEnum::Success->description());
    }

    public function store(CreateRoleRequest $request)
    {
        try {
            DB::beginTransaction();
            $store = $this->roleRepository->store($request->all());
            DB::commit();
            return $this->sendResponse($store, ApiResponseEnum::Created->description());
        } catch (Exception $e) {
            DB::rollBack();
            return $this->sendResponse($e->getMessage(), ApiResponseEnum::InternalError->description());
        }
    }

    public function get(String $id)
    {
        $roles = $this->roleRepository->getById($id);
        return $this->sendResponse($roles, ApiResponseEnum::Success->description());
    }
}
