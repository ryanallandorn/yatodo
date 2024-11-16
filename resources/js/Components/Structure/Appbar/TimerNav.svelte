<!-- resources/js/Components/Actions/Dropdown.svelte -->

<script>
    import { Inertia } from '@inertiajs/inertia';
    import { mdiTimer } from '@mdi/js';
    import IconMdi from '@components//UI/Icons/Mdi.svelte';

    export let modelId;
    export let wrapperCssClass = ''; 
    export let buttonCssClass = 'btn dropdown-toggle';
    export let dropdownCssClass = 'dropdown-menu'; 

    // Function to navigate to the edit page
    const navigateToEdit = () => {
        Inertia.visit(`/tasks/${modelId}/edit`);
    };

    // Function to handle item deletion
    const deleteItem = () => {
        console.log('Delete item with ID:', modelId);
    };

    // Function to handle item archiving
    const archiveItem = () => {
        console.log('Archive item with ID:', modelId);
    };
</script>

<div class={`dropdown ${wrapperCssClass}`}>
    <button class={`btn dropdown-toggle ${buttonCssClass}`} type="button" data-bs-toggle="dropdown" aria-expanded="false">
        <slot name="buttonContents">
            <IconMdi icon={mdiTimer} />
        </slot>
    </button>
    <div class={`dropdown-menu ${dropdownCssClass}`}>
        <slot name="dropdownContents">
            <!-- Default dropdown content if no slot content is provided -->
            <button class="dropdown-item" on:click={navigateToEdit}>Edit</button>
            <button class="dropdown-item" on:click={deleteItem}>Delete</button>
            <button class="dropdown-item" on:click={archiveItem}>Archive</button>
        </slot>
    </div>
</div>
