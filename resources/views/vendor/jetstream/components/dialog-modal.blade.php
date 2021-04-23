@props(['id' => null, 'maxWidth' => null])

<x-jet-modal :id="$id" :maxWidth="$maxWidth" {{ $attributes }}>
    <div class="px-6 py-4">
        <div class="tw-text-lg">
            {{ $title }}
        </div>

        <div class="tw-mt-4">
            {{ $content }}
        </div>
    </div>

    <div class="tw-px-6 tw-py-4 tw-bg-gray-100 tw-text-right">
        {{ $footer }}
    </div>
</x-jet-modal>
