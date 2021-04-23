<template>
    <modal :show="show" :max-width="maxWidth" :closeable="closeable" @close="close">
        <div class="tw-px-6 tw-py-4">
            <div class="tw-text-lg">
                <slot name="title">
                </slot>
            </div>

            <div class="tw-mt-4">
                <slot name="content">
                </slot>
            </div>
        </div>

        <div class="tw-px-6 tw-py-4 tw-bg-gray-100 tw-text-right">
            <slot name="footer">
            </slot>
        </div>
    </modal>
</template>

<script>
    import Modal from './Modal'

    export default {
        components: {
            Modal,
        },

        props: {
            show: {
                default: false
            },
            maxWidth: {
                default: '2xl'
            },
            closeable: {
                default: true
            },
        },

        methods: {
            close() {
                this.$emit('close')
            },
        }
    }
</script>
