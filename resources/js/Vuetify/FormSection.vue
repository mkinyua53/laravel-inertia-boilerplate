<template>
    <div class="md:tw-grid md:tw-grid-cols-3 md:tw-gap-6">
        <jet-section-title>
            <template #title><slot name="title"></slot></template>
            <template #description><slot name="description"></slot></template>
        </jet-section-title>

        <div class="mt-5 md:tw-mt-0 md:tw-col-span-2">
            <form @submit.prevent="$emit('submitted')">
                <div class="tw-shadow tw-overflow-hidden sm:tw-rounded-md">
                    <div class="tw-px-4 tw-py-5 tw-bg-white sm:tw-p-6">
                        <div class="tw-grid tw-grid-cols-6 tw-gap-6">
                            <slot name="form"></slot>
                        </div>
                    </div>

                    <div class="tw-flex tw-items-center tw-justify-end tw-px-4 tw-py-3 tw-bg-gray-50 tw-text-right sm:tw-px-6" v-if="hasActions">
                        <slot name="actions"></slot>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
    import JetSectionTitle from './SectionTitle'

    export default {
        components: {
            JetSectionTitle,
        },

        computed: {
            hasActions() {
                return !! this.$slots.actions
            }
        }
    }
</script>
