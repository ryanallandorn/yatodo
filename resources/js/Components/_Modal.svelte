<script>
// resources/js/Components/Modal.svelte

    import { onMount, onDestroy, tick } from 'svelte';
    import { writable } from 'svelte/store';

    export let show = false;
    export let maxWidth = 'modal-xl'; // Bootstrap width class
    export let closeable = true;

    let modalRef;
    const modalInstance = writable(null); // Store for Bootstrap modal instance

    // Handle body overflow to prevent scrolling when modal is open
    function handleBodyOverflow(enabled) {
        document.body.style.overflow = enabled ? 'hidden' : null;
    }

    function close() {
        if (closeable) {
            dispatchEvent(new CustomEvent('close'));
        }
    }

    function closeOnEscape(e) {
        if (e.key === 'Escape' && show) {
            e.preventDefault();
            close();
        }
    }

    $: if (show) {
        handleBodyOverflow(true);
        tick().then(() => {
            const instance = new bootstrap.Modal(modalRef);
            modalInstance.set(instance);
            instance.show();
        });
    } else {
        handleBodyOverflow(false);
        setTimeout(() => {
            modalInstance.subscribe((instance) => instance?.hide());
        }, 200);
    }

    onMount(() => {
        document.addEventListener('keydown', closeOnEscape);
    });

    onDestroy(() => {
        document.removeEventListener('keydown', closeOnEscape);
        handleBodyOverflow(false);
    });
</script>

<!-- Bootstrap 5.3 Modal Structure -->
<div
    class="modal fade"
    tabindex="-1"
    bind:this={modalRef}
    aria-hidden="true"
>
    <div class="modal-dialog {maxWidth}">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <slot name="title" />
                </h5>
                {#if closeable}
                    <button
                        type="button"
                        class="btn-close"
                        aria-label="Close"
                        on:click={close}
                    ></button>
                {/if}
            </div>

            <div class="modal-body">
                <slot  name="content"  />
            </div>

            <div class="modal-footer">
                <slot name="footer" />
            </div>
        </div>
    </div>
</div>
