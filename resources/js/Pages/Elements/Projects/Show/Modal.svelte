<script>
    // resources/js/Pages/Elements/Projects/Show/Modal.svelte
    
    import ModalBox from '@components/UI/Modal/Box.svelte';
    import { onMount } from 'svelte';
    import { writable } from 'svelte/store';
    
    export let apiGetRoute = null;
    let modal;
    let itemData = writable(null);
    
    const openModal = async () => {
        try {
            const response = await fetch(apiGetRoute);
            const data = await response.json();
            itemData.set(data); // Update the store with the fetched data
            modal.open();
        } catch (error) {
            console.error("Error fetching data:", error);
        }
    };
    </script>
    
    <!-- Trigger to open the modal -->
    <span on:click={openModal} class="cursor-pointer text-primary">
        <slot name="displaySlot"></slot>
    </span>
    
    <!-- Modal to display fetched data -->
    <ModalBox bind:this={modal}>
        <span slot="title">Item Details</span>
    
        <div slot="staticContent">
            {#if $itemData}
                <p><strong>ID:</strong> {$itemData.id}</p>
                <p><strong>Name:</strong> {$itemData.name}</p>
                <!-- Add other fields as needed -->
            {:else}
                <p>Loading...</p>
            {/if}
        </div>
    
        <div slot="footer">
            <button type="button" class="btn btn-secondary" on:click={() => modal.close()}>
                Close
            </button>
        </div>
    </ModalBox>
    