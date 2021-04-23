<div class="md:tw-grid md:tw-grid-cols-3 md:tw-gap-6" {{ $attributes }}>
    <x-jet-section-title>
        <x-slot name="title">{{ $title }}</x-slot>
        <x-slot name="description">{{ $description }}</x-slot>
    </x-jet-section-title>

    <div class="mt-5 md:tw-mt-0 md:tw-col-span-2">
        <div class="tw-px-4 tw-py-5 sm:tw-p-6 tw-bg-white tw-shadow sm:tw-rounded-lg">
            {{ $content }}
        </div>
    </div>
</div>
