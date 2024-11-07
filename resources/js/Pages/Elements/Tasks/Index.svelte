<script>
// resources/js/Pages/Elements/Tasks/Index.svelte

    import { t } from 'svelte-i18n';
    import { useForm } from "@inertiajs/svelte";
    import App from "@layouts/App.svelte";

    import ContextNav from '@components/Structure/Nav/Context.svelte';
    import Datatable from '@components/UI/Datatable/Datatable.svelte';
    import ActionsDropdown from '@components/Actions/Dropdown.svelte';
    import LinkViewPage from '@components/UI/Datatable/Slots/LinkViewPage.svelte';
    import LinkViewModal from '@pages/Elements/Tasks/Show/Modal.svelte';

    // Define nav items and actions
    let navItems = [
        {
            name: 'All Tasks',
            icon: 'bi bi-globe',
            link: route('tasks.index'),
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
                component: LinkViewModal,
                props: {
                    apiGetRoute: (item) => `/api/get/tasks?filters[id]=${item.id}`,
                    displaySlot: (item) => item.name, 
                    modalTitle: (item) => item.name, 
                }
            }
        },
        { 
            key: 'project_name', 
            label: 'Project', 
            render: {
                component: LinkViewPage,
                props: {
                    route: (item) => route('projects.show', item.project_id), 
                    displaySlot: (item) => item.project_name 
                }
            }
        },
        { 
            key: 'parent_task_id', 
            label: 'Parent Task' 
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
</script>

<App>

    <div slot="hero" class="hero-contents">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            <i class="bi bi-check2-square"></i> 
            {$t('Tasks')}
        </h2>
    </div>

    <!-- Pass `navItems` to `ContextNav` and use the `actions` slot -->
    <ContextNav {navItems}>
        <div class="context-actions" slot="actions">
            <a class="btn btn-primary" href="{route('tasks.create')}">
                {$t('Add Task')}
                <i class="bi bi-plus-lg"></i>
            </a>
        </div>
    </ContextNav>

    <!-- Datatable Component -->
    <Datatable 
        apiUrl="{route('api.get.tasks')}"
        {columns}
    />

</App>
