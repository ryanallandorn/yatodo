<script>
    import { createEventDispatcher } from 'svelte';

    export let type = 'button'; // Default: 'button'
    export let disabled = false; // Default: not disabled
    export let cssClass = ''; // Additional CSS classes

    const dispatch = createEventDispatcher();

    // Handle clicks with conditional preventDefault logic
    const handleClick = (event) => {
        // console.log('Button clicked:', type); // For debugging

        if (type !== 'submit') {
            event.preventDefault(); // Prevent default only for non-submit buttons
            //console.log('Default prevented for non-submit button'); // For debugging
        }

        // Dispatch the click event so parent components can listen to it
        dispatch('click', event);
    };
</script>

<button
    type={type}
    class={`btn ${cssClass}`}
    disabled={disabled}
    on:click={handleClick}
>
    <slot></slot> <!-- Render button content -->
</button>
