<?php

namespace App\Http\Controllers;

use App\Enum\ApiResponseEnum;
use App\Http\Requests\CreateSubjectRequest;
use App\Http\Requests\UpdateSubjectRequest;
use App\Repositories\SubjectRepository;
use App\SendResponseTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubjectController extends Controller
{
    use SendResponseTrait;

    private SubjectRepository $subjectRepository;
    private $notFoundMessage = 'Tidak ada data mata pelajaran yang ditemukan';

    public function __construct(SubjectRepository $subjectRepository)
    {
        $this->subjectRepository = $subjectRepository;
    }

    public function getAll(Request $request)
    {
        $per_page = $request->get('per_page');
        $keyword = $request->get('keyword');
        $is_major = $request->get('is_major');
        $major_id = $request->get('major_id');

        $roles = $this->subjectRepository->getAll($per_page, $keyword, $is_major, $major_id);
        return $this->sendResponse($roles, ApiResponseEnum::Success->description());
    }

    public function store(CreateSubjectRequest $request)
    {
        try {
            DB::beginTransaction();
            $store = $this->subjectRepository->store($request->all());
            DB::commit();
            return $this->sendResponse($store, ApiResponseEnum::Created->description());
        } catch (Exception $e) {
            DB::rollBack();
            return $this->sendResponse($e->getMessage(), ApiResponseEnum::InternalError->description());
        }
    }

    public function get(String $id)
    {
        $role = $this->subjectRepository->getById($id);
        if (!$role) return $this->sendResponse(null, ApiResponseEnum::Custom->customDescription('ERROR', $this->notFoundMessage, 404));

        return $this->sendResponse($role, ApiResponseEnum::Success->description());
    }

    public function update(UpdateSubjectRequest $request, $id)
    {
        try {
            DB::beginTransaction();
            $store = $this->subjectRepository->update($id, $request->all());
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
        $role = $this->subjectRepository->delete($id);
        if (!$role) return $this->sendResponse(null, ApiResponseEnum::Custom->customDescription('ERROR', $this->notFoundMessage, 404));
        return $this->sendResponse($role, ApiResponseEnum::Success->description());
    }
}
