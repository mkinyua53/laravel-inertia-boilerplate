<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Package;
use App\Models\Profession;
use App\Models\Professional;
use App\Models\ServiceRequest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MainController extends Controller
{
    public function index()
    {
        return inertia('welcome');
    }

    public function about()
    {
        return inertia('About');
    }

    public function dashboard()
    {
        return inertia('Dashboard');
    }

    public function register()
    {
        return inertia('Auth/Register');
    }

    public function login()
    {
        return inertia('Auth/login');
    }

    public function getPrivacyPolicy()
    {
        return inertia('Pages/privacy');
    }

    public function getTermsOfUse()
    {
        return inertia('Pages/terms');
    }

    public function push(Request $request)
    {
        $user = auth()->user();

        $user->updatePushSubscription($request->endpoint, $request->key, $request->token, $request->contentEncoding);
    }
}
