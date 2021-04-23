<template>
    <button :type="type" class="inline-flex tw-items-center tw-px-4 tw-py-2 tw-bg-white tw-border tw-border-gray-300 tw-rounded-md tw-font-semibold tw-text-xs tw-text-gray-700 tw-uppercase tw-tracking-widest tw-shadow-sm hover:tw-text-gray-500 focus:tw-outline-none focus:tw-border-blue-300 focus:tw-shadow-outline-blue active:tw-text-gray-800 active:tw-bg-gray-50 tw-transition tw-ease-in-out tw-duration-150">
        <slot></slot>
    </button>
</template>

<script>
    export default {
        props: {
            type: {
                type: String,
                default: 'button',
            },
        }
    }
</script>
