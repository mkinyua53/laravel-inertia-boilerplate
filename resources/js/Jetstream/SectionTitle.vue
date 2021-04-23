<template>
    <div class="md:tw-col-span-1">
        <div class="tw-px-4 sm:tw-px-0">
            <h3 class="tw-text-lg tw-font-medium tw-text-gray-900">
                <slot name="title"></slot>
            </h3>

            <p class="tw-mt-1 tw-text-sm tw-text-gray-600">
                <slot name="description"></slot>
            </p>
        </div>
    </div>
</template>
