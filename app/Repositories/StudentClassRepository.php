<?php

namespace App\Repositories;

use App\Models\SchoolClass;
use App\Models\StudentClass;

class StudentClassRepository
{
    protected $model;

    public function __construct(StudentClass $model)
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
