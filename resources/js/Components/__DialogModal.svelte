<script>
    import ModalBox from '@components/UI/Modal/Box.svelte';
    import { createEventDispatcher, onMount } from 'svelte'; 

    export let show = false;
    export let maxWidth = 'modal-xl';
    export let closeable = true;

    let modal;
    const dispatch = createEventDispatcher();

    $: modalClass = {
        'sm': 'modal-sm',
        'md': 'modal-md',
        'lg': 'modal-lg',
        'xl': 'modal-xl',
        '2xl': 'modal-xxl',
    }[maxWidth] || 'modal-xl';

    function close() {
        dispatch('close');
    }

    export function openModal() {
        modal?.open();
    }

    export function closeModal() {
        modal?.close();
    }

    // Watch for show changes
    $: if (modal) {
        if (show) {
            openModal();
        } else {
            closeModal();
        }
    }

    onMount(() => {
        if (show) openModal();
    });
</script>

<ModalBox
    bind:this={modal}
    maxWidth={modalClass}
    {closeable}
    on:close={close}
>
    <div slot="title">
        <slot name="title" />
    </div>

    <div slot="staticContent">
        <div class="px-6 py-4">
            <div class="mt-4 text-sm text-gray-600 dark:text-gray-400">
                <slot name="content" />
            </div>
        </div>
    </div>

    <div slot="footer">
        <div class="d-flex justify-content-end px-6 py-4 bg-gray-100 dark:bg-gray-800">
            <slot name="footer" />
        </div>
    </div>
</ModalBox>