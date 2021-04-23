<template>
    <jet-form-section @submitted="updatePassword">
        <template #title>
            Update Password
        </template>

        <template #description>
            Ensure your account is using a long, random password to stay secure.
        </template>

        <template #form>
            <div class="tw-col-span-6 sm:tw-col-span-4">
                <jet-label for="current_password" value="Current Password" />
                <jet-input id="current_password" type="password" class="tw-mt-1 tw-block tw-w-full" v-model="form.current_password" ref="current_password" autocomplete="current-password" />
                <jet-input-error :message="form.error('current_password')" class="tw-mt-2" />
            </div>

            <div class="tw-col-span-6 sm:tw-col-span-4">
                <jet-label for="password" value="New Password" />
                <jet-input id="password" type="password" class="tw-mt-1 tw-block tw-w-full" v-model="form.password" autocomplete="new-password" />
                <jet-input-error :message="form.error('password')" class="tw-mt-2" />
            </div>

            <div class="tw-col-span-6 sm:tw-col-span-4">
                <jet-label for="password_confirmation" value="Confirm Password" />
                <jet-input id="password_confirmation" type="password" class="tw-mt-1 tw-block tw-w-full" v-model="form.password_confirmation" autocomplete="new-password" />
                <jet-input-error :message="form.error('password_confirmation')" class="tw-mt-2" />
            </div>
        </template>

        <template #actions>
            <jet-action-message :on="form.recentlySuccessful" class="tw-mr-3">
                Saved.
            </jet-action-message>

            <jet-button :class="{ 'tw-opacity-25': form.processing }" :disabled="form.processing">
                Save
            </jet-button>
        </template>
    </jet-form-section>
</template>

<script>
    import JetActionMessage from '@/Jetstream/ActionMessage'
    import JetButton from '@/Jetstream/Button'
    import JetFormSection from '@/Jetstream/FormSection'
    import JetInput from '@/Jetstream/Input'
    import JetInputError from '@/Jetstream/InputError'
    import JetLabel from '@/Jetstream/Label'

    export default {
        components: {
            JetActionMessage,
            JetButton,
            JetFormSection,
            JetInput,
            JetInputError,
            JetLabel,
        },

        data() {
            return {
                form: this.$inertia.form({
                    current_password: '',
                    password: '',
                    password_confirmation: '',
                }, {
                    bag: 'updatePassword',
                }),
            }
        },

        methods: {
            updatePassword() {
                this.form.put(route('user-password.update'), {
                    preserveScroll: true
                }).then(() => {
                    // this.$refs.current_password.focus()
                })
            },
        },
    }
</script>
