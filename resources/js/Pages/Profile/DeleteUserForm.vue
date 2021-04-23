<template>
    <span>
    <v-container fluid>
        <v-layout wrap>
            <v-flex xs12 sm4 md4>
                <v-icon :large="!$breakpoint.xsOnly" class="text-center">fa-trash-o</v-icon>
                <h4>Delete Account</h4>
                <p>Permanently delete your account.</p>
            </v-flex>
            <v-flex xs12 sm8 md8>
                <v-card>
                    <v-card-text>
                        <p>Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.</p>
                        <p class="red--text">Please note that you might not be able to register an account with the email ({{ user.email }})<span v-if="user.username">/username ({{ user.username }})</span> of this account.</p>

                        <v-btn small @click="confirmUserDeletion" class="red">
                            <v-icon left>fa-trash-o</v-icon>
                            Delete Account
                        </v-btn>

                        <modal-dialog :open="confirmingUserDeletion" @close="confirmingUserDeletion = false">
                            <template slot="title">Delete Account</template>
                            <v-card>
                                <v-card-text class="pa-1">
                                    <p>Are you sure you want to delete your account? Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.</p>

                                    <v-text-field type="password" placeholder="Password" v-model="form.password" required="required" :error="_error('password', 'deleteUser') && _error('password', 'deleteUser').length > 0" :error-messages="_error('password', 'deleteUser')" ref="password"></v-text-field>

                                    <v-btn large @click="deleteUser" :loading="form.processing" class="red" :disabled="!form.password">Delete Account</v-btn>
                                </v-card-text>
                            </v-card>
                        </modal-dialog>
                    </v-card-text>
                </v-card>
            </v-flex>
        </v-layout>
    </v-container>
    </span>
</template>

<script>

    export default {
        components: {
            //
        },

        props: [
            'user'
        ],

        data() {
            return {
                confirmingUserDeletion: false,
                deleting: false,

                form: this.$inertia.form({
                    '_method': 'DELETE',
                    password: '',
                },)
            }
        },

        methods: {
            confirmUserDeletion() {
                this.form.password = '';

                this.confirmingUserDeletion = true;

                setTimeout(() => {
                    console.log('confirmingUserDeletion')
                    this.$refs.password.focus()
                }, 250)
            },

            deleteUser() {
                this.form.post(route('current-user.destroy'), {
                    preserveScroll: true
                }).then(response => {
                    if (! this.form.hasErrors()) {
                        this.confirmingUserDeletion = false;
                    }
                })
            },
        },
    }
</script>
