<!-- resources/js/Pages/Elements/Projects/Show/Page.svelte -->

<script>
    import { t } from 'svelte-i18n';
    import { onMount } from 'svelte';
    import { useForm } from "@inertiajs/svelte";
    import { writable } from 'svelte/store';
    
    import App from "@layouts/App.svelte";
    import ModalBox from '@components/UI/Modal/Box.svelte';
    import CreateProjectForm from '@pages/Elements/Projects/Forms/Store.svelte';
    import ContextNav from '@components/Structure/Nav/Context.svelte';

    // Import your tab components here
    // import TabTasks from '@pages/Elements/Projects/Tabs/Tasks.svelte';
    // import TabNotes from '@pages/Elements/Projects/Tabs/Notes.svelte';
    // import TabFiles from '@pages/Elements/Projects/Tabs/Tasks.svelte';

    
    let modal;
    let formInstance;
    let activeComponent = null;

    export let project;
    export let tab;

    // Lazy loading components using dynamic imports
    const tabComponents = {
        tasks: () => import('@pages/Elements/Projects/Tabs/Tasks.svelte'),
        notes: () => import('@pages/Elements/Projects/Tabs/Notes.svelte'),
        files: () => import('@pages/Elements/Projects/Tabs/Files.svelte')
    };

    // Define navigation items
    let navItems = [
        {
            name: $t('Tasks'),
            icon: 'bi bi-check2-square',
            link: route('projects.show', { project: project.id, view: 'tasks' }),
            children: []
        },
        {
            name: $t('Notes'),
            icon: 'bi bi-sticky',
            link: route('projects.show', { project: project.id, view: 'notes' }),
            children: []
        },
        // {
        //     name: $t('Comments'),
        //     icon: 'bi bi-check2-square',
        //     link: route('projects.show', { project: 1, view: 'notes' }),
        //     children: []
        // },
        {
            name: $t('Files'),
            icon: 'bi bi-archive',
            link: route('projects.show', { project: project.id, view: 'files' }),
            children: []
        },
        // users
        // settings
        // CONTACTS
    ];

        // // Tab components lookup
        // const tabComponents = {
        //     tasks: TabTasks,
        //     notes: TabNotes,
        //     files: TabFiles
        // };



    // Load the active component dynamically
    async function loadComponent(tab) {
        if (tabComponents[tab]) {
            activeComponent = (await tabComponents[tab]()).default;
        }
    }

    // Load the initial component on mount
    onMount(() => {
        loadComponent(tab);
    });


</script>

<App>
    <div slot="hero" class="hero-contents">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            <i class="bi bi-check2-square"></i>
            {$t('Project')}: {project.name}
        </h2>
    </div>

    <!-- Project Details Section -->
    {#if project?.description}
    <div class="project-details">
    <p>{project.description}</p>
    </div>
    {/if}

    <!-- Navigation and Modal Box -->
    <ContextNav {navItems}>
        <div class="context-actions" slot="actions">
            <button class="btn btn-primary">
                {$t('Add')} <i class="bi bi-plus-lg"></i>
            </button>
            <!-- <button class="btn btn-primary" on:click={openUserModal}>
                {$t('Add Project')} <i class="bi bi-plus-lg"></i>
            </button>
        
            <ModalBox bind:this={modal}>
                <span slot="title">{$t('Add A Project')}</span>
                <div slot="staticContent">
                    <CreateProjectForm bind:this={formInstance} showSubmitButton={false} />
                </div>
                <div slot="footer">
                    <button type="button" class="btn btn-primary" on:click={handleFormSubmit}>
                        {$t('Add Project')} <i class="bi bi-plus-lg"></i>
                    </button>
                </div>
            </ModalBox> -->
        </div>
    </ContextNav>
    <!-- Tab Content Section -->
    <div class="tab-content">
        {#if activeComponent}
            <svelte:component this={activeComponent} {project} />
        {/if}
    </div>
</App>
