<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Exception;

class AuthController extends Controller
{
    /* *
    * Initialise permissions
     */
    public function permissions()
    {
        $permissions = [
            [
                'name'  => 'dashboard.view',
                'label' => 'View main dashboard. You still need individual permissions for the menu items'
            ]
        ];

        foreach ($permissions as $key) {
            Permission::firstOrCreate(['name' => $key['name']], $key);
            Permission::updateOrCreate(['name' => $key['name']], ['label' => $key['label']]);
        }

        return $this;
    }

    /**
     * Initialise roles
     */
    public function roles()
    {
        $roles = [
            [
                'name'  => 'Developer',
                'label' => 'Holds all permissions and intended for developers and support team members'
            ],
            [
                'name'  => 'SuperAdmin',
                'label' => 'Holds all permissions'
            ],
            [
                'name' => 'UserManager',
                'label' => 'Authorization to grant and revoke users access'
            ],
            [
                'name' => 'Admin',
                'label' => 'View administrator tasks'
            ],
        ];

        foreach ($roles as $key) {
            Role::firstOrCreate(['name' => $key['name']], $key);
            Role::updateOrCreate(['name' => $key['name']], ['label' => $key['label'], 'default' => 'DEFAULT']);
        }

        return $this;
    }

    /**
     * Initialise the main roles
     */
    public function initRoles()
    {
        $developer = Role::where('name', 'Developer')->first();
        $superadmin = Role::where('name', 'SuperAdmin')->first();
        $usermanager = Role::where('name', 'UserManager')->first();

        $allPermissions = Permission::all();

        $developer->permissions()->syncWithoutDetaching($allPermissions);
        $superadmin->permissions()->syncWithoutDetaching($allPermissions);
        $usermanager->permissions()->syncWithoutDetaching($allPermissions);

        return $this;
    }

    /**
     * Install Authorization
     */
    public function installAuth()
    {
        try {
            $this->permissions()->roles()->initRoles();
        } catch (Exception $e) {
            return $e;
        }

        $users = User::count();
        if ($users === 1) {
            if (auth()->check()) {
                $user = auth()->user();
            }
            if ($user) {
                $roles = Role::where('name', 'SuperAdmin')
                    ->orWhere('name', 'UserManager')
                    ->get();

                $user->roles()->attach($roles);
            }
        }

        return $this;
    }

    /**
     * Reset Auth
     */
    public function resetAuth()
    {
        $user = Auth::user();

        DB::table('role_user')->truncate();
        DB::table('permission_user')->truncate();
        DB::table('permission_role')->truncate();
        DB::table('roles')->delete();
        DB::table('permissions')->delete();

        $this->installAuth();
        $roles = Role::where('name', 'SuperAdmin')
            ->orWhere('name', 'UserManager')
            ->get();

        $user->roles()->attach($roles);

        return $this;
    }

    public function handleInstall()
    {
        $this->installAuth();

        return redirect()->back()->with('message', 'Authentication tables has been updated');
    }

    public function handleReset()
    {
        if (!Auth::user()->hasAnyRoles(['Developer', 'SuperAdmin'])) {
            return (new \Illuminate\Http\Response)->setStatusCode(403, 'Not Allowed');
        }

        $this->resetAuth();

        if (User::count() === 1) {
            return redirect()->route('/');
        }

        return redirect()->back()->with('message', 'Authentication tables has been reset. <br> You must attach permissions and roles to users afresh.');
    }
}
