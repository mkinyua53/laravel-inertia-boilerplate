@if ($errors->any())
    <div {{ $attributes }}>
        <div class="tw-font-medium tw-text-red-600">{{ __('Whoops! Something went wrong.') }}</div>

        <ul class="tw-mt-3 tw-list-disc tw-list-inside tw-text-sm tw-text-red-600">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
