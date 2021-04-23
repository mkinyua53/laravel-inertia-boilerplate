<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\User;
use App\Models\Permission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
	/**
	 *
	 */
	public function __construct()
	{
		$this->middleware('roles:UserManager');
	}

	/**
	 * Display a listing of roles
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		return inertia('Admin/roles/roles', [
			'roles' => Role::withCount('permissions', 'users')->get()
		]);
	}

	/**
	 * Create a new role
	 *
	 * @param \Illuminate\Http\Request $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		Validator::make($request->all(), [
			'name'  => 'required|unique:roles,name',
		])->validateWithBag('createRole');

		$role = Role::create($request->all());

		return redirect()->action([RoleController::class, 'show'], ['role' => $role->id])->with('message', 'Role created. <br> Attach permissions and then users');
	}

	/**
	 * Display the specified role
	 *
	 * @param \App\Models\Role $role
	 * @return \Illuminate\Http\Response
	 */
	public function show(Role $role)
	{
			return inertia('Admin/roles/show', [
				'role' => $role->load('permissions', 'users'),
				'users'	=> User::all(),
				'permissions'	=> Permission::all(),
			]);
	}

	/**
	 * Update the specified role in storage
	 *
	 * @param \Illuminate\Http\Request $request
	 * @param \App\Models\Role $role
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, Role $role)
	{
		Validator::make($request->all(), [
			'name'  => 'required|unique:roles,name,' . $role->id,
		])->validateWithBag('updateRole');

		$role->update($request->all());

		return redirect()
			->back()
			->with('message', 'Updated');
	}

	/**
	 * Delete the specified role
	 *
	 * @param \App\Models\Role $role
	 * @return \Illuinate\Http\Response
	 */
	public function destroy(Role $role)
	{
		if ($role->default) {
			return redirect()->back()->with('message', 'Sorry, you can not delete this role');
		}

		$role->delete();

		return redirect()->back()->with('message', 'Role deleted');
	}

	/**
		*
		* Attach role to user
		*
		* @param \App\Models\Role $role
		* @param \App\Models\User $user
		* @return \Illuminata\Http\Response
		*/
		public function attachUser(Role $role, User $user)
		{
			$role->users()->syncWithoutDetaching([$user->id]);

			return redirect()->back()->with('message', 'User attached');
		}

		/**
		*
		* Attach a permission to user
		*
		* @param \App\Models\Role $role
		* @param \App\Models\Permission $permission
		* @return Illuminate\Http\Response
		*/
		public function attachPermission(Role $role, Permission $permission)
		{
				$permission->roles()->syncWithoutDetaching([$role->id]);

				return redirect()
					->back()
					->with('message', 'Permission attached to the role');
		}

		/**
		*
		* Detach a user from a role
		*
		* @param \App\Models\Role $role
		* @param \App\Models\User $user
		* @return Illuminate\Http\Response
		**/
		public function detachUser(Role $role, User $user)
		{
				$user->roles()->detach($role->id);

				return redirect()
					->back()
					->with('message', 'User detached');
		}

		/**
		*
		* Detach a permission from a role
		*
		* @param \App\Models\Role $role
		* @param \App\Models\Permission $permission
		* @return Illuminate\Http\Response
		**/
		public function detachPermission(Role $role, Permission $permission)
		{
				$permission->roles()->detach($role->id);

				return redirect()
					->back()
					->with('message', 'Permission Detached from Role');
		}

		/**
		*
		* Detach all roles from a user
		*
		* @param \App\Models\User $user
		* @return Illuminate\Http\Response
		**/
		public function detachUserAll(User $user)
		{
				if ($user->id == Auth::user()->id) {
						return response()->json('Not allowed', 422);
				}
				$user->roles()->detach();

				return redirect()
					->back()
					->with('message', 'User Detached all Roles');
		}

		/**
		*
		* Detach all roles from a permission
		*
		* @param \App\Models\Permission $permission
		* @return Illuminate\Http\Response
		**/
		public function detachPermissionAll(Permission $permission)
		{
				$permission->roles()->detach();

				return redirect()
					->back()
					->with('message', 'Permission Detached of all roles');
		}
}
