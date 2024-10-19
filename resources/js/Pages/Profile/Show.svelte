<script>

// resources/js/Pages/Profile/Show.svelte

    import { page, inertia, Link } from "@inertiajs/svelte";
    import App from "@layouts/App.svelte";

    import UpdateProfileInformationForm from '@pages/Profile/Partials/UpdateProfileInformationForm.svelte';
    import UpdatePasswordForm from '@pages/Profile/Partials/UpdatePasswordForm.svelte';
    import TwoFactorAuthenticationForm from '@pages/Profile/Partials/TwoFactorAuthenticationForm.svelte';
    import SectionBorder from '@components/SectionBorder.svelte'



</script>

<svelte:head>
    <title>Profile</title>
</svelte:head>

<App>

    <!-- This content will be passed to the named "hero" slot -->
    <div slot="hero" class="hero-contents">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            <i class="bi bi-person-vcard me-2"></i> Profile
        </h2>
        <!-- <p>Additional content here, like a description or other elements.</p> -->
    </div>

    <!-- Main content -->
    <div class="page-content container py-5">

        {#if $page.props.jetstream.canUpdateProfileInformation}
        <UpdateProfileInformationForm :user="$page.props.auth.user" />
        <SectionBorder />
        {/if}

        {#if $page.props.jetstream.canUpdatePassword}
        <UpdatePasswordForm :user="$page.props.auth.user" />
        <SectionBorder />
        {/if}

        {#if $page.props.jetstream.canManageTwoFactorAuthentication}
        <TwoFactorAuthenticationForm :user="$page.props.auth.user" />
        <SectionBorder />
        {/if}
        <!-- {#if $page.jetstream.hasAccountDeletionFeatures}
        {/if} -->
    </div>


</App>