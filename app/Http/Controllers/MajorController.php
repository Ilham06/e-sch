<?php

namespace App\Http\Controllers;

use App\Enum\ApiResponseEnum;
use App\Http\Requests\CreateMajorRequest;
use App\Http\Requests\UpdateMajorRequest;
use App\Repositories\MajorRepository;
use App\SendResponseTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MajorController extends Controller
{
    use SendResponseTrait;

    private MajorRepository $majorRepository;
    private $notFoundMessage = 'Tidak ada data jurusan yang ditemukan';

    public function __construct(MajorRepository $majorRepository)
    {
        $this->majorRepository = $majorRepository;
    }

    public function getAll(Request $request)
    {
        $per_page = $request->get('per_page');
        $keyword = $request->get('keyword');

        $roles = $this->majorRepository->getAll($per_page, $keyword);
        return $this->sendResponse($roles, ApiResponseEnum::Success->description());
    }

    public function store(CreateMajorRequest $request)
    {
        try {
            DB::beginTransaction();
            $store = $this->majorRepository->store($request->all());
            DB::commit();
            return $this->sendResponse($store, ApiResponseEnum::Created->description());
        } catch (Exception $e) {
            DB::rollBack();
            return $this->sendResponse($e->getMessage(), ApiResponseEnum::InternalError->description());
        }
    }

    public function get(String $id)
    {
        $role = $this->majorRepository->getById($id);
        if (!$role) return $this->sendResponse(null, ApiResponseEnum::Custom->customDescription('ERROR', $this->notFoundMessage, 404));

        return $this->sendResponse($role, ApiResponseEnum::Success->description());
    }

    public function update(UpdateMajorRequest $request, $id)
    {
        try {
            DB::beginTransaction();
            $store = $this->majorRepository->update($id, $request->all());
            if (!$store) return $this->sendResponse(null, ApiResponseEnum::Custom->customDescription('ERROR', $this->notFoundMessage, 404));
            DB::commit();
            return $this->sendResponse($store, ApiResponseEnum::Created->description());
        } catch (Exception $e) {
            DB::rollBack();
            return $this->sendResponse($e->getMessage(), ApiResponseEnum::InternalError->description());
        }
    }

    public function delete(String $id)
    {
        $role = $this->majorRepository->delete($id);
        if (!$role) return $this->sendResponse(null, ApiResponseEnum::Custom->customDescription('ERROR', $this->notFoundMessage, 404));
        return $this->sendResponse($role, ApiResponseEnum::Success->description());
    }
}
