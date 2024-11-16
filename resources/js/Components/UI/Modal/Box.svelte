<script>

// resources/js/Components/UI/Modal/Box.svelte

    import { createEventDispatcher, onMount } from 'svelte';
    import 'bootstrap/js/dist/modal';

    let modalElement;
    let bootstrapModal;
    const dispatch = createEventDispatcher();

    export let initialData = null;
    export let fetchUrl = '';
    export let fetchOnLoad = null;

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
            dispatch('dataLoaded', fetchedData);
        } catch (error) {
            console.error('Error fetching data:', error);
            dispatch('error', error);
        } finally {
            loading = false;
        }
    }

    export function close() {
        bootstrapModal.hide();
        dispatch('close');
    }

    onMount(() => {
        bootstrapModal = new bootstrap.Modal(modalElement);
    });
</script>

<div
    bind:this={modalElement}
    class="modal modal-wide fade"
    tabindex="-1"
    aria-labelledby="modalLabel"
    aria-hidden="true"
>
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">
                    <slot name="title">Default Title</slot>
                </h5>
                <button type="button" class="btn-close" aria-label="Close" on:click={close}></button>
            </div>
            <div class="modal-body">
                {#if loading}
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                {:else}
                    <slot name="dynamicContent" {fetchedData} {initialData} />
                    <slot name="staticContent" {fetchedData} {initialData} />
                {/if}
            </div>
            <div class="modal-footer">
                <slot name="footer">
                    <button type="button" class="btn btn-secondary" on:click={close}>Close</button>
                </slot>
            </div>
        </div>
    </div>
</div>