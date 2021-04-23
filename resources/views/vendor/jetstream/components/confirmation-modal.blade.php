@props(['id' => null, 'maxWidth' => null])

<x-jet-modal :id="$id" :maxWidth="$maxWidth" {{ $attributes }}>
    <div class="bg-white tw-px-4 tw-pt-5 tw-pb-4 sm:p-6 sm:tw-pb-4">
        <div class="sm:tw-flex sm:tw-items-start">
            <div class="tw-mx-auto tw-flex-shrink-0 tw-flex tw-items-center tw-justify-center tw-h-12 tw-w-12 tw-rounded-full tw-bg-red-100 sm:tw-mx-0 sm:tw-h-10 sm:tw-w-10">
                <svg class="tw-h-6 tw-w-6 tw-text-red-600" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>

            <div class="tw-mt-3 tw-text-center sm:tw-mt-0 sm:tw-ml-4 sm:tw-text-left">
                <h3 class="tw-text-lg">
                    {{ $title }}
                </h3>

                <div class="tw-mt-2">
                    {{ $content }}
                </div>
            </div>
        </div>
    </div>

    <div class="tw-px-6 tw-py-4 tw-bg-gray-100 tw-text-right">
        {{ $footer }}
    </div>
</x-jet-modal>
