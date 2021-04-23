<template>
    <div>
        <transition leave-active-class="tw-transition tw-ease-in tw-duration-1000" leave-class="tw-opacity-100" leave-to-class="tw-opacity-0">
            <div v-show="on" class="tw-text-sm tw-text-gray-600">
                <slot />
            </div>
        </transition>
    </div>
</template>

<script>
    export default {
        props: ['on'],
    }
</script>
