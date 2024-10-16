<script>

// resources/js/Components/Forms/ActionMessage.svelte


    import { onMount, createEventDispatcher } from 'svelte';
    import { fade } from 'svelte/transition';
    import { tick } from 'svelte';

    export let on; // The event name to listen for
    export let className = 'text-sm text-gray-600 dark:text-gray-400';
    let shown = false;
    let timeout;

    const dispatch = createEventDispatcher();

    // Listen for the custom event and manage the visibility
    onMount(() => {
        const handler = () => {
            clearTimeout(timeout);
            shown = true;
            tick().then(() => {
                timeout = setTimeout(() => (shown = false), 2000);
            });
        };

        // Register the event listener
        dispatch(on, handler);

        // Cleanup the listener when the component is destroyed
        return () => clearTimeout(timeout);
    });
</script>

{#if shown}
    <div
        class={className}
        style="display: none;"
        in:fade={{ duration: 1500 }}
        out:fade={{ duration: 1500 }}
    >
        <slot>Saved.</slot>
    </div>
{/if}
