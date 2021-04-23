@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'tw-form-input tw-rounded-md tw-shadow-sm form-control']) !!}>
