<template>
    <app-layout>
        <template #header>
            <h2 class="tw-font-semibold tw-text-xl tw-text-gray-800 tw-leading-tight">
                Profile
            </h2>
        </template>

        <div>
            <div class="tw-max-w-7xl tw-mx-auto sm:tw-px-6 lg:tw-px-8">
                <update-profile-information-form :user="$page.props.user" />

                <!-- <jet-section-border /> -->

                <update-password-form class="tw-mt-10 sm:tw-mt-0" />

                <div v-if="$page.props.jetstream.canManageTwoFactorAuthentication">
                    <!-- <jet-section-border /> -->

                    <two-factor-authentication-form class="tw-mt-10 sm:tw-mt-0" />
                </div>

                <!-- <jet-section-border /> -->

                <logout-other-browser-sessions-form :sessions="sessions" class="tw-mt-10 sm:tw-mt-0" />

                <!-- <jet-section-border /> -->

                <delete-user-form class="tw-mt-10 sm:tw-mt-0" :user="user" />
            </div>
        </div>
    </app-layout>
</template>

<script>
    import AppLayout from '@/Layouts/User'
    import DeleteUserForm from './DeleteUserForm'
    import JetSectionBorder from '@/Jetstream/SectionBorder'
    import LogoutOtherBrowserSessionsForm from './LogoutOtherBrowserSessionsForm'
    import TwoFactorAuthenticationForm from './TwoFactorAuthenticationForm'
    import UpdatePasswordForm from './MkUpdatePasswordForm'
    import UpdateProfileInformationForm from './UpdateProfileInformationForm'

    export default {
        props: ['sessions', 'user'],

        components: {
            AppLayout,
            DeleteUserForm,
            JetSectionBorder,
            LogoutOtherBrowserSessionsForm,
            TwoFactorAuthenticationForm,
            UpdatePasswordForm,
            UpdateProfileInformationForm,
        },

        metaInfo () {
            return {
                title: 'User Profile',
                titleTemplate: '%s | ' + this.$title
            }
        },
    }
</script>
