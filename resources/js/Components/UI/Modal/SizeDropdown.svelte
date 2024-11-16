<script>
    import { t } from 'svelte-i18n';
    import { onMount } from 'svelte';

    export let modalElement; // Receive the modal element as a prop

    // State to track the current modal size
    let currentModalSize = '';

    // Function to get the modal size from localStorage
    const getStoredModalSize = () => localStorage.getItem('modalSize') || '';

    // Function to set the modal size in localStorage
    const setStoredModalSize = (size) => localStorage.setItem('modalSize', size);

    // Function to set the modal size and update the DOM
    const setModalSize = (size) => {
        if (!modalElement) return; // Ensure modalElement is available
        const modalDialog = modalElement.querySelector('.modal-dialog');
        modalDialog.classList.remove('modal-sm', 'modal-lg', 'modal-xl'); // Remove existing size classes
        if (size) {
            modalDialog.classList.add(size); // Add the new size class
        }
        currentModalSize = size; // Update the current modal size
        setStoredModalSize(size); // Store the size in localStorage
    };

    // Initialize the modal size on component mount
    onMount(() => {
        currentModalSize = getStoredModalSize(); // Get the stored size
        setModalSize(currentModalSize); // Set the modal size from localStorage
    });
</script>

<div class="dropdown-item">
    <label class="me-2">
        {$t('Modal Size')}
    </label>
    <div class="btn-group">
        <button
            class="btn btn-outline-secondary btn-sm {currentModalSize === 'modal-sm' ? 'active' : ''}"
            on:click={() => setModalSize('modal-sm')}
        >
            S
        </button>
        <button
            class="btn btn-outline-secondary btn-sm {currentModalSize === '' ? 'active' : ''}"
            on:click={() => setModalSize('')}
        >
            M
        </button>
        <button
            class="btn btn-outline-secondary btn-sm {currentModalSize === 'modal-lg' ? 'active' : ''}"
            on:click={() => setModalSize('modal-lg')}
        >
            L
        </button>
        <button
            class="btn btn-outline-secondary btn-sm {currentModalSize === 'modal-xl' ? 'active' : ''}"
            on:click={() => setModalSize('modal-xl')}
        >
            XL
        </button>
    </div>
</div>
