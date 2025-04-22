<?php

namespace App\Http\Controllers;

use App\Enum\ApiResponseEnum;
use App\Http\Requests\CreateAttendanceRequest;
use App\Models\SchoolClass;
use App\SendResponseTrait;
use App\Services\AttendenceService;
use Carbon\Carbon;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    use SendResponseTrait;

    private AttendenceService $attendanceService;

    public function __construct(
        AttendenceService $attendanceService)
    {
        $this->attendanceService = $attendanceService;
    }

    public function getAvailaableLesson(Request $request)
    {
        $data = $this->attendanceService->getAvailableLesson($request->get('class_id'));
        return $this->sendResponse($data, ApiResponseEnum::Success->description());
    }

    public function getAttendance(Request $request)
    {
        $data = $this->attendanceService->getAvailableAttendence($request->get('class_id'), $request->get('lesson_id'));
        return $this->sendResponse($data, ApiResponseEnum::Success->description());
    }

    public function store(CreateAttendanceRequest $request)
    {
        $data = $this->attendanceService->storeUserAttendance($request->all());
        return $this->sendResponse($data, ApiResponseEnum::Success->description());
    }
}
