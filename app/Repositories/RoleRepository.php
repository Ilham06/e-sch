<?php

namespace App\Repositories;

use App\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Str;

class RoleRepository
{
    protected $model;

    public function __construct(Role $model)
    {
        $this->model = $model->orderBy('created_at', 'desc')->with('permissions');
    }

    public function getAll()
    {
        return Role::with('permissions')->get();
    }

    public function getPaginate($per_page, $keyword)
    {
        $roles = $this->model->withCount('users');
        $roles = $roles->when($keyword, function ($query) use ($keyword) {
            $query->where('name', 'like', '%' . $keyword . '%');
        })->paginate($per_page);

        return $roles;
    }

    function getPermissions($per_page, $keyword)
    {
        $permissions = Permission::orderBy('name', 'asc');
        $permissions = $permissions->when($keyword, function ($query) use ($keyword) {
            $query->where('name', 'like', '%' . $keyword . '%');
        });

        if ($per_page) {
            return $permissions->paginate($per_page);
        }
        return $permissions->get();
    }

    function store($data)
    {
        $role = Role::create(['name' => $data['name'], 'uuid' => Str::uuid(), 'guard_name' => 'web']);
        $data['permissions'] ? $role->syncPermissions($data['permissions']) : null;

        return $role;
    }

    function getById($id)
    {
        return Role::with('permissions')->find($id);
    }

    public function update($id, $data)
    {
        $role = Role::find($id);
        if (!$role) return false;
        $role->update(['name' => $data['name']]);
        if (!empty($data['permissions'])) {
            $role->syncPermissions($data['permissions']);
        } else {
            $role->revokePermissionTo($role->permissions);
        }

        return $role;
    }

    public function delete($id)
    {
        $role = Role::findOrFail($id);
        $role->revokePermissionTo($role->permissions);
        $role->delete();

        return true;
    }
}
