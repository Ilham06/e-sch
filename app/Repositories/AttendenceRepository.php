<?php

namespace App\Repositories;

use App\Models\Attendance;

class AttendenceRepository
{
    protected $model;

    public function __construct(Attendance $model)
    {
        $this->model = $model;
    }

    public function store($data)
    {
        return $this->model->create($data);
    }


}
