<template>
	<v-container fluid>
		<v-layout wrap class="align-center">
			<v-flex xs12 sm4 md4>
				<h3>Browser Sessions</h3>
				<p>Manage and logout your active sessions on other browsers and devices.</p>
			</v-flex>
			<v-flex xs12 md8 sm8>
				<v-card>
					<v-card-text>
						<div class="tw-max-w-xl tw-text-sm">
              If necessary, you may logout of all of your other browser sessions across all of your devices. Some of your recent sessions are listed below; however, this list may not be exhaustive. If you feel your account has been compromised, you should also update your password.
            </div>
						<!-- Other Browser Sessions -->
            <div class="tw-mt-5 tw-space-y-6" v-if="$page.props.sessions.length > 0">
							<div class="tw-flex tw-items-center" v-for="(session, i) in $page.props.sessions" :key="i">
								<div>
									<svg fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor" class="tw-w-8 h-8" v-if="session.agent.is_desktop">
										<path d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
									</svg>

									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round" class="tw-w-8 h-8" v-else>
										<path d="M0 0h24v24H0z" stroke="none"></path><rect x="7" y="4" width="10" height="16" rx="1"></rect><path d="M11 5h2M12 17v.01"></path>
									</svg>
								</div>

								<div class="tw-ml-3">
									<div class="tw-text-sm">
										{{ session.agent.platform }} - {{ session.agent.browser }}
									</div>

									<div>
										<div class="tw-text-xs">
											{{ session.ip_address }},

											<span class="tw-text-green-500 tw-font-semibold" v-if="session.is_current_device">This device</span>
											<span v-else>Last active {{ session.last_active }}</span>
										</div>
									</div>
								</div>
              </div>
            </div>
						<div class="my-4">
							<p class="text-muted red--text mb-0" v-if="_error('password', 'logoutOtherBrowserSessions')">{{ _error('password', 'logoutOtherBrowserSessions') }}</p>
							<v-btn dark @click.native="confirmLogout" :loading="form.processing">Logout Other Browser Sessions</v-btn>

							<jet-action-message :on="form.recentlySuccessful" class="tw-ml-3">
								Done.
							</jet-action-message>
            </div>
						<!-- Logout Other Devices Confirmation Modal -->
						<modal-dialog v-model="confirmingLogout">
							<template #title>Logout Other Browser Sessions</template>
							<p v-html="'Please enter your password to confirm you would like to logout of your other browser sessions across all of your devices.'"></p>
							<v-text-field type="password" placeholder="Password" prepend-inner-icon="mdi-account-key" ref="password" v-model="form.password" @keyup.enter.native="logoutOtherBrowserSessions()" :error-messages="_error('password', 'logoutOtherBrowserSessions')" required></v-text-field>
							<v-btn small @click="logoutOtherBrowserSessions" class="primary" :loading="form.processing">{{ 'Confirm' }}</v-btn>
						</modal-dialog>
					</v-card-text>
				</v-card>
			</v-flex>
		</v-layout>
	</v-container>
</template>

<script>
    import JetActionMessage from '@/Jetstream/ActionMessage'
    import JetActionSection from '@/Jetstream/ActionSection'
    import JetButton from '@/Jetstream/Button'
    import JetDialogModal from '@/Jetstream/DialogModal'
    import JetInput from '@/Jetstream/Input'
    import JetInputError from '@/Jetstream/InputError'
    import JetSecondaryButton from '@/Jetstream/SecondaryButton'
    import MkConfirmsPassword from '@/Components/components/ConfirmsPassword.vue'

    export default {
        props: ['sessions'],

        components: {
            JetActionMessage,
            JetActionSection,
            JetButton,
            JetDialogModal,
            JetInput,
            JetInputError,
            JetSecondaryButton,
            MkConfirmsPassword,
        },

        data() {
            return {
                confirmingLogout: false,

                form: this.$inertia.form({
                    '_method': 'DELETE',
                    password: '',
                }, {
                    bag: 'logoutOtherBrowserSessions'
                })
            }
        },

        methods: {
            confirmLogout() {
                this.form.password = ''

                this.confirmingLogout = true

                setTimeout(() => {
                    this.$refs.password.focus()
                }, 250)
            },

            logoutOtherBrowserSessions() {
							this.confirmingLogout = false
                this.form.post(route('other-browser-sessions.destroy'), {
                  preserveScroll: true,
									onSuccess: () => {
										this.form.reset('password')
									}
                })
            },
        },
    }
</script>
