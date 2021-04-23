<button {{ $attributes->merge(['type' => 'submit', 'class' => 'tw-inline-flex tw-items-center tw-px-4 tw-py-2 tw-bg-gray-800 tw-border tw-border-transparent tw-rounded-md tw-font-semibold tw-text-xs tw-text-white tw-uppercase tw-tracking-widest hover:tw-bg-gray-700 active:tw-bg-gray-900 focus:tw-outline-none focus:tw-border-gray-900 focus:tw-shadow-outline-gray disabled:tw-opacity-25 tw-transition tw-ease-in-out tw-duration-150']) }} style="color: white;">
    {{ $slot }}
</button>
