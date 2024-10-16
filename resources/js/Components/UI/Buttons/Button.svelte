<script>
    // resources/js/Components/UI/Buttons/Button.svelte

    export let type = 'button'; // Default to 'button'
    export let disabled = false; // Default disabled state
    export let cssClass = ''; // Use 'class' directly to allow additional classes

    // Use $$restProps directly in the button with spread syntax
</script>

<button
    type={type}
    class={`btn ${cssClass}`}
    disabled={disabled}
    {...$$restProps}
>
    <slot></slot> <!-- Button content goes here -->
</button>
