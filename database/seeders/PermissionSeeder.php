<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Str;


class PermissionSeeder extends Seeder
{
    private $permissions = [
        'MANAGE_ROLE',
        'VIEW_ROLE',
        'VIEW_PERMISSION',
    ];


    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        foreach ($this->permissions as $permission) {
            Permission::create(['name' => $permission, 'uuid' => Str::uuid()]);
        }
    }
}
