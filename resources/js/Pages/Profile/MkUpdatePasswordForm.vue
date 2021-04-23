<template>
    <v-container fluid>
        <v-layout wrap class="align-center">
            <v-flex xs12 sm4 md4>
                <v-icon :large="! $breakpoint.xsOnly" class="text-center">fa-key</v-icon>
                <h4>Update Password</h4>
                <p>Ensure your account is using a long, random password to stay secure.</p>
            </v-flex>
            <v-flex xs12 sm8 md8>
                <v-card class="ma-1">
                    <v-card-text>
                        <form @submit.prevent="updatePassword">
                            <v-text-field dense single-line label="Current Password" type="password" v-model="form.current_password" ref="current_password" autocomplete="current-password" required :error="_error('current_password', 'updatePassword') && _error('current_password', 'updatePassword').length > 0" :error-messages="_error('current_password', 'updatePassword')"></v-text-field>

                            <v-text-field dense single-line label="New Password" type="password" v-model="form.password" ref="password" autocomplete="new-password" minLength="8" required :error="_error('password', 'updatePassword') && _error('password', 'updatePassword').length > 0" :error-messages="_error('password', 'updatePassword')"></v-text-field>

                            <v-text-field dense single-line label="Confirm Password" type="password" v-model="form.password_confirmation" required autocomplete="new-password"></v-text-field>

                            <span v-if="form.recentlySuccessful">Saved</span>
                            <v-layout wrap class="justify-end">
                                <v-flex xs12>
                                    <v-btn small class="secondary pull-right" type="submit" :loading="form.processing">Change</v-btn>
                                </v-flex>
                            </v-layout>
                        </form>
                    </v-card-text>
                </v-card>
            </v-flex>
        </v-layout>
    </v-container>
</template>

<script>
    export default {
        components: {
            // JetInputError,
        },
        props: [
            'errors'
        ],
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
