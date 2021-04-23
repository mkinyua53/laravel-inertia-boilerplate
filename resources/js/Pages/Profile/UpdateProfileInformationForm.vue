<template>
	<div>
		<v-container fluid>
			<v-layout wrap class="align-center">
				<v-flex xs12 md4 sm4>
					<h3>Profile Information</h3>
					<p>Update your account's profile information and email address.</p>
				</v-flex>
				<v-flex xs12 md8 sm8>
					<v-card class="ma-1">
						<v-card-text>
							<form @submit.prevent="updateProfileInformation">
								<div class="tw-col-span-6 sm:tw-col-span-4 pb-2" v-if="$page.props.jetstream.managesProfilePhotos">
									<!-- Profile Photo File Input -->
									<input type="file" class="tw-hidden" ref="photo" @change="updatePhotoPreview">
									<label for="photo" value="Photo" />

									<!-- Current Profile Photo -->
									<div class="tw-mt-2" v-show="! photoPreview">
										<img :src="user.profile_photo_url" alt="Current Profile Photo" class="tw-rounded-full tw-h-20 tw-w-20 tw-object-cover">
									</div>

									<!-- New Profile Photo Preview -->
									<div class="tw-mt-2" v-show="photoPreview">
										<span class="tw-block tw-rounded-full tw-w-20 tw-h-20" :style="'background-size: cover; background-repeat: no-repeat; background-position: center center; background-image: url(\'' + photoPreview + '\');'"></span>
									</div>

									<v-btn class="tw-mt-2 tw-mr-2" small @click.native.prevent="selectNewPhoto">
										Select A New Photo
									</v-btn>

									<v-btn class="tw-mt-2" small @click.native.prevent="deletePhoto" v-if="user.profile_photo_path">
										<v-icon color="red" left>fa-trash-o</v-icon>
										Remove Photo
									</v-btn>

									<!-- <jet-input-error :message="form.error('photo')" class="tw-mt-2" /> -->
								</div>
								<v-layout wrap>
									<v-flex xs3 class="pr-1">
										<v-text-field label="Title" v-model="form.title" autocomplete="title" single-line dense :error-messages="_error('title', 'updateProfileInformation')" list="titles"></v-text-field>
										<datalist id="titles">
											<option value="M/s."/>
											<option value="Mr."/>
											<option value="Dr."/>
											<option value="Prof."/>
											<option value="Eng."/>
										</datalist>
									</v-flex>
									<v-flex xs9>
										<v-text-field label="Name" v-model="form.name" autocomplete="name" single-line dense :error-messages="_error('name', 'updateProfileInformation')"></v-text-field>
									</v-flex>
									<v-flex xs12 md6 sm6>
										<v-text-field prepend-inner-icon="fa-envelope" label="Email" v-model="form.email" required="required" type="email" single-line dense :error-messages="_error('email', 'updateProfileInformation')"></v-text-field>
									</v-flex>
									<v-flex xs12 md6 sm6 class="pl-1">
										<v-text-field label="Username" prepend-inner-icon="mdi-account-key" v-model="form.username" single-line dense :error-messages="_error('username', 'updateProfileInformation')"></v-text-field>
									</v-flex>
									<v-flex xs6 class="pr-1">
										<v-text-field label="Phone Number" name="Phone" prepend-inner-icon="fa-phone" autocomplete="phone" v-model="form.phone" required="required" single-line dense :error-messages="_error('phone', 'updateProfileInformation')"></v-text-field>
									</v-flex>
									<v-flex xs6 class="pr-1">
										<v-text-field label="Place of Residence" name="Residence" hint="Where do you stay (be as specific as possible)" autocomplete="residence" prepend-inner-icon="fa-home" v-model="form.residence" required="required" single-line dense :error-messages="_error('residence', 'updateProfileInformation')"></v-text-field>
									</v-flex>
									<v-flex xs6 class="pr-1">
										<v-text-field label="Date of Birth" name="Date of Birth" type="date" v-model="form.date_of_birth" prepend-inner-icon="fa-birthday-cake" single-line dense :error-messages="_error('date_of_birth', 'updateProfileInformation')"></v-text-field>
									</v-flex>
									<v-flex xs12>
										<v-btn class="pull-right secondary" small type="submit" :loading="loading">Update</v-btn>
									</v-flex>
								</v-layout>

							</form>
						</v-card-text>
					</v-card>
				</v-flex>
			</v-layout>
		</v-container>
	</div>
</template>

<script>
    import JetButton from '@/Jetstream/Button'
    import JetFormSection from '@/Jetstream/FormSection'
    import JetInput from '@/Jetstream/Input'
    import JetInputError from '@/Jetstream/InputError'
    import JetLabel from '@/Jetstream/Label'
    import JetActionMessage from '@/Jetstream/ActionMessage'
    import JetSecondaryButton from '@/Jetstream/SecondaryButton'

    export default {
        components: {
            JetActionMessage,
            JetButton,
            JetFormSection,
            JetInput,
            JetInputError,
            JetLabel,
            JetSecondaryButton,
        },

        props: ['user'],

        data() {
            return {
                form: this.$inertia.form({
                    '_method': 'PUT',
                    name: this.user.name,
                    email: this.user.email,
										photo: null,
										title: this.user.title,
										phone: this.user.phone,
										residence: this.user.residence,
										date_of_birth: this.user.date_of_birth,
										username: this.user.username
                }, {
                    bag: 'updateProfileInformation'
                }),

								photoPreview: null,
								loading: false
            }
        },

        methods: {
            updateProfileInformation() {
							this.loading = true
                if (this.$refs.photo) {
                    this.form.photo = this.$refs.photo.files[0]
                }

                this.form.post(route('user-profile-information.update'), {
										preserveScroll: true,
										onSuccess: () => {
											this.loading = false
										}
                });
            },

            selectNewPhoto() {
                this.$refs.photo.click();
            },

            updatePhotoPreview() {
                const reader = new FileReader();

                reader.onload = (e) => {
                    this.photoPreview = e.target.result;
                };

                reader.readAsDataURL(this.$refs.photo.files[0]);
            },

            deletePhoto() {
                this.$inertia.delete(route('current-user-photo.destroy'), {
                    preserveScroll: true,
                }).then(() => {
                    this.photoPreview = null
                });
            },
        },
    }
</script>
