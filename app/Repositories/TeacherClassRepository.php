<?php

namespace App\Repositories;

use App\Models\SchoolClass;
use App\Models\StudentClass;
use App\Models\TeacherClass;

class TeacherClassRepository
{
    protected $model;

    public function __construct(TeacherClass $model)
    {
        $this->model = $model->orderBy('created_at', 'desc');
    }

    public function store($data)
    {
        return $this->model->create($data);
    }

    function deleteByClass($classId)
    {
        return $this->model->whereSchoolClassId($classId)->delete();
    }
}
