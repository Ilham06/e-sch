<?php

namespace App\Http\Controllers;

use App\Enum\ApiResponseEnum;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Repositories\UserRepository;
use App\SendResponseTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    use SendResponseTrait;

    private UserRepository $userRepository;
    private $notFoundMessage = 'Tidak ada data pengguna yang ditemukan';

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getAllStudent(Request $request)
    {
        $per_page = $request->get('per_page');
        $keyword = $request->get('keyword');

        $roles = $this->userRepository->getUsers($per_page, $keyword, 'siswa');
        return $this->sendResponse($roles, ApiResponseEnum::Success->description());
    }

    public function getAllTeacher(Request $request)
    {
        $per_page = $request->get('per_page');
        $keyword = $request->get('keyword');

        $roles = $this->userRepository->getUsers($per_page, $keyword, 'guru');
        return $this->sendResponse($roles, ApiResponseEnum::Success->description());
    }

    public function getAllAdmin(Request $request)
    {
        $per_page = $request->get('per_page');
        $keyword = $request->get('keyword');

        $roles = $this->userRepository->getUsers($per_page, $keyword, 'administrator');
        return $this->sendResponse($roles, ApiResponseEnum::Success->description());
    }

    public function store(CreateUserRequest $request)
    {
        try {
            DB::beginTransaction();
            $store = $this->userRepository->store($request->all());
            DB::commit();
            return $this->sendResponse($store, ApiResponseEnum::Created->description());
        } catch (Exception $e) {
            DB::rollBack();
            return $this->sendResponse($e->getMessage(), ApiResponseEnum::InternalError->description());
        }
    }

    public function get($id)
    {
        $user = $this->userRepository->getById($id);
        if (!$user) return $this->sendResponse(null, ApiResponseEnum::Custom->customDescription('ERROR', $this->notFoundMessage, 404));
        return $this->sendResponse($user, ApiResponseEnum::Success->description());
    }

    public function update(UpdateUserRequest $request, $id)
    {
        try {
            DB::beginTransaction();
            $store = $this->userRepository->update($request->all(), $id);
            DB::commit();
            return $this->sendResponse($store, ApiResponseEnum::Created->description());
        } catch (Exception $e) {
            DB::rollBack();
            return $this->sendResponse($e->getMessage(), ApiResponseEnum::InternalError->description());
        }
    }

    public function delete(String $id)
    {
        $role = $this->userRepository->delete($id);
        if (!$role) return $this->sendResponse(null, ApiResponseEnum::Custom->customDescription('ERROR', $this->notFoundMessage, 404));
        return $this->sendResponse($role, ApiResponseEnum::Success->description());
    }
}
