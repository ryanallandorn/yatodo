<script>

// resources/js/Components/UI/Datatable/Filters/Switch.svelte

    import { createEventDispatcher } from 'svelte';

    export let label;
    export let value = false; // Default value for the switch
    const dispatch = createEventDispatcher();

    // Handle change event
    const handleChange = (event) => {
        value = event.target.checked;
        dispatch('change', { value });
    };
</script>

<div class="form-check form-switch">
    <input
        type="checkbox"
        class="form-check-input"
        bind:checked={value}
        on:change={handleChange}
    />
    <label class="form-check-label">{label}</label>
</div>
