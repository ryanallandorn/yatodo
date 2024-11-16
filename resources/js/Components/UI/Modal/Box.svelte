<!-- resources/js/Components/UI/Modal/Box.svelte -->

<script>
    import { createEventDispatcher, onMount } from 'svelte';
    import 'bootstrap/js/dist/modal';
    import { mdiPin } from '@mdi/js';

    import IconMdi from '@components/UI/Icons/Mdi.svelte';
    import ActionsDropdown from '@components/Actions/Dropdown.svelte';
    import SizeDropdown from '@components/UI/Modal/SizeDropdown.svelte';

    let modalElement;
    let bootstrapModal;
    const dispatch = createEventDispatcher();

    export let initialData = null;
    export let fetchUrl = '';
    export let fetchOnLoad = null;
    export let showFooter = true;

    let loading = false;
    let fetchedData = null;

    // Function to open the modal
    export async function open() {
        if (fetchOnLoad) {
            await loadData(fetchOnLoad);
        } else if (fetchUrl) {
            await loadData(() => fetch(fetchUrl).then(res => res.json()));
        }
        bootstrapModal.show();
    }

    // Function to load data asynchronously
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

    // Function to close the modal
    export function close() {
        bootstrapModal.hide();
        dispatch('close');
    }

    // Function to get the modal size from localStorage
    const getStoredModalSize = () => localStorage.getItem('modalSize') || '';

    // Retrieve the stored modal size for the initial class
    let modalSizeClass = getStoredModalSize();

    onMount(() => {
        bootstrapModal = new bootstrap.Modal(modalElement);
    });
</script>

<div
    bind:this={modalElement}
    class={`modal modal-wide fade ${modalSizeClass}`}
    tabindex="-1"
    aria-labelledby="modalLabel"
    aria-hidden="true"
>
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between">
                <h5 class="modal-title me-auto" id="modalLabel">
                    <slot name="title">Default Title</slot>
                </h5>
                <div class="btn-group ms-auto d-flex align-items-center">
                    <IconMdi icon={mdiPin} />
                    <ActionsDropdown wrapperCssClass="ms-2">
                        <span slot="buttonContents">
                            <i class="bi bi-sliders2-vertical"></i>
                        </span>
                        <div slot="dropdownContents">
                            <!-- Pass modalElement to SizeDropdown -->
                            <SizeDropdown {modalElement} />
                        </div>
                    </ActionsDropdown>
                    <button type="button" class="btn-close ms-2" aria-label="Close" on:click={close}></button>
                </div>
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
            {#if showFooter}
            <div class="modal-footer">
                <slot name="footer">
                    <button type="button" class="btn btn-secondary" on:click={close}>Close</button>
                </slot>
            </div>
            {/if}
        </div>
    </div>
</div>
