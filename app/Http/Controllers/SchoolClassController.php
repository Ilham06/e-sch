<?php

namespace App\Http\Controllers;

use App\Enum\ApiResponseEnum;
use App\Http\Requests\CreateClassRequest;
use App\Repositories\ScheduleRepository;
use App\Repositories\SchoolClassRepository;
use App\Repositories\StudentClassRepository;
use App\Repositories\TeacherClassRepository;
use App\SendResponseTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SchoolClassController extends Controller
{
    use SendResponseTrait;

    private SchoolClassRepository $schoolClassRepository;
    private StudentClassRepository $studentClassRepository;
    private TeacherClassRepository $teacherClassRepository;
    private ScheduleRepository $scheduleRepository;

    public function __construct(
        SchoolClassRepository $schoolClassRepository, 
        StudentClassRepository $studentClassRepository, 
        TeacherClassRepository $teacherClassRepository, 
        ScheduleRepository $scheduleRepository)
    {
        $this->schoolClassRepository = $schoolClassRepository;
        $this->studentClassRepository = $studentClassRepository;
        $this->teacherClassRepository = $teacherClassRepository;
        $this->scheduleRepository = $scheduleRepository;
    }

    public function getAll(Request $request)
    {
        $per_page = $request->get('per_page');
        $keyword = $request->get('keyword');
        $rank = $request->get('rank');
        $major_id = $request->get('major_id');

        $roles = $this->schoolClassRepository->getAll($per_page, $keyword, $rank, $major_id);
        return $this->sendResponse($roles, ApiResponseEnum::Success->description());
    }

    public function store(CreateClassRequest $request)
    {
        try {
            $input = $request->except(['teachers', 'students']);

            DB::beginTransaction();
            $store = $this->schoolClassRepository->store($input);

            foreach ($request->teachers as $key => $teacher) {
                $this->teacherClassRepository->store([
                    'user_id' => $teacher,
                    'school_class_id' => $store->id
                ]);
            }

            foreach ($request->students as $key => $student) {
                $this->studentClassRepository->store([
                    'user_id' => $student,
                    'school_class_id' => $store->id
                ]);
            }

            $this->scheduleRepository->store($store->id);

            DB::commit();
            return $this->sendResponse($store, ApiResponseEnum::Created->description());
        } catch (Exception $e) {
            DB::rollBack();
            return $this->sendResponse($e->getMessage(), ApiResponseEnum::InternalError->description());
        }
    }

    public function get(String $id)
    {
        $role = $this->schoolClassRepository->getById($id);
        return $this->sendResponse($role, ApiResponseEnum::Success->description());
    }

    public function update(CreateClassRequest $request, $id)
    {
        try {
            $input = $request->except(['teachers', 'students']);

            DB::beginTransaction();

            $update = $this->schoolClassRepository->update($id, $input);
            $this->studentClassRepository->deleteByClass($id);
            $this->teacherClassRepository->deleteByClass($id);

            foreach ($request->teachers as $key => $teacher) {
                $this->teacherClassRepository->store([
                    'user_id' => $teacher,
                    'school_class_id' => $update->id
                ]);
            }

            foreach ($request->students as $key => $student) {
                $this->studentClassRepository->store([
                    'user_id' => $student,
                    'school_class_id' => $update->id
                ]);
            }

            DB::commit();
            return $this->sendResponse($update, ApiResponseEnum::Created->description());
        } catch (Exception $e) {
            DB::rollBack();
            return $this->sendResponse($e->getMessage(), ApiResponseEnum::InternalError->description());
        }
    }

    public function delete(String $id)
    {
        $role = $this->schoolClassRepository->delete($id);
        return $this->sendResponse($role, ApiResponseEnum::Success->description());
    }
}
