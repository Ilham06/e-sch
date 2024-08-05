<?php

namespace App\Repositories;

use App\Models\Schedule;
use App\Models\ScheduleLesson;

class ScheduleRepository
{
    protected $model;

    public function __construct(ScheduleLesson $model)
    {
        $this->model = $model->orderBy('created_at', 'desc');
    }

    public function store($class_id) {
        foreach (['1','2','3','4','5','6'] as $key => $day) {
            Schedule::create([
                'day' => $day,
                'school_class_id' => $class_id
            ]);
        }
    }

    public function storeLesson($data)
    {
        return $this->model->create($data);
    }
    
    public function get($id)
    {
        return $this->model->with('user','subject')->find($id);
    }

    public function updateLesson($data, $id)
    {
        return $this->model->whereId($id)->update($data);
    }

    public function deleteLesson($id) 
    {
        $lesson = $this->model->find($id);
        return $lesson->delete();
    }

    public function getByClass($id)
    {
        return Schedule::whereSchoolClassId($id)->with('lessons.user','lessons.subject')->get();
    }

    function deleteAll($schedule_id)
    {
        return $this->model->whereScheduleId($schedule_id)->delete();
    }
}
