<script>
// resources/js/Pages/Elements/Notes/Index.svelte

    import { t } from 'svelte-i18n';
    import { useForm } from "@inertiajs/svelte";
    import { tick } from 'svelte';
    import App from "@layouts/App.svelte";

    import ModalBox from '@components/UI/Modal/Box.svelte';
    //import CreateNoteForm from '@pages/Elements/Notes/Forms/StoreSingle.svelte';
    import ModalAddBody from '@pages/Elements/Notes/Modal/Add/Body.svelte';
    //import ModalShowBody from '@pages/Elements/Notes/Modal/Show/Body.svelte';

    import ContextNav from '@components/Structure/Nav/Context.svelte';
    import Datatable from '@components/UI/Datatable/Datatable.svelte';
    import ActionsDropdown from '@components/Actions/Dropdown.svelte';
    import LinkViewPage from '@components/UI/Datatable/Cells/LinkViewPage.svelte';
    import CollapseRow from '@components/UI/Datatable/Cells/CollapseRow.svelte';
    //import SubtasksDatatable from '@pages/Elements/Notes/SubtasksDatatable.svelte';
    import LinkViewModal from '@pages/Elements/Notes/Show/Modal.svelte';
    import { mdiTable, mdiChartGantt, mdiViewColumnOutline, mdiCalendarOutline } from '@mdi/js';

    let modal; // Store the modal instance reference
    let formInstance;

    function openNoteModal() {
        modal.open(); // Access the modal's `open` method through the bound instance
    }

    async function handleFormSubmit() {
        console.log(formInstance);
        await tick(); // Wait for the DOM to update
        if (formInstance && formInstance.externalSubmit) {
            formInstance.externalSubmit(); // Call the exposed method
        }
    }

    // Define nav items and actions
    let navItems = [
        {
            name: $t('Table'),
            showText:false,
            tooltip: $t('Table'), 
            // iconClass: 'bi bi-globe',
            iconPath: mdiTable,
            link: route('notes.index'),
            children: []
        },
        // {
        //     name: $t('Boards'),
        //     showText:false,
        //     tooltip: $t('Boards'), 
        //     // iconClass: 'bi bi-globe',
        //     iconPath: mdiViewColumnOutline,
        //     link: route('tasks.index'),
        //     children: []
        // },
        // {
        //     name: $t('Gantt'),
        //     showText:false,
        //     tooltip:$t('Gantt'),
        //     //iconClass: 'bi bi-globe',
        //     //iconHtml: '<span slot="button" class="material-symbols-outlined d-inline-flex">chart_gantt</span>',
        //     iconPath: mdiChartGantt,
        //     link: route('tasks.index'),
        //     children: []
        // },
        // {
        //     name: $t('Calendar'),
        //     showText:false,
        //     //iconClass: 'bi bi-globe',
        //     // iconHtml: '<span slot="button" class="material-symbols-outlined d-inline-flex">table</span>',
        //     iconPath: mdiCalendarOutline,
        //     link: route('tasks.index'),
        //     children: []
        // },

    ];

    // Define columns with labels
    const columns = [
        // { 
        //     key: 'id', 
        //     label: 'ID' 
        // },
        { 
            key: 'name', 
            label: 'Name', 
            render: {
                component: LinkViewModal,
                props: {
                    apiGetRoute: (item) => `/api/get/note/${item.id}`,
                    displaySlot: (item) => item.name, 
                    modalTitle: (item) => item.name, 
                }
            }
        },
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
        //     label: $t('Parent Note'), 
        //     render: {
        //         component: LinkViewModal,
        //         props: {
        //             apiGetRoute: (item) => `/api/get/task/${item.parent_task_id}`,
        //             displaySlot: (item) => item.parent_task_name, 
        //             modalTitle: (item) => item.parent_task_name, 
        //         }
        //     }
        // },
        // { 
        //     key: 'subtasks', 
        //     label: $t('Subtasks'), 
        //     render: {
        //         component: CollapseRow,
        //         props: {
        //             buttonDisplaySlot: (item) => `${item.subtasks_count || 0}`, // PROGRESS subtasks
        //             contentSlot: () => SubtasksDatatable,
        //             item: (item) => item,
        //             parentRowId: (item) => item.id  // Add this line
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
        // {
        //     key: 'includeSubtasks',
        //     value: false, 
        //     reloadOnChange: true,
        //     type: 'FilterSwitch',
        //     label: $t('Include Subtasks'),
        //     callbacks:[],
        // },
        // // Add more filters as needed
    ];
</script>

<App>

    <div slot="hero" class="hero-contents">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            <i class="bi bi-sticky"></i> 
            {$t('Notes')}
        </h2>
    </div>

    <!-- Pass `navItems` to `ContextNav` and use the `actions` slot -->
    <ContextNav {navItems}>
        <div class="context-actions" slot="actions">

            <button 
                class="btn btn-primary" 
                on:click={openNoteModal}
            >
                {$t('Add Note')}
                <i class="bi bi-sticky"></i>
            </button>
        
            <ModalBox
                bind:this={modal}
            >
                <span slot="title">{$t('Add A Note')}</span>
        
                <div slot="staticContent">
                    <ModalAddBody bind:formInstance />
                </div>
        
                <div slot="footer">
                    <!-- <button type="button" class="btn btn-secondary" on:click={() => console.log('Custom Close')}>
                        Custom Close
                    </button> -->
                    <button type="button" class="btn btn-primary" on:click={handleFormSubmit}>
                        {$t('Add Note')}
                        <i class="bi bi-sticky"></i>
                    </button>
                </div>
            </ModalBox>

            <!-- <a class="btn btn-primary" href="{route('tasks.create')}">
                {$t('Add Note')}
                <i class="bi bi-person-add"></i>
            </a> -->
        </div>
    </ContextNav>

    <!-- Datatable Component -->
    <Datatable 
        apiUrl="{route('api.get.notes')}"
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

</App>
