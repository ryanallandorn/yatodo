<script>
// resources/js/Components/Structure/Nav/InnerMain.svelte

    import { onMount } from 'svelte';
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

    onMount(() => {
        // Initialize all tooltips on the page
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.forEach((tooltipTriggerEl) => {
            new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });

</script>

<ul class="nav nav-tabs">
    {#each navItems as navItem}
        {#if navItem.children.length === 0}
            <li class="nav-item">
                <a
                    class="nav-link {isActive(navItem?.link) ? 'active' : ''}"
                    href={navItem?.link}
                    data-bs-toggle={navItem?.tooltip ? "tooltip" : null}
                    data-bs-placement="top"
                    data-bs-title={navItem?.tooltip}
                >
                    {#if navItem?.iconClass}
                        <i class={navItem.iconClass}></i> 
                    {/if}
                    {#if navItem?.iconHtml}
                        {@html navItem.iconHtml}
                    {/if}
                    {#if navItem?.iconPath}
                    <svg 
                        viewBox="0 0 24 24" 
                        width="24" 
                        height="24"
                        class="inline-block align-middle"
                    >
                        <path d={navItem.iconPath} fill="currentColor" />
                    </svg>
                {/if}
                {#if navItem?.showText !== false}
                    {navItem.name}
                {/if}
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
