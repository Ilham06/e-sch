<?php

namespace App\Http\Controllers;

use App\Enum\ApiResponseEnum;
use App\Http\Requests\CreateScheduleLessonRequest;
use App\Repositories\ScheduleRepository;
use App\SendResponseTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClassScheduleController extends Controller
{
    use SendResponseTrait;

    private ScheduleRepository $scheduleRepository;

    public function __construct(
        ScheduleRepository $scheduleRepository)
    {
        $this->scheduleRepository = $scheduleRepository;
    }

    public function store(CreateScheduleLessonRequest $request) 
    {
        try {
    
            DB::beginTransaction();
            $store = $this->scheduleRepository->storeLesson($request->all());

            DB::commit();
            return $this->sendResponse($store, ApiResponseEnum::Created->description());
        } catch (Exception $e) {
            DB::rollBack();
            return $this->sendResponse($e->getMessage(), ApiResponseEnum::InternalError->description());
        }
    }

    public function get(String $id)
    {
        $data = $this->scheduleRepository->get($id);
        return $this->sendResponse($data, ApiResponseEnum::Success->description());
    }

    public function getByClass(String $class_id)
    {
        $data = $this->scheduleRepository->getByClass($class_id);
        return $this->sendResponse($data, ApiResponseEnum::Success->description());
    }

    public function update(CreateScheduleLessonRequest $request, $id) 
    {
        try {
    
            DB::beginTransaction();
            $store = $this->scheduleRepository->updateLesson($request->all(), $id);

            DB::commit();
            return $this->sendResponse($store, ApiResponseEnum::Created->description());
        } catch (Exception $e) {
            DB::rollBack();
            return $this->sendResponse($e->getMessage(), ApiResponseEnum::InternalError->description());
        }
    }

    public function delete(String $id)
    {
        $data = $this->scheduleRepository->deleteLesson($id);
        return $this->sendResponse($data, ApiResponseEnum::Success->description());
    }

    public function deleteAll(String $schedule_id)
    {
        $data = $this->scheduleRepository->deleteAll($schedule_id);
        return $this->sendResponse($data, ApiResponseEnum::Success->description());
    }
}
