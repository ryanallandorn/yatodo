<script>
    // resources/js/Pages/Profile/Show.svelte

    import { page } from "@inertiajs/svelte"; // No need for inertia and Link here
    import App from "@layouts/App.svelte";

    import UpdateProfileInformationForm from '@pages/Profile/Partials/UpdateProfileInformationForm.svelte';
    import UpdatePasswordForm from '@pages/Profile/Partials/UpdatePasswordForm.svelte';
    import TwoFactorAuthenticationForm from '@pages/Profile/Partials/TwoFactorAuthenticationForm.svelte';
    import LogoutOtherBrowserSessionsForm from '@pages/Profile/Partials/LogoutOtherBrowserSessionsForm.svelte';
    import SectionBorder from '@components/SectionBorder.svelte';

    // Reactive user property
    $: user = $page.props.auth?.user;

    // Exporting props for reuse
    export let confirmsTwoFactorAuthentication = false; // Default to false if not passed
    export let sessions = []; // Default to an empty array
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
    </div>

    <!-- Main content -->
    <div class="page-content container py-5">
        
        {#if $page.props.jetstream.canUpdateProfileInformation}
            <UpdateProfileInformationForm user={user} />
            <SectionBorder />
        {/if}

        {#if $page.props.jetstream.canUpdatePassword}
            <UpdatePasswordForm user={user} />
            <SectionBorder />
        {/if}

        {#if $page.props.jetstream.canManageTwoFactorAuthentication}
            <TwoFactorAuthenticationForm 
                requiresConfirmation={confirmsTwoFactorAuthentication} 
                user={user} 
            />
            <SectionBorder />
        {/if}

        <LogoutOtherBrowserSessionsForm sessions="{sessions}" class="mt-10 sm:mt-0" />
        
        <!-- Optional deletion section -->
        {#if $page.props.jetstream.hasAccountDeletionFeatures}
            <SectionBorder />
            <!-- Your account deletion form component here -->
        {/if}
    </div>

</App>
