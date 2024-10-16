<!-- src/routes/layout/Header.svelte -->

<script>
    import { onMount } from 'svelte';
    import { page, Link } from "@inertiajs/svelte";
    // import { logout } from '$lib/stores/auth'; // This should handle the logout API call
    import LogoFull from '@components/Branding/LogoFull.svelte';
    import LogoIcon from '@components/Branding/LogoIcon.svelte';
    import ColorSwitcherAppbarNav from '@components/UI/States/ColorSwitcher/AppbarNav.svelte';
    // import Timer from '$lib/components/Timer.svelte'; // Your timer component
    // import DropdownMega from '$components/DropdownMega.svelte'; // Dropdown Mega component
    // import DropdownLink from '$components/DropdownLink.svelte'; // Link component for dropdown
    // import UserAvatar from '$components/UserAvatar.svelte'; // Your user avatar component
    //import { user, teams } from '$lib/stores/auth'; // Assuming auth store manages user & team data

    // let currentTeam = null;

    // const teams = {};

    onMount(() => {
        //currentTeam = user.currentTeam; // Fetch current team on mount if Jetstream-like feature is used
    });

    function handleLogout() {
        logout(); // Call the logout function from your store or API
    }
</script>

<header id="header" class="fixed-top border-bottom bg-body-tertiary">
    <div class="container-fluid d-flex flex-wrap justify-content-between ps-0 h-100">
        <a id="header-logo" href="/" class="navbar-brand sidebar-shrink-width d-flex col-md-3 col-lg-2">
            <LogoFull class="logo img-fluid logo-shrink shrink-hide m-auto mx-3" height="3rem" />
            <LogoIcon class="logo img-fluid logo-shrink shrink-show m-auto" height="3rem" />
        </a>

        <div class="d-flex align-items-center">
            <ColorSwitcherAppbarNav />

            {#if teams.hasTeamFeatures}
                <DropdownMega dropdownClasses="flex-shrink-0 dropdown me-3 bd-mode-toggle" menuClasses="custom-menu-class" dropdownId="dropdownTeam">
                    <svelte:fragment slot="trigger">
                        <button class="btn btn-bd-primary py-2 dropdown-toggle d-flex align-items-center" type="button" id="dropdownTeam">
                            {currentTeam?.name}
                        </button>
                    </svelte:fragment>
                    <svelte:fragment slot="content">
                        <div class="block px-4 py-2 text-xs text-gray-400">
                            Manage Team
                        </div>
                        <DropdownLink href={`/teams/${currentTeam?.id}`}>Team Settings</DropdownLink>

                        {#if user.canCreateTeam}
                            <DropdownLink href="/teams/create">Create New Team</DropdownLink>
                        {/if}

                        {#if teams.allTeams.length > 1}
                            <div class="border-t border-gray-200 dark:border-gray-600"></div>
                            <div class="block px-4 py-2 text-xs text-gray-400">
                                Switch Teams
                            </div>
                            {#each teams.allTeams as team}
                                <DropdownLink href="#" on:click={() => switchTeam(team)}>
                                    {team.name}
                                </DropdownLink>
                            {/each}
                        {/if}
                    </svelte:fragment>
                </DropdownMega>
            {/if}

            <div class="flex-shrink-0 dropdown me-3">
                <a href="#" class="d-block link-body-emphasis text-decoration-none dropdown-toggle" data-bs-toggle="dropdown">
                    {#if user.profilePhotoUrl}
                        <UserAvatar profilePhotoUrl={user.profilePhotoUrl} name={user.name} width="32" height="32" />
                    {:else}
                        {user.name}
                    {/if}
                </a>

                <ul class="dropdown-menu text-small shadow">
                    <li>
                        <div class="dropdown-header">Manage Account</div>
                    </li>
                    <li>
                        <DropdownLink href="/profile">Profile</DropdownLink>
                    </li>

                    {#if user.hasApiFeatures}
                        <li>
                            <DropdownLink href="/api-tokens">API Tokens</DropdownLink>
                        </li>
                    {/if}

                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form on:submit|preventDefault={handleLogout}>
                            <DropdownLink href="/logout">Log Out</DropdownLink>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</header>
