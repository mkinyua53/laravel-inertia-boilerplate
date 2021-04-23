<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class UserController extends Controller
{
    public function __construct() {
        $this->middleware(['roles:UserManager']);
    }

    public function activate(User $user)
    {
        $user->forceFill(['active' => '1']);
        $user->save();

        return redirect()->back()->with('message', $user->name . ' activated!');
    }

    public function deactivate(User $user)
    {
        $user->forceFill(['active' => '0']);
        $user->save();

        return redirect()->back()->with('message', $user->name . ' deactivated!');
    }

    public function verify(User $user)
    {
        $user->forceFill(['email_verified_at' => '1']);
        $user->save();

        return redirect()->back()->with('message', $user->name . ' verified!');
    }

    public function unverify(User $user)
    {
        $user->forceFill(['email_verified_at' => null]);
        $user->save();

        return redirect()->back()->with('message', 'Success!');
    }

    public function loginAs(Request $request)
    {
        $user = auth()->user();
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
        $new = User::findorFail($request->id);

        Auth::guard('web')->loginUsingId($new->id, true);

        // return auth('web')->user();

        \Log::info('Admin user ' . $user->email . ' logged in as ' . $new->email);

        return redirect()->route('profile.show')->with('message', 'You are now logged in as '. $new->email);
    }
}
