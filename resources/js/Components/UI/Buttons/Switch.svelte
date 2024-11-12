<script>

// resources/js/Components/UI/Buttons/Switch.svelte

    import { t } from 'svelte-i18n';
    export let id = null;
    export let attributes = {};
    export let fieldInputCss = '';
    export let fieldWrapperCss = '';
    export let label = ''; // Default label for the input field

    // New prop: a function that will be called when the switch is toggled
    export let onToggle = null;

    // Function to handle the toggle action
    function handleToggle(event) {
        if (onToggle && typeof onToggle === 'function') {
            onToggle(event);
        }
    }

</script>

<div class="form-check form-switch btn-switch">
    <input 
        type="checkbox" 
        {...(id ? { id } : {})} 
        {...(name ? { name } : {})} 
        {...(Object.keys(attributes).length ? attributes : {})} 
        class={`form-check-input ${fieldInputCss}`}
        on:change={handleToggle}
    >
    <label class="form-check-label" for="flexSwitchCheckDefault">
        <slot></slot>
    </label>
</div>