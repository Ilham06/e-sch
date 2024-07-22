<?php

namespace App\Repositories;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleRepository
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function getAll()
    {
        return Role::with('permissions')->get();
    }

    public function getPaginate()
    {
        return Role::with('permissions')->paginate(10);
    }

    function getPermissions()
    {
        return Permission::paginate(10);
    }

    function store($data)
    {
        $role = Role::create(['name' => $data['name']]);
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
