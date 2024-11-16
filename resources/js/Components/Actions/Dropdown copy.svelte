<script>

    // resources/js/Components/Actions/Dropdown.svelte
    
        import { Inertia } from '@inertiajs/inertia';
    
        export let modelId;
    
        const navigateToEdit = () => {
            Inertia.visit(`/tasks/${modelId}/edit`);
        };
    
        const deleteItem = () => {
            // Handle delete logic here, e.g., by showing a modal or making a delete request
            console.log('Delete item with ID:', modelId);
        };
    
        const archiveItem = () => {
            // Handle archive logic here
            console.log('Archive item with ID:', modelId);
        };
    </script>
    
    <div class="dropdown">
        <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-three-dots-vertical"></i>
        </button>
        <ul class="dropdown-menu">
            <li><button class="dropdown-item" on:click={navigateToEdit}>Edit</button></li>
            <li><button class="dropdown-item" on:click={deleteItem}>Delete</button></li>
            <li><button class="dropdown-item" on:click={archiveItem}>Archive</button></li>
        </ul>
    </div>
    