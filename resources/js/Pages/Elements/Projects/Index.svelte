<script>

// resources/js/Pages/Elements/Projects/Index.svelte

    import { ray } from 'node-ray';
    import { t } from 'svelte-i18n';
    import { useForm } from "@inertiajs/svelte";
    import { writable } from 'svelte/store';

    import App from "@layouts/App.svelte";
    import ModalBox from '@components/UI/Modal/Box.svelte';
    import CreateProjectForm from '@pages/Elements/Projects/Forms/Store.svelte';
    import ContextNav from '@components/Structure/Nav/Context.svelte';
    import Datatable from '@components/UI/Datatable/Datatable.svelte';
    import ActionsDropdown from '@components/Actions/Dropdown.svelte';
    import LinkViewPage from '@components/UI/Datatable/Cells/LinkViewPage.svelte';

    let modal; // Store the modal instance reference
    let formInstance;

    // Define nav items and actions
    let navItems = [
        {
            name: 'All Projects',
            icon: 'bi bi-globe',
            link: route('projects.index'),
            children: []
        },
    ];

    // Define columns with labels
    const columns = [
        { 
            key: 'id', 
            label: 'ID' 
        },
        { 
            key: 'name', 
            label: 'Name', 
            render: {
                component: LinkViewPage,
                props: {
                    route: (item) => route('projects.show', item.id), 
                    displaySlot: (item) => item.name 
                }
            }
        },
        { 
            key: 'actions', 
            label: 'Actions', 
            hideLabel: true, 
            icon: 'bi bi-three-dots-vertical', 
            render: {
                component: ActionsDropdown,
                props: {}
            }
        }
    ];

    let filters = [];

    function openUserModal() {
        modal.open(); // Access the modal's `open` method through the bound instance
    }

    function handleFormSubmit() {
        if (formInstance && formInstance.externalSubmit) {
            formInstance.externalSubmit(); // Call the exposed method
        }
    }

</script>

<App>

    <div slot="hero" class="hero-contents">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            <i class="bi bi-briefcase"></i> {$t('Projects')}
        </h2>
    </div>

    <!-- Pass `navItems` to `ContextNav` and use the `actions` slot -->
    <ContextNav {navItems}>
        <div class="context-actions" slot="actions">
            <button 
                class="btn btn-primary" 
                on:click={openUserModal}
            >
                {$t('Add Project')}
                <i class="bi bi-plus-lg"></i>
            </button>
        
            <ModalBox
                bind:this={modal}
            >
                <span slot="title">{$t('Add A Project')}</span>
        
                <div slot="staticContent">
                    <CreateProjectForm bind:this={formInstance} showSubmitButton={false} />
                </div>
        
                <div slot="footer">
                    <!-- <button type="button" class="btn btn-secondary" on:click={() => console.log('Custom Close')}>
                        Custom Close
                    </button> -->
                    <button type="button" class="btn btn-primary" on:click={handleFormSubmit}>
                        {$t('Add Project')}
                        <i class="bi bi-plus-lg"></i>
                    </button>
                </div>
            </ModalBox>
            <!-- <a class="btn btn-primary" href="{route('projects.create')}">
                {$t('Add Project')}
                <i class="bi bi-plus-lg"></i>
            </a> -->
        </div>
    </ContextNav>

    <Datatable 
        apiUrl="{route('api.get.projects')}"
        {columns}
        datatableControls={{
            search: {
                enabled: true,
                debounce: 300,
                //trigger: 'onKeystroke' 
                //fields:[],
            },
            pageLength: {
                enabled:true,
            }
        }}
        {filters}
    />

</App>
