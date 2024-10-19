<script>

// resources/js/Components/DialogModal.svelte

    import Modal from '@components/Modal.svelte'; // Import your base modal
    import { createEventDispatcher, onMount } from 'svelte'; 

    export let show = false; // Control visibility
    export let maxWidth = 'modal-xl'; // Bootstrap-compatible width
    export let closeable = true; // Whether the modal is closeable

    let modal; // Modal instance reference

    const dispatch = createEventDispatcher(); // For emitting events

    function close() {
        dispatch('close'); // Emit close event to parent
    }

    // Optional: If you need to open the modal programmatically
    export function openModal() {
        modal?.open();
    }

    export function closeModal() {
        modal?.close();
    }

    onMount(() => {
        if (show) modal?.open(); // Open modal if `show` is initially true
    });
</script>

<Modal 
    bind:this={modal}
    {show} 
    {maxWidth} 
    {closeable} 
    on:close={close}
>
    <span slot="title">
        <slot name="title" />
    </span>

    <div slot="staticContent">
        <slot name="staticContent" />
    </div>

    <div slot="footer">
        <slot name="footer" />
    </div>
</Modal>
