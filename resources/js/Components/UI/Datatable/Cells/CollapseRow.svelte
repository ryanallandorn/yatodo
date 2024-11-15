<script>
    import { createEventDispatcher, onMount } from 'svelte';
    
    export let buttonDisplaySlot;
    export let contentSlot;
    export let apiGetRoute = null;
    export let item;
    export let parentRowId;
    
    $: actualParentId = typeof parentRowId === 'function' ? parentRowId(item) : item?.id;
    
    let isExpanded = false;
    let isLoading = false;
    let tableElement;
    let childRow = null;
    let childComponent = null;
    let parentRow = null; // Store reference to parent row
    const dispatch = createEventDispatcher();
    
    const mockData = {
        id: 1,
        name: "Test Task",
        description: "This is a test task",
        subtasks: [
            {
                id: 2,
                name: "Subtask 1",
                description: "First subtask description",
                status: "pending"
            },
            {
                id: 3,
                name: "Subtask 2",
                description: "Second subtask description",
                status: "completed"
            }
        ]
    };
    
    onMount(() => {
        tableElement = document.querySelector('table');
        // Store parent row reference on mount
        parentRow = document.querySelector(`button[data-parent-id="${actualParentId}"]`)?.closest('tr');
        return () => {
            destroyChildRow();
        };
    });
    
    function destroyChildRow() {
        if (childComponent) {
            childComponent.$destroy();
            childComponent = null;
        }
        if (childRow) {
            childRow.remove();
            childRow = null;
        }
        // Remove expanded class from parent
        if (parentRow) {
            parentRow.classList.remove('tr-expanded');
        }
    }
    
    async function toggleCollapse(event) {
        const buttonElement = event.currentTarget;
        parentRow = buttonElement.closest('tr');
        
        if (isExpanded) {
            destroyChildRow();
            buttonElement.querySelector('.bi').classList.replace('bi-chevron-down', 'bi-chevron-right');
            parentRow.classList.remove('tr-expanded');
            isExpanded = false;
        } else {
            isLoading = true;
            
            try {
                destroyChildRow();
                
                buttonElement.querySelector('.bi').classList.replace('bi-chevron-right', 'bi-chevron-down');
                parentRow.classList.add('tr-expanded');
                
                await new Promise(resolve => setTimeout(resolve, 500));
                const data = mockData;
                
                childRow = document.createElement('tr');
                childRow.dataset.parentId = actualParentId;
                childRow.classList.add('child-row');
                
                const indentCell = document.createElement('td');
                const contentCell = document.createElement('td');
                contentCell.colSpan = tableElement.querySelectorAll('th').length - 1;
                
                childRow.appendChild(indentCell);
                childRow.appendChild(contentCell);
                parentRow.insertAdjacentElement('afterend', childRow);
                
                const ComponentToRender = contentSlot;
                
                childComponent = new ComponentToRender({
                    target: contentCell,
                    props: {
                        data,
                        parentItem: item
                    }
                });
                
                childRow.animate([
                    { opacity: 0 },
                    { opacity: 1 }
                ], {
                    duration: 200,
                    easing: 'ease-out'
                });
                
                isExpanded = true;
            } catch (error) {
                console.error('Failed to load content:', error);
                // Remove expanded class if there's an error
                parentRow.classList.remove('tr-expanded');
            } finally {
                isLoading = false;
            }
        }
        
        dispatch('toggleRow', {
            parentId: actualParentId,
            isExpanded,
            item
        });
    }
    </script>
    
    <button 
        class="btn btn-link d-flex align-items-center gap-2" 
        on:click={toggleCollapse}
        data-parent-id={actualParentId}
    >
        <i class="bi bi-chevron-right"></i>
        {typeof buttonDisplaySlot === 'function' ? buttonDisplaySlot(item) : buttonDisplaySlot}
        {#if isLoading}
            <div class="spinner-border spinner-border-sm" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        {/if}
    </button>
    
    <style>
    :global(.child-row) {
        background-color: rgba(0, 0, 0, 0.02);
    }
    
    :global(.child-row td:first-child) {
        width: 2rem;
    }
    
    :global(.tr-expanded) td {
        background-color: rgba(0, 0, 0, 0.03);
    }
    </style>