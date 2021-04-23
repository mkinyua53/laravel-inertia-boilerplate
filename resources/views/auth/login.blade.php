<x-guest-layout>
    <x-jet-authentication-card>
        <x-slot name="logo">
            <x-jet-authentication-card-logo />
        </x-slot>

        <x-jet-validation-errors class="tw-mb-4" />

        @if (session('status'))
            <div class="tw-mb-4 tw-font-medium tw-text-sm tw-text-green-600">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div>
                <x-jet-label for="email" value="{{ __('Email / Username') }}" />
                <x-jet-input id="email" class="tw-block tw-mt-1 tw-w-full form-control" name="email" :value="old('email')" required autofocus />
            </div>

            <div class="tw-mt-4">
                <x-jet-label for="password" value="{{ __('Password') }}" />
                <x-jet-input id="password" class="tw-block tw-mt-1 tw-w-full form-control" type="password" name="password" required autocomplete="current-password" />
            </div>

            <div class="tw-block tw-mt-4">
                <label for="remember_me" class="tw-flex tw-items-center">
                    <input id="remember_me" type="checkbox" class="tw-form-checkbox form-control" name="remember">
                    <span class="tw-ml-2 tw-text-sm tw-text-gray-600">{{ __('Remember me') }}</span>
                </label>
            </div>

            <div class="tw-flex tw-items-center jsb tw-mt-4">
                <a class="tw-underline tw-text-sm tw-text-gray-600 tw-hover:tw-text-gray-900" href="{{ route('register') }}">
                    {{ __('Create Account') }}
                </a>
                @if (Route::has('password.request'))
                    <a class="tw-underline tw-text-sm tw-text-gray-600 tw-hover:tw-text-gray-900" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif

                <x-jet-button class="tw-ml-4">
                    {{ __('Login') }}
                </x-jet-button>
            </div>
            <div class="tw-flex tw-items-center tw-justify-end tw-mt-4">

            </div>
        </form>
        <a text class="tw-underline tw-text-sm tw-text-gray-600 tw-hover:tw-text-gray-900" small href="/privacy-policy">Privacy Policy</a>
        <a text class="tw-underline tw-text-sm tw-text-gray-600 tw-hover:tw-text-gray-900" small href="/terms-of-use">Terms Of Use</a>
    </x-jet-authentication-card>
</x-guest-layout>
