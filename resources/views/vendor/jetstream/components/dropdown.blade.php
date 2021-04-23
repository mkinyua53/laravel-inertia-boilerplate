@props(['align' => 'right', 'width' => '48', 'contentClasses' => 'tw-py-1 tw-bg-white'])

@php
switch ($align) {
    case 'left':
        $alignmentClasses = 'tw-origin-top-left tw-left-0';
        break;
    case 'top':
        $alignmentClasses = 'tw-origin-top';
        break;
    case 'right':
    default:
        $alignmentClasses = 'tw-origin-top-right tw-right-0';
        break;
}

switch ($width) {
    case '48':
        $width = 'tw-w-48';
        break;
}
@endphp

<div class="tw-relative" x-data="{ open: false }" @click.away="open = false" @close.stop="open = false">
    <div @click="open = ! open">
        {{ $trigger }}
    </div>

    <div x-show="open"
            x-transition:enter="tw-transition tw-ease-out tw-duration-200"
            x-transition:enter-start="tw-transform tw-opacity-0 tw-scale-95"
            x-transition:enter-end="tw-transform tw-opacity-100 tw-scale-100"
            x-transition:leave="tw-transition tw-ease-in tw-duration-75"
            x-transition:leave-start="tw-transform tw-opacity-100 tw-scale-100"
            x-transition:leave-end="tw-transform tw-opacity-0 tw-scale-95"
            class="tw-absolute tw-z-50 tw-mt-2 {{ $width }} tw-rounded-md tw-shadow-lg {{ $alignmentClasses }}"
            style="display: none;"
            @click="open = false">
        <div class="tw-rounded-md tw-shadow-xs {{ $contentClasses }}">
            {{ $content }}
        </div>
    </div>
</div>
