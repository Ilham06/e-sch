<?php

namespace App\Http\Controllers;

use App\Enum\ApiResponseEnum;
use App\Http\Requests\CreateRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
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

    public function getPagination(Request $request)
    {
        $page = $request->get('page');
        $per_page = $request->get('per_page');
        $keyword = $request->get('keyword');

        $roles = $this->roleRepository->getPaginate($per_page, $keyword);
        return $this->sendResponse($roles, ApiResponseEnum::Success->description());
    }

    public function getAllPermissions(Request $request)
    {
        $per_page = $request->get('per_page');
        $keyword = $request->get('keyword');

        $permissions = $this->roleRepository->getPermissions($per_page, $keyword);
        return $this->sendResponse($permissions, ApiResponseEnum::Success->description());
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
        $role = $this->roleRepository->getById($id);
        if (!$role) return $this->sendResponse(null, ApiResponseEnum::Custom->customDescription('NOT OKE', 'Tidak ada data role ditemukan', 404));
        return $this->sendResponse($role, ApiResponseEnum::Success->description());
    }

    public function update(UpdateRoleRequest $request, $id)
    {
        try {
            DB::beginTransaction();
            $store = $this->roleRepository->update($id, $request->all());
            if (!$store) return $this->sendResponse(null, ApiResponseEnum::Custom->customDescription('NOT OKE', 'Tidak ada data role ditemukan', 404));
            DB::commit();
            return $this->sendResponse($store, ApiResponseEnum::Created->description());
        } catch (Exception $e) {
            DB::rollBack();
            return $this->sendResponse($e->getMessage(), ApiResponseEnum::InternalError->description());
        }
    }

    public function delete(String $id)
    {
        $role = $this->roleRepository->delete($id);
        if (!$role) return $this->sendResponse(null, ApiResponseEnum::Custom->customDescription('NOT OKE', 'Tidak ada data role ditemukan', 404));
        return $this->sendResponse($role, ApiResponseEnum::Success->description());
    }
}
