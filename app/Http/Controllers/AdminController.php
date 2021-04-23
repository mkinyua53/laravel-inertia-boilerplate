<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     *
     */
    public function __construct()
    {
        $this->middleware('roles:UserManager')->except('user');
    }

    public function index()
    {
        return inertia('Admin/index');
    }

    public function users()
    {
        $users = User::all();

        return inertia('Admin/users/users', [
            'users'  => $users,
        ]);
    }

    public function roles()
    {
        $roles = Role::all();

        return inertia('Admin/roles/roles', [
            'roles' => $roles
        ]);
    }

    public function permissions()
    {
        $permissions = Permission::withCount('roles', 'users')->get();

        return inertia('Admin/permissions/permissions', [
            'permissions'   => $permissions
        ]);
    }

    public function user(User $user)
    {
        $permissions = Permission::all();

        $userperm = collect([]);

        $user->load('roles', 'permissions');

        foreach ($permissions as $perm) {
            if ($user->can($perm->name)) {
                $user->permissions->push($perm);
            }
        }

        return inertia('Admin/users/show', [
            'user' => $user,
            'roles' => Role::all()
        ]);
    }
}
