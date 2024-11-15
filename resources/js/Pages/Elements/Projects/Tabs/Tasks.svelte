<script>

// resources/js/Pages/Elements/Projects/Tabs/Tasks.svelte

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

    export let project;

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

    let filters = [
        {
            key: 'includeSubtasks',
            value: false, 
            reloadOnChange: true,
            type: 'FilterSwitch',
            label: $t('Include Subtasks'),
            callbacks:[],
        },
        {
            key: 'project_id',
            value: project.id, 
            type: 'hiddenValue',
            callbacks:[],
        },
        // Add more filters as needed
    ];



    function openUserModal() {
        modal.open(); // Access the modal's `open` method through the bound instance
    }

    function handleFormSubmit() {
        if (formInstance && formInstance.externalSubmit) {
            formInstance.externalSubmit(); // Call the exposed method
        }
    }

</script>





    <Datatable 
        apiUrl="{route('api.get.tasks')}"
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
