<template>
    <portal to="modal">
        <transition leave-active-class="tw-duration-200">
            <div v-show="show" class="tw-fixed tw-top-0 tw-inset-x-0 tw-px-4 tw-pt-6 sm:tw-px-0 sm:tw-flex sm:tw-items-top sm:tw-justify-center">
                <transition enter-active-class="tw-ease-out tw-duration-300"
                        enter-class="tw-opacity-0"
                        enter-to-class="tw-opacity-100"
                        leave-active-class="tw-ease-in tw-duration-200"
                        leave-class="tw-opacity-100"
                        leave-to-class="tw-opacity-0">
                    <div v-show="show" class="tw-fixed tw-inset-0 tw-transform tw-transition-all" @click="close">
                        <div class="tw-absolute tw-inset-0 tw-bg-gray-500 tw-opacity-75"></div>
                    </div>
                </transition>

                <transition enter-active-class="tw-ease-out tw-duration-300"
                        enter-class="tw-opacity-0 tw-translate-y-4 sm:tw-translate-y-0 sm:tw-scale-95"
                        enter-to-class="tw-opacity-100 tw-translate-y-0 sm:tw-scale-100"
                        leave-active-class="tw-ease-in tw-duration-200"
                        leave-class="tw-opacity-100 tw-translate-y-0 sm:tw-scale-100"
                        leave-to-class="tw-opacity-0 tw-translate-y-4 sm:tw-translate-y-0 sm:tw-scale-95">
                    <div v-show="show" class="tw-bg-white tw-rounded-lg tw-overflow-hidden tw-shadow-xl tw-transform tw-transition-all sm:tw-w-full" :class="maxWidthClass">
                        <slot></slot>
                    </div>
                </transition>
            </div>
        </transition>
    </portal>
</template>

<script>
    export default {
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
                if (this.closeable) {
                    this.$emit('close')
                }
            }
        },

        watch: {
            show: {
                immediate: true,
                handler: (show) => {
                    if (show) {
                        document.body.style.overflow = 'hidden'
                    } else {
                        document.body.style.overflow = null
                    }
                }
            }
        },

        created() {
            const closeOnEscape = (e) => {
                if (e.key === 'Escape' && this.show) {
                    this.close()
                }
            }

            document.addEventListener('keydown', closeOnEscape)

            this.$once('hook:destroyed', () => {
                document.removeEventListener('keydown', closeOnEscape)
            })
        },

        computed: {
            maxWidthClass() {
                return {
                    'sm': 'sm:tw-max-w-sm',
                    'md': 'sm:tw-max-w-md',
                    'lg': 'sm:tw-max-w-lg',
                    'xl': 'sm:tw-max-w-xl',
                    '2xl': 'sm:tw-max-w-2xl',
                }[this.maxWidth]
            }
        }
    }
</script>
