<script>
    // resources/js/Pages/Elements/Tasks/Show/Modal.svelte
    
    import ModalBox from '@components/UI/Modal/Box.svelte';
    export let apiGetRoute = null;
    export let displaySlot = '';
    export let modalTitle = '';
    
    let modal;
    
    const openModal = () => {
        modal.open(); // Call `open` directly on the modal instance
    };
    </script>

    <!-- Trigger to open the modal, displaying `displaySlot` content -->
    <span on:click={openModal} class="cursor-pointer text-primary">
        {displaySlot}
    </span>
    
    <!-- Modal to display fetched data -->
    <ModalBox bind:this={modal} fetchUrl={apiGetRoute}>
        <span slot="title">{modalTitle}</span>
    
        <div slot="staticContent">
            <!-- Custom content to display fetched data will be handled inside ModalBox -->
        </div>
    
        <div slot="footer">
            <button type="button" class="btn btn-secondary" on:click={() => modal.close()}>
                Close
            </button>
        </div>
    </ModalBox>
    