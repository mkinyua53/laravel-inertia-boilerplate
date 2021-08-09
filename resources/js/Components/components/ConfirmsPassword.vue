<template>
    <span class="pa-1 ma-1">
        <span @click="startConfirmingPassword">
            <slot />
        </span>

        <modal-dialog v-model="confirmingPassword">
          <template slot="title">{{ title }}</template>
           <v-card class="ma-1">
             <v-card-text>
               <p v-html="content"></p>
               <v-text-field type="password" placeholder="Password" ref="password" v-model="form.password" @keyup.enter.native="confirmPassword" :error-messages="form.error"></v-text-field>
              <v-btn small @click="confirmPassword" class="primary pull-right" :loading="form.processing">{{ button }}</v-btn><br>
             </v-card-text>
           </v-card>
        </modal-dialog>
    </span>
</template>

<script>

    export default {
        props: {
            title: {
                default: 'Confirm Password',
            },
            content: {
                default: 'For your security, please confirm your password to continue.',
            },
            button: {
                default: 'Confirm',
            }
        },

        components: {
            //
        },

        data() {
            return {
                confirmingPassword: false,

                form: this.$inertia.form({
                    password: '',
                    error: '',
                }, {
                    bag: 'confirmPassword',
                })
            }
        },

        methods: {
            startConfirmingPassword() {
                this.form.error = '';

                axios.get(route('password.confirmation').url()).then(response => {
                    if (response.data.confirmed) {
                        this.$emit('confirmed');
                    } else {
                        this.confirmingPassword = true;
                        this.form.password = '';

                        setTimeout(() => {
                            this.$refs.password.focus()
                        }, 250)
                    }
                })
            },

            confirmPassword() {
                this.form.processing = true;

                axios.post(route('password.confirm').url(), {
                    password: this.form.password,
                }).then(response => {
                    this.confirmingPassword = false;
                    this.form.password = '';
                    this.form.error = '';
                    this.form.processing = false;

                    this.$nextTick(() => this.$emit('confirmed'));
                }).catch(error => {
                    this.form.processing = false;
                    this.form.error = error.response.data.errors.password[0];
                });
            }
        }
    }
</script>
