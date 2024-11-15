<!-- src/routes/layout/Header.svelte -->

<script>
    import { onMount } from 'svelte';
    import { page, inertia, Link } from "@inertiajs/svelte";
    import { t } from 'svelte-i18n';
    // import { logout } from '$lib/stores/auth'; // This should handle the logout API call
    import LogoFull from '@components/Branding/LogoFull.svelte';
    import LogoIcon from '@components/Branding/LogoIcon.svelte';
    import ColorSwitcherAppbarNav from '@components/UI/States/ColorSwitcher/AppbarNav.svelte';
    import AppbarUserNav from '@components/Structure/Appbar/UserNav.svelte';
    import AppbarCommentNav from '@components/Structure/Appbar/CommentNav.svelte';

    // import Timer from '$lib/components/Timer.svelte'; // Your timer component
    // import DropdownMega from '$components/DropdownMega.svelte'; // Dropdown Mega component
    // import DropdownLink from '$components/DropdownLink.svelte'; // Link component for dropdown
    // import UserAvatar from '$components/UserAvatar.svelte'; // Your user avatar component
    //import { user, teams } from '$lib/stores/auth'; // Assuming auth store manages user & team data

    let currentTeam = null;

    const teams = {};

    import { route } from 'ziggy-js';

    onMount(() => {
        //currentTeam = user.currentTeam; // Fetch current team on mount if Jetstream-like feature is used
    });

    function handleLogout() {
        logout(); // Call the logout function from your store or API
    }

    // Add a timestamp when the component loads
    const loadTime = new Date().toLocaleTimeString();



</script>

<header id="header" class="fixed-top border-bottom bg-body-tertiary">
    <div class="container-fluid d-flex flex-wrap justify-content-between ps-0 h-100">
        <a id="header-logo" href="/" class="navbar-brand sidebar-shrink-width d-flex justify-content-center">
            <LogoFull class="logo img-fluid logo-shrink shrink-hide m-auto mx-3" height="3rem" id="logo-svg" fill="blue" />
            <LogoIcon class="logo img-fluid logo-shrink shrink-show m-auto" height="3rem" id="logo-svg" fill="blue" />
        </a>
        <div class="d-flex align-items-center">
            <span class="text-muted small">
                Component loaded at: {loadTime}
            </span>
            <ColorSwitcherAppbarNav />
            <AppbarCommentNav />
            <AppbarUserNav />
        </div>
    </div>
</header>
