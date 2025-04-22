<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\AttendanceUser;
use App\Models\SchoolClass;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AttendenceService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function getAvailableLesson($class_id)
    {
        $dayNow = Carbon::now()->format('N');

        $class = SchoolClass::with('user')->withWhereHas('schedules', function ($query) use ($dayNow) {
            $query->where('day', $dayNow)->withWhereHas('lessons', function ($query) {
                $query->with('subject')->where('is_break', '0');
            });
        })->find($class_id);

        return $class->schedules->first()->lessons;
    }

    public function getAvailableAttendence($class_id, $lesson_id)
    {
        $dayNow = Carbon::now()->format('N');
        $daysInMonth = Carbon::now()->daysInMonth;
        $currentMonth = Carbon::now()->month;

        $class = SchoolClass::with('user')->whereHas('schedules', function ($query) use ($dayNow) {
            $query->where('day', $dayNow)->whereHas('lessons', function ($query) {
                $query->with('subject')->where('is_break', '0');
            });
        })->find($class_id);

        $attendence = Attendance::with('schedule_lesson.subject')
            ->where('school_class_id', $class_id)
            ->where('schedule_lesson_id', $lesson_id)
            ->whereMonth('created_at', $currentMonth)
            ->first();

        if (!$attendence) {
            $attendence = Attendance::create([
                'school_class_id' => $class_id,
                'schedule_lesson_id' => $lesson_id
            ]);

            foreach ($class->students as $key => $student) {
                for ($i = 1; $i < $daysInMonth + 1; $i++) {
                    AttendanceUser::create([
                        'attendance_id' => $attendence->id,
                        'user_id' => $student->user_id,
                        'day' => $i,
                    ]);
                }
            }
        }

        $students = User::withWhereHas('attendances', function ($query) use ($attendence) {
            $query->where('attendance_id', $attendence?->id);
        })->orderBy('name', 'ASC')->get();

        return compact('class','students','attendence');
    }

    public function storeUserAttendance($request)
    {
        $dayNow = Carbon::now()->day;

        $attendence = AttendanceUser::where('attendance_id', $request['attendance_id'])
            ->where('user_id', $request['user_id'])
            ->where('day', $dayNow)
            ->first();

        $attendence->status = $request['status'];
        $attendence->note = $request['note'];
        $attendence->absent_by = Auth::user();
        $attendence->save();

        return $attendence;
    }
}
