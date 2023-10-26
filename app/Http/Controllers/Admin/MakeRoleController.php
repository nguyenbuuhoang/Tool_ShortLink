<?php

namespace App\Http\Controllers\Admin;

use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;

class MakeRoleController extends Controller
{
    public function index()
    {
        Permission::create(['name' => 'Add user']);
        Permission::create(['name' => 'Edit user']);
        Permission::create(['name' => 'Delete User']);
        Permission::create(['name' => 'Add link']);
        Permission::create(['name' => 'Edit link']);
        Permission::create(['name' => 'Delete link']);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'editor']);
        Role::create(['name' => 'user']);
    }
}
