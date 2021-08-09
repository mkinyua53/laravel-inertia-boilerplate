<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Fortify\Fortify;
use Illuminate\Support\Facades\Hash;
use App\Http\Responses\LoginResponse;
use App\Actions\Fortify\CreateNewUser;
use App\Http\Responses\LogoutResponse;
use Illuminate\Support\ServiceProvider;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Laravel\Fortify\Contracts\LogoutResponse as LogoutResponseContract;
use Laravel\Fortify\Contracts\TwoFactorLoginResponse as TwoFactorLoginResponseContract;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Fortify::authenticateUsing(function (Request $request) {
            $identity = $request->email;
            $user = User::where('email', $identity)
                ->orWhere('username', $identity)
                ->first();

            if (
                $user &&
                Hash::check($request->password, $user->password)
            ) {
                return $user;
            }
        });

        Fortify::loginView(function () {
            return inertia('Auth/login', [
                'page_type' => 'auth'
            ]);
        });

        Fortify::registerView(function () {
            return inertia('Auth/UserRegister', [
                'page_type' => 'auth',
            ]);
        });

        Fortify::requestPasswordResetLinkView(function () {
            return inertia('Auth/forgotPassword', [
                'page_type' => 'auth'
            ]);
        });

        Fortify::resetPasswordView(function () {
            return inertia('Auth/resetPassword', [
                'page_type' => 'auth'
            ]);
        });

        Fortify::verifyEmailView(function () {
            return inertia('Auth/verifyEmail', [
                'page_type' => 'auth'
            ]);
        });

        Fortify::twoFactorChallengeView(function () {
            return inertia('Auth/2fa', [
                'page_type' => 'auth',
            ]);
        });

        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        $this->app->singleton(LoginResponseContract::class, LoginResponse::class);
        $this->app->singleton(TwoFactorLoginResponseContract::class, LoginResponse::class);
        $this->app->singleton(LogoutResponseContract::class, LogoutResponse::class);
    }
}
