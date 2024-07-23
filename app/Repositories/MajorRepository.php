<?php

namespace App\Repositories;

use App\Models\Major;

class MajorRepository
{
    protected $model;

    public function __construct(Major $model)
    {
        $this->model = $model->orderBy('created_at', 'desc');
    }

    public function getAll($per_page, $keyword)
    {
        $majors = $this->model->when($keyword, function ($query) use ($keyword) {
            $query->where('name', 'like', '%' . $keyword . '%');
        })->paginate($per_page);

        return $majors;
    }

    public function store($data)
    {
        return $this->model->create($data);
    }

    function getById($id)
    {
        return $this->model->find($id);
    }

    public function update($id, $data)
    {
        $major = $this->model->find($id);
        if (!$major) return false;
        $major->update($data);
        return $major;
    }

    public function delete($id)
    {
        $major = $this->model->find($id);
        $major->delete();

        return true;
    }
}
