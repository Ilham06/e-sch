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

    public function getAll(){
        return Role::with('permissions')->get();
    }

    public function getPaginate(){
        return Role::with('permissions')->paginate(10);
    }

    function getPermissions() {
        return Permission::paginate(10);
    }

    function store($data) {
        $role = Role::create(['name' => $data['name']]);
        $data['permissions'] ? $role->syncPermissions($data['permissions']) : null;

        return $role;
    }

    function getById($id) {
        return Role::with('permissions')->findOrFail($id);
    }
}
