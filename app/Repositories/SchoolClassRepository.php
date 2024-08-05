<?php

namespace App\Repositories;

use App\Models\SchoolClass;

class SchoolClassRepository
{
    protected $model;

    public function __construct(SchoolClass $model)
    {
        $this->model = $model->orderBy('created_at', 'desc');
    }

    public function getAll($per_page, $keyword, $rank, $major_id)
    {

        $subjects = $this->model->with('major', 'user')->withCount('students');

        $subjects = $this->model->when($keyword, function ($query) use ($keyword) {
            $query->where('name', 'like', '%' . $keyword . '%');
        });

        $subjects = $this->model->when($rank, function ($query) use ($rank) {
            $query->where('rank', $rank);
        });

        $subjects = $this->model->when($major_id, function ($query) use ($major_id) {
            $query->where('major_id', $major_id);
        });


        return $subjects->paginate($per_page);
    }

    public function store($data)
    {
        return $this->model->create($data);
    }

    function getById($id)
    {
        return $this->model->with('major', 'user', 'students.user', 'teachers.user')->find($id);
    }

    public function update($id, $data)
    {
        $classData = $this->model->find($id);
        $classData->update($data);

        return $classData;
    }

    public function delete($id)
    {
        $data = $this->model->find($id);
        return $data->delete();
    }
}
