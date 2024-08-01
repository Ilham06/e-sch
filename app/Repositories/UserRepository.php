<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserRepository
{
    protected $model;

    public function __construct(User $model)
    {
        $this->model = $model->orderBy('created_at', 'desc');
    }

    public function getStudents($per_page, $keyword)
    {
        $student = $this->model->role('siswa');
        $student = $student->when($keyword, function ($query) use ($keyword) {
            $query->where('name', 'like', '%' . $keyword . '%');
        })->paginate($per_page);

        return $student;
    }

    public function getUsers($per_page, $keyword, $role)
    {
        $student = $this->model->role($role)->with('major');
        $student = $student->when($keyword, function ($query) use ($keyword) {
            $query->where('name', 'like', '%' . $keyword . '%');
        })->paginate($per_page);

        return $student;
    }

    function store($data)
    {
        $data['password'] = Hash::make($data['code']);
        $user = User::create($data);
        $user->assignRole($data['role']);

        return $user;
    }

    function getById($id)
    {
        return $this->model->with('major')->find($id);
    }

    function update($data, $id)
    {
        $user = $this->model->find($id);
        $user = $user->update($data);

        return $user;
    }

    public function delete($id)
    {
        $subject = $this->model->find($id);
        $subject->delete();

        return true;
    }
}
