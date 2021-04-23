<x-guest-layout>
    <x-jet-authentication-card>
        <x-slot name="logo">
            <x-jet-authentication-card-logo />
        </x-slot>

        <x-jet-validation-errors class="tw-mb-4" />

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div>
                <x-jet-label for="name" value="{{ __('Name') }}" />
                <x-jet-input id="name" class="tw-block tw-mt-1 tw-w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            </div>

            <div class="tw-mt-4">
                <x-jet-label for="email" value="{{ __('Email') }}" />
                <x-jet-input id="email" class="tw-block tw-mt-1 tw-w-full" type="email" name="email" :value="old('email')" required />
            </div>

            <div class="tw-mt-4">
                <x-jet-label for="username" value="{{ __('Username') }}" />
                <x-jet-input id="username" class="tw-block tw-mt-1 tw-w-full" name="username" :value="old('username')" />
            </div>

            <div class="tw-mt-4">
                <x-jet-label for="password" value="{{ __('Password') }}" />
                <x-jet-input id="password" class="tw-block tw-mt-1 tw-w-full" type="password" name="password" required autocomplete="new-password" />
            </div>

            <div class="tw-mt-4">
                <x-jet-label for="password_confirmation" value="{{ __('Confirm Password') }}" />
                <x-jet-input id="password_confirmation" class="tw-block tw-mt-1 tw-w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
            </div>

            <div class="tw-flex tw-items-center tw-justify-end tw-mt-4">
                <a class="tw-underline tw-text-sm tw-text-gray-600 hover:tw-text-gray-900" href="{{ route('login') }}">
                    {{ __('Already registered?') }}
                </a>

                <x-jet-button class="tw-ml-4">
                    {{ __('Register') }}
                </x-jet-button>
            </div>
        </form>
        <a text class="tw-underline tw-text-sm tw-text-gray-600 tw-hover:tw-text-gray-900" small href="/privacy-policy">Privacy Policy</a>
        <a text class="tw-underline tw-text-sm tw-text-gray-600 tw-hover:tw-text-gray-900" small href="/terms-of-use">Terms Of Use</a>
    </x-jet-authentication-card>
</x-guest-layout>
