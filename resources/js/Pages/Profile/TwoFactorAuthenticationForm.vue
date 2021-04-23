<template>
    <v-container fluid>
        <v-layout wrap class="align-center">
            <v-flex xs12 md4 sm4>
                <v-icon large>mdi-two-factor-authentication</v-icon>
                <h3>Two Factor Authentication</h3>
                <p>Add additional security to your account using two factor authentication.</p>
            </v-flex>
            <v-flex xs12 md8 sm8>
                <v-card>
                    <v-card-text>
                        <h3 class="text-lg font-medium text-gray-900" v-if="twoFactorEnabled">
                            You have enabled two factor authentication.
                        </h3>

                        <h3 class="text-lg font-medium text-gray-900" v-else>
                            You have not enabled two factor authentication.
                        </h3>

                        <div class="mt-3 max-w-xl text-sm text-gray-600">
                            <p>
                                When two factor authentication is enabled, you will be prompted for a secure, random token during authentication. You may retrieve this token from your phone's Google Authenticator application.
                            </p>
                        </div>

                        <div v-if="twoFactorEnabled">
                            <div v-if="qrCode">
                                <div class="mt-4 max-w-xl text-sm text-gray-600">
                                    <p class="font-semibold">
                                        Two factor authentication is now enabled. Scan the following QR code using your phone's authenticator application.
                                    </p>
                                </div>
                                <v-card light flat class="pa-1 d-inline-block">
                                    <v-card-text>
                                        <div class="mt-4 dark:p-4 dark:w-56 dark:bg-white" v-html="qrCode"></div>
                                    </v-card-text>
                                </v-card>
                            </div>

                            <div v-if="recoveryCodes.length > 0">
                                <div class="mt-4 max-w-xl text-sm text-gray-600">
                                    <p class="font-semibold">
                                        Store these recovery codes in a secure password manager. They can be used to recover access to your account if your two factor authentication device is lost.
                                    </p>
                                </div>

                                <div class="grid gap-1 max-w-xl mt-4 px-4 py-4 font-mono text-sm bg-gray-100 rounded-lg">
                                    <div v-for="code in recoveryCodes" :key="code">
                                        {{ code }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-5">
                            <div v-if="! twoFactorEnabled">
                                <mk-confirms-password @confirmed="enableTwoFactorAuthentication">
                                    <jet-button type="button" :class="{ 'tw-opacity-25': enabling }" :disabled="enabling">
                                        Enable
                                    </jet-button>
                                </mk-confirms-password>
                            </div>

                            <div v-else>
                                <mk-confirms-password @confirmed="regenerateRecoveryCodes">
                                    <v-btn small text class="mr-3"
                                                    v-if="recoveryCodes.length > 0">
                                        Regenerate Recovery Codes
                                    </v-btn>
                                </mk-confirms-password>

                                <mk-confirms-password @confirmed="showRecoveryCodes">
                                    <v-btn small text class="mr-3" v-if="recoveryCodes.length == 0">
                                        Show Recovery Codes
                                    </v-btn>
                                </mk-confirms-password>

                                <mk-confirms-password @confirmed="disableTwoFactorAuthentication">
                                    <jet-danger-button
                                                    :class="{ 'opacity-25': disabling }"
                                                    :disabled="disabling">
                                        Disable
                                    </jet-danger-button>
                                </mk-confirms-password>
                            </div>
                        </div>
                    </v-card-text>
                </v-card>
            </v-flex>
        </v-layout>
    </v-container>
</template>

<script>
    import JetActionSection from '@/Jetstream/ActionSection'
    import JetButton from '@/Jetstream/Button'
    import JetConfirmsPassword from '@/Jetstream/ConfirmsPassword'
    import JetDangerButton from '@/Jetstream/DangerButton'
    import JetSecondaryButton from '@/Jetstream/SecondaryButton'
    import MkConfirmsPassword from '@/Components/components/ConfirmsPassword.vue'

    export default {
        components: {
            JetActionSection,
            JetButton,
            JetConfirmsPassword,
            JetDangerButton,
            JetSecondaryButton,
            MkConfirmsPassword,
        },

        data() {
            return {
                enabling: false,
                disabling: false,

                qrCode: null,
                recoveryCodes: [],
            }
        },

        methods: {
            enableTwoFactorAuthentication() {
                this.enabling = true

                this.$inertia.post('/user/two-factor-authentication', {}, {
                    preserveScroll: true,
                }).then(() => {
                    return Promise.all([
                        this.showQrCode(),
                        this.showRecoveryCodes()
                    ])
                }).then(() => {
                    this.enabling = false
                })
            },

            showQrCode() {
                return axios.get('/user/two-factor-qr-code')
                        .then(response => {
                            this.qrCode = response.data.svg
                        })
            },

            showRecoveryCodes() {
                return axios.get('/user/two-factor-recovery-codes')
                        .then(response => {
                            this.recoveryCodes = response.data
                        })
            },

            regenerateRecoveryCodes() {
                axios.post('/user/two-factor-recovery-codes')
                        .then(response => {
                            this.showRecoveryCodes()
                        })
            },

            disableTwoFactorAuthentication() {
                this.disabling = true

                this.$inertia.delete('/user/two-factor-authentication', {
                    preserveScroll: true,
                }).then(() => {
                    this.disabling = false
                })
            },
        },

        computed: {
            twoFactorEnabled() {
                return ! this.enabling && this.$page.props.user.two_factor_enabled
            }
        }
    }
</script>
