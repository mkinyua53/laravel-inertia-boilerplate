@props(['for'])

@error($for)
    <p {{ $attributes->merge(['class' => 'tw-text-sm tw-text-red-600']) }}>{{ $message }}</p>
@enderror
