<script>
    // resources/js/Pages/Elements/Tasks/Index.svelte
    
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
        import LinkViewPage from '@components/UI/Datatable/Slots/LinkViewPage.svelte';
        import LinkViewModal from '@pages/Elements/Tasks/Show/Modal.svelte';
    
    
    
        let modal; // Store the modal instance reference
        let formInstance;
    
        function openUserModal() {
            modal.open(); // Access the modal's `open` method through the bound instance
        }
    
        async function handleFormSubmit() {
            alert('hi');
            console.log(formInstance);
            await tick(); // Wait for the DOM to update
            if (formInstance && formInstance.externalSubmit) {
                alert('wee');
                formInstance.externalSubmit(); // Call the exposed method
            }
        }
    
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
                        apiGetRoute: (item) => `/api/get/task/${item.id}`,
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
            // reactive if filter parent
            { 
                key: 'name', 
                label: 'Name', 
                render: {
                    component: LinkViewModal,
                    props: {
                        apiGetRoute: (item) => `/api/get/task/${item.parent_task_id}`,
                        displaySlot: (item) => item.parent_task_name, 
                        modalTitle: (item) => item.parent_task_name, 
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
    
                <button 
                    class="btn btn-primary" 
                    on:click={openUserModal}
                >
                    {$t('Add Task')}
                    <i class="bi bi-plus-lg"></i>
                </button>
            
                <ModalBox
                    bind:this={modal}
                >
                    <span slot="title">{$t('Add A Task')}</span>
            
                    <div slot="staticContent">
                        <ModalAddBody bind:formInstance />
                    </div>
            
                    <div slot="footer">
                        <!-- <button type="button" class="btn btn-secondary" on:click={() => console.log('Custom Close')}>
                            Custom Close
                        </button> -->
                        <button type="button" class="btn btn-primary" on:click={handleFormSubmit}>
                            {$t('Add Task')}
                            <i class="bi bi-plus-lg"></i>
                        </button>
                    </div>
                </ModalBox>
    
                <!-- <a class="btn btn-primary" href="{route('tasks.create')}">
                    {$t('Add Task')}
                    <i class="bi bi-plus-lg"></i>
                </a> -->
            </div>
        </ContextNav>
    
        <!-- Datatable Component -->
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
                },
                filters:{
                    projectId:1
                }
            }}
        >
            <div slot="datatable-controls"></div> <!-- bIND SLOT ITEMS TO DATATABLE-->
            <div slot="datatable-filters"></div>
        </Datatable>
    
    </App>
    