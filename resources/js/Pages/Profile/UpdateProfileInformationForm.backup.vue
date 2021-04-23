<template>
	<div>
		<v-container fluid>
			<v-layout wrap>
				<v-flex xs12 md4 sm4>
					<h3>Profile Information</h3>
					<p>Update your account's profile information and email address.</p>
				</v-flex>
				<v-flex xs12 md8 sm8>
					<div class="tw-col-span-6 sm:tw-col-span-4" v-if="$page.props.jetstream.managesProfilePhotos">
                <!-- Profile Photo File Input -->
                <input type="file" class="tw-hidden"
                            ref="photo"
                            @change="updatePhotoPreview">

                <jet-label for="photo" value="Photo" />

                <!-- Current Profile Photo -->
                <div class="tw-mt-2" v-show="! photoPreview">
                    <img :src="user.profile_photo_url" alt="Current Profile Photo" class="tw-rounded-full tw-h-20 tw-w-20 tw-object-cover">
                </div>

                <!-- New Profile Photo Preview -->
                <div class="tw-mt-2" v-show="photoPreview">
                    <span class="tw-block tw-rounded-full tw-w-20 tw-h-20"
                          :style="'background-size: cover; background-repeat: no-repeat; background-position: center center; background-image: url(\'' + photoPreview + '\');'">
                    </span>
                </div>

                <jet-secondary-button class="tw-mt-2 tw-mr-2" type="button" @click.native.prevent="selectNewPhoto">
                    Select A New Photo
                </jet-secondary-button>

                <jet-secondary-button type="button" class="tw-mt-2" @click.native.prevent="deletePhoto" v-if="user.profile_photo_path">
                    Remove Photo
                </jet-secondary-button>
                <span>{{ _error('photo', 'updateProfileInformation') }}</span>
                <jet-input-error :message="form.error('photo')" class="tw-mt-2" />
            </div>
				</v-flex>
			</v-layout>
		</v-container>
    <jet-form-section @submitted="updateProfileInformation">
        <template #form>
            <!-- Profile Photo -->


            <!-- Name -->
            <div class="tw-col-span-6 sm:tw-col-span-4">
                <jet-label for="name" value="Name" />
                <jet-input id="name" type="text" class="tw-mt-1 tw-block tw-w-full" v-model="form.name" autocomplete="name" />
                <jet-input-error :message="form.error('name')" class="tw-mt-2" />
            </div>

            <!-- Email -->
            <div class="tw-col-span-6 sm:tw-col-span-4">
                <jet-label for="email" value="Email" />
                <jet-input id="email" type="email" class="tw-mt-1 tw-block tw-w-full" v-model="form.email" />
                <jet-input-error :message="form.error('email')" class="tw-mt-2" />
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

        props: ['user', 'errors'],

        data() {
            return {
                form: this.$inertia.form({
                    '_method': 'PUT',
                    name: this.user.name,
                    email: this.user.email,
                    photo: null,
                }, {
                    bag: 'updateProfileInformation',
                    resetOnSuccess: false,
                }),

                photoPreview: null,
            }
        },

        methods: {
            updateProfileInformation() {
                if (this.$refs.photo) {
                    this.form.photo = this.$refs.photo.files[0]
                }

                this.form.post(route('user-profile-information.update'), {
                    preserveScroll: true
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
