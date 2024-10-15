<script>

// resources/js/Components/Structure/Sidebar.svelte

    let sidebarNav = [
        {
            name: 'Dashboard',
            icon: 'bi bi-speedometer2',
            link: '/',
            children: []
        },
        {
            name: 'Projects',
            icon: 'bi bi-briefcase',
            link: '/projects',
            children: []
        },
        {
            name: 'Tasks',
            icon: 'bi bi-check2-square',
            link: '/tasks',
            children: [
                { name: 'Completed', link: '/tasks#completed' },
                { name: 'Overdue', link: '/tasks#overdue' }
            ]
        },
        {
            name: 'Bases',
            icon: 'bi bi-mortarboard',
            link: '/tasks',
            children: [
                { name: 'Completed', link: '/tasks#completed' },
                { name: 'Overdue', link: '/tasks#overdue' }
            ]
        },
        {
            name: 'Tickets',
            icon: 'bi bi-life-preserver',
            link: '/tasks',
            children: [
                { name: 'Completed', link: '/tasks#completed' },
                { name: 'Overdue', link: '/tasks#overdue' }
            ]
        },
        {
            name: 'Files',
            icon: 'bi bi-archive',
            link: '/tasks',
            children: [
                { name: 'Completed', link: '/tasks#completed' },
                { name: 'Overdue', link: '/tasks#overdue' }
            ]
        },
        {
            name: 'Notes',
            icon: 'bi bi-sticky',
            link: '/notes',
            children: [
                { name: 'Completed', link: '/tasks#completed' },
                { name: 'Overdue', link: '/tasks#overdue' }
            ]
        }
    ];

    let currentRoute = '/'; // You can replace this with a reactive store or function to track the current route
</script>

<div id="sidebar-left" class="sidebar sidebar-shrink-width border border-right p-0 bg-body-tertiary position-fixed top-0 start-0 d-flex flex-column h-100">
    <div class="offcanvas-md offcanvas-end h-100 sidebar-inner sidebar-shrink-width" tabindex="-1" aria-labelledby="sidebarMenuLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="sidebarMenuLabel">Company name</h5>
            <button type="button" class="btn-close" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body d-md-flex flex-column p-0 h-100">
            <ul class="nav flex-column">
                {#each sidebarNav as navItem}
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center gap-2 text-light {navItem.link === currentRoute ? 'active' : ''}" href={navItem.link}>
                            <i class={`nav-icon ${navItem.icon} d-flex`}></i>
                            <span class="nav-text d-flex shrink-hide">{navItem.name}</span>
                        </a>

                        {#if navItem.children.length > 0}
                            <ul class="nav flex-column ms-3">
                                {#each navItem.children as child}
                                    <li class="nav-item">
                                        <a class="nav-link d-flex align-items-center gap-2 text-light {child.link === currentRoute ? 'active' : ''}" href={child.link}>
                                            <span class="nav-text d-flex shrink-hide">{child.name}</span>
                                        </a>
                                    </li>
                                {/each}
                            </ul>
                        {/if}
                    </li>
                {/each}
            </ul>

            <div class="sidebar-bottom mt-auto position-relative">
                <ul class="nav flex-column mb-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link d-flex align-items-center gap-2 active dropdown-toggle" href="#" id="settingsDropdown" role="button" aria-expanded="false">
                            <i class="bi bi-gear"></i>
                            <span class="nav-text d-flex shrink-hide">Settings</span>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="settingsDropdown">
                            <li><a class="dropdown-item" href="/settings/etl/api-calls">API Calls</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <button 
        type="button" 
        class="btn btn-outline-secondary rounded-circle mt-3 p-0 position-absolute bottom-0 end-0" 
        id="btn-sidebar-toggle" 
        title="Collapse Sidebar"
    >
        <i class="bi bi-chevron-left shrink-hide"></i>
        <i class="bi bi-chevron-right shrink-show"></i>
    </button>
</div>

<style>
    /* Add any relevant styles */
</style>
