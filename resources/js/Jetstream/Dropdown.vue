<template>
    <div class="relative">
        <div @click="open = ! open">
            <slot name="trigger"></slot>
        </div>

        <!-- Full Screen Dropdown Overlay -->
        <div v-show="open" class="tw-fixed tw-inset-0 tw-z-40" @click="open = false">
        </div>

        <transition
            enter-active-class="tw-transition tw-ease-out tw-duration-200"
            enter-class="tw-transform tw-opacity-0 tw-scale-95"
            enter-to-class="tw-transform tw-opacity-100 tw-scale-100"
            leave-active-class="tw-transition tw-ease-in tw-duration-75"
            leave-class="tw-transform tw-opacity-100 tw-scale-100"
            leave-to-class="tw-transform tw-opacity-0 tw-scale-95">
            <div v-show="open"
                    class="tw-absolute tw-z-50 tw-mt-2 tw-rounded-md tw-shadow-lg"
                    :class="[widthClass, alignmentClasses]"
                    style="display: none;"
                    @click="open = false">
                <div class="tw-rounded-md tw-shadow-xs" :class="contentClasses">
                    <slot name="content"></slot>
                </div>
            </div>
        </transition>
    </div>
</template>

<script>
    export default {
        props: {
            align: {
                default: 'right'
            },
            width: {
                default: '48'
            },
            contentClasses: {
                default: () => ['tw-py-1', 'tw-bg-white']
            }
        },

        data() {
            return {
                open: false
            }
        },

        created() {
            const closeOnEscape = (e) => {
                if (this.open && e.keyCode === 27) {
                    this.open = false
                }
            }

            this.$once('hook:destroyed', () => {
                document.removeEventListener('keydown', closeOnEscape)
            })

            document.addEventListener('keydown', closeOnEscape)
        },

        computed: {
            widthClass() {
                return {
                    '48': 'tw-w-48',
                }[this.width.toString()]
            },

            alignmentClasses() {
                if (this.align == 'left') {
                    return 'tw-origin-top-left tw-left-0'
                } else if (this.align == 'right') {
                    return 'tw-origin-top-right tw-right-0'
                } else {
                    return 'tw-origin-top'
                }
            },
        }
    }
</script>
