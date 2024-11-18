

<script>

// resources/js/Pages/Elements/Tasks/SubtasksDatatable.svelte

    import { t } from 'svelte-i18n';
    import { useForm } from "@inertiajs/svelte";
    import { tick } from 'svelte';
    import App from "@layouts/App.svelte";

    import ModalBox from '@components/UI/Modal/Box.svelte';
    //import CreateTaskForm from '@pages/Elements/Tasks/Forms/StoreSingle.svelte';
    import ModalAddBody from '@pages/Elements/Tasks/Modal/Add/Body.svelte';

    import ContextNav from '@components/Structure/Nav/Context.svelte';
    import Datatable from '@components/UI/Datatable/Datatable.svelte';
    import ActionsDropdown from '@components/Actions/Dropdown.svelte';
    import LinkViewPage from '@components/UI/Datatable/Cells/LinkViewPage.svelte';
    import CollapseRow from '@components/UI/Datatable/Cells/CollapseRow.svelte';
    import SubtasksDatatable from '@pages/Elements/Tasks/SubtasksDatatable.svelte';
    import LinkViewModal from '@pages/Elements/Tasks/Show/Modal.svelte';
    import FieldInlineCompleteSwitch from '@components/Fields/Inline/Switch.svelte'; 

    export let data;
    export let parentItem;

    // Define columns with labels
    const columns = [
        // { 
        //     key: 'id', 
        //     label: 'ID' 
        // },
        {
            key: 'completed',
            label: 'Completed',
            render: {
                component: FieldInlineCompleteSwitch,
                props: {
                    apiPutRoute: (item) => route('api.update.taskFieldSingle', { 
                        task: item.id,
                        fieldName: "completed"
                    }), // API route for update
                    item: (item) => item, // Pass the whole item
                    fieldName: 'completed', // Field name to update
                    fieldValue: (item) => !!item.completed, // Ensure fieldValue is a boolean
                    label: '', // Complete
                    id: (item) => `task-completed-switch-${item.id}`
                }
            }
        },
        { 
            key: 'name', 
            label: 'Name', 
            render: {
                component: LinkViewModal,
                props: {
                    apiGetRoute: (item) => `/api/get/task/${item.id}`,
                    displaySlot: (item) => item.name, 
                    modalTitle: (item) => item.name, 
                }
            }
        },
        // make conditional
        // { 
        //     key: 'project_name', 
        //     label: $t('Project'), 
        //     render: {
        //         component: LinkViewPage,
        //         props: {
        //             route: (item) => route('projects.show', item.project_id), 
        //             displaySlot: (item) => item.project_name 
        //         }
        //     }
        // },
        // // reactive if filter parent
        // { 
        //     key: 'name', 
        //     label: $t('Parent Task'), 
        //     render: {
        //         component: LinkViewModal,
        //         props: {
        //             apiGetRoute: (item) => `/api/get/task/${item.parent_task_id}`,
        //             displaySlot: (item) => item.parent_task_name, 
        //             modalTitle: (item) => item.parent_task_name, 
        //         }
        //     }
        // },
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
            key: 'parent_task_id',
            value: parentItem.id, 
            type: 'hiddenValue',
            callbacks:[],
        },
        // Add more filters as needed
    ];
</script>



    <!-- Datatable Component -->
    <Datatable 
        apiUrl="{route('api.get.tasks')}"
        {columns}
        datatableControls={{
            search: {
                enabled: false,
                debounce: 300,
                //trigger: 'onKeystroke' 
                //fields:[],
            },
            pageLength: {
                enabled:true,
            }
        }}
        {filters}
    >
        <div slot="datatable-controls"></div> <!-- bIND SLOT ITEMS TO DATATABLE-->
        <div slot="datatable-filters">
            <!-- <FilterSwitch
                label="Include Subtasks"
                bind:value={filters.includeSubtasks}
                on:change={(e) => applyFilter('includeSubtasks', e.detail.value)}
            /> -->
        </div>
    </Datatable>