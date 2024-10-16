<!-- src/routes/layout/Header.svelte -->

<script>
    import { onMount } from 'svelte';
    import { page, inertia, Link } from "@inertiajs/svelte";
    import { t } from 'svelte-i18n';
    // import { logout } from '$lib/stores/auth'; // This should handle the logout API call
    import LogoFull from '@components/Branding/LogoFull.svelte';
    import LogoIcon from '@components/Branding/LogoIcon.svelte';
    import ColorSwitcherAppbarNav from '@components/UI/States/ColorSwitcher/AppbarNav.svelte';
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
</script>

<header id="header" class="fixed-top border-bottom bg-body-tertiary">
    <div class="container-fluid d-flex flex-wrap justify-content-between ps-0 h-100">
        <a id="header-logo" href="/" class="navbar-brand sidebar-shrink-width d-flex justify-content-center">
            <LogoFull class="logo img-fluid logo-shrink shrink-hide m-auto mx-3" height="3rem" id="logo-svg" fill="blue" />
            <LogoIcon class="logo img-fluid logo-shrink shrink-show m-auto" height="3rem" id="logo-svg" fill="blue" />
        </a>

        <div class="d-flex align-items-center">
            <ColorSwitcherAppbarNav />

            <div class="flex-shrink-0 dropdown me-3">
                <a href="/" class="d-block link-body-emphasis text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    {#if $page.props.jetstream.managesProfilePhotos}
                    <img
                      class="h-8 w-8 rounded-full object-cover"
                      src="{$page.props.auth.user.profile_photo_url}"
                      alt="{$page.props.auth.user.name}"
                    />
                  {:else}
                    <span>{$page.props.auth.user.name}</span>
                  {/if}
                </a>
                <ul class="dropdown-menu text-small shadow">
                        <li>
                            <div class="dropdown-header">
                                {$t('Manage Account')}
                            </div>
                        </li>
                        <li>
                            <Link href="{route('profile.show')}">
                                {$t('Profile')}
                            </Link>
                        </li>
                        {#if $page.props.jetstream.hasApiFeatures}
                        <li>
                            <Link href="{route('api-tokens.index')}">
                                {$t('API Tokens')}
                            </Link>
                        </li>
                        {/if}
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <button use:inertia="{{ href: '/logout', method: 'post' }}" type="button">
                                {$t('Logout')}
                            </button>
                        </li>
                </ul>
            </div>

        </div>
    </div>
</header>
