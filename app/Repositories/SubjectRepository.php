<?php

namespace App\Repositories;

use App\Models\Subject;

class SubjectRepository
{
    protected $model;

    public function __construct(Subject $model)
    {
        $this->model = $model->orderBy('created_at', 'desc');
    }

    public function getAll($per_page, $keyword, $is_major, $major_id)
    {
        $subjects = $this->model->with('major');

        $subjects = $this->model->when($keyword, function ($query) use ($keyword) {
            $query->where('name', 'like', '%' . $keyword . '%');
        });

        $subjects = $this->model->when($is_major, function ($query) use ($is_major) {
            $query->where('is_major_subject', $is_major);
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
        return $this->model->with('major')->find($id);
    }

    public function update($id, $data)
    {
        $subjects = $this->model->find($id);
        if (!$subjects) return false;
        $subjects->update($data);
        return $subjects;
    }

    public function delete($id)
    {
        $subject = $this->model->find($id);
        $subject->delete();

        return true;
    }
}
