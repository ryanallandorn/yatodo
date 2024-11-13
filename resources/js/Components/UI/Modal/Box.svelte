
<script>

// esources/js/Components/Modal.svelte 

    import { createEventDispatcher, onMount } from 'svelte';
    import 'bootstrap/js/dist/modal'; // Import Bootstrap's modal JS

    let modalElement;
    let bootstrapModal;
    const dispatch = createEventDispatcher();

    export let initialData = null;  // Optional initial data
    export let fetchUrl = '';       // Optional API endpoint
    export let fetchOnLoad;         // Optional function to fetch data

    let loading = false;
    let fetchedData = null;

    export async function open() {
        if (fetchOnLoad) {
            await loadData(fetchOnLoad);
        } else if (fetchUrl) {
            await loadData(() => fetch(fetchUrl).then(res => res.json()));
        }
        bootstrapModal.show();
    }

    async function loadData(fetchFunction) {
        loading = true;
        try {
            fetchedData = await fetchFunction();
        } catch (error) {
            console.error('Error fetching data:', error);
        } finally {
            loading = false;
        }
    }

    export function close() {
        bootstrapModal.hide();
        dispatch('close'); // Emit a custom 'close' event
    }

    onMount(() => {
        bootstrapModal = new bootstrap.Modal(modalElement);
    });
</script>

<div
    bind:this={modalElement}
    class="modal fade"
    tabindex="-1"
    aria-labelledby="exampleModalLabel"
    aria-hidden="true"
>
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">
                    <slot name="title">Default Title</slot>
                </h5>
                <button type="button" class="btn-close" aria-label="Close" on:click={close}></button>
            </div>
            <div class="modal-body">
                {#if initialData}
                    <div>
                        <h6>Initial Data</h6>
                        <pre>{JSON.stringify(initialData, null, 2)}</pre>
                    </div>
                {/if}

                {#if loading}
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                {:else if fetchedData}
                    <div>
                        <h6>Fetched Data</h6>
                        <pre>{JSON.stringify(fetchedData, null, 2)}</pre>
                    </div>
                {:else if fetchUrl || fetchOnLoad}
                    <p>No data available.</p>
                {/if}

                <!-- Render additional static content -->
                <slot name="staticContent" />
            </div>
            <div class="modal-footer">
                <slot name="footer">
                    <button type="button" class="btn btn-secondary" on:click={close}>Close</button>
                </slot>
            </div>
        </div>
    </div>
</div>

<style>
    /* Optional: Add custom modal styling */
</style>
