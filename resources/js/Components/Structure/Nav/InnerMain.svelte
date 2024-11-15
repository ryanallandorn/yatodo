<script>
// resources/js/Components/Structure/Nav/InnerMain.svelte

import { t } from 'svelte-i18n';
import { page} from "@inertiajs/svelte";

    export let navItems = [];

    // Check if a given path is active based on the current window location
    const isActive = (link) => {
        // Create a URL object for the current location
        const currentUrl = new URL(window.location.href);

        // Check if the link is a fully qualified URL
        try {
            const linkUrl = new URL(link, window.location.origin);
            return currentUrl.pathname === linkUrl.pathname;
        } catch {
            // If link is not fully qualified, fallback to comparing pathnames
            return currentUrl.pathname.startsWith(link);
        }
    };

</script>

<ul class="nav nav-tabs">
    {#each navItems as navItem}
        {#if navItem.children.length === 0}
            <li class="nav-item">
                <a
                    class="nav-link {isActive(navItem.link) ? 'active' : ''}"
                    href={navItem.link}
                >
                    <i class={navItem.icon}></i> {navItem.name}
                </a>
            </li>
        {:else}
            <li class="nav-item dropdown">
                <a
                    class="nav-link dropdown-toggle {isActive(navItem.link) ? 'active' : ''}"
                    href="#"
                    role="button"
                    data-bs-toggle="dropdown"
                    aria-expanded="false"
                >
                    <i class={navItem.icon}></i> {navItem.name}
                </a>
                <ul class="dropdown-menu">
                    {#each navItem.children as child}
                        <li>
                            <a
                                class="dropdown-item {isActive(child.link) ? 'active' : ''}"
                                href={child.link}
                            >
                                {child.name}
                            </a>
                        </li>
                    {/each}
                </ul>
            </li>
        {/if}
    {/each}
</ul>
