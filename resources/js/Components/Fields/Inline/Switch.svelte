<script>
    // resources/js/Components/Fields/Inline/Switch.svelte

    import { onMount } from 'svelte';
    import { page } from '@inertiajs/svelte';
    import { debounce } from 'lodash';
    import axios from 'axios';

    // Internal Imports
    import { handleCallbackMessages } from '@scripts/toasts';

    // Props
    export let apiPutRoute; // API route for updates
    export let fieldName; // Name of the field
    export let fieldValue = false; // Current value for the field
    export let label;
    export let id;
    export let fieldInputCss = ''; // CSS for the input field
    export let fieldWrapperCss = ''; // CSS for the wrapper

    let checked = fieldValue; // Initialize 'checked' with 'fieldValue'

    // Debounced update function using Axios
    const debouncedUpdate = debounce(async (newValue) => {
        if (!apiPutRoute || !fieldName) {
            console.error("API route or field name not specified");
            return;
        }

        try {
            console.log('Updating field:', {
                route: apiPutRoute,
                fieldName: fieldName,
                fieldValue: newValue
            });

            // Make the Axios PUT request with the updated field value
            const response = await axios.put(apiPutRoute, {
                [fieldName]: newValue
            }, {
                headers: {
                    'X-CSRF-TOKEN': $page.props.csrf_token
                }
            });

            handleCallbackMessages(response.data);

        } catch (error) {
            console.error('An error occurred during the update:', error);
        }
    }, 300); // Adjust debounce timing as necessary

    // Function to handle the switch change
    function handleSwitchChange() {
        checked = !checked; // Toggle the checked state
        debouncedUpdate(checked);
    }

    // Initialize the checked state with the fieldValue prop
    onMount(() => {
        checked = fieldValue;
    });
</script>

<div class={`form-check form-switch ${fieldWrapperCss}`}>
    <input
        type="checkbox"
        id={id}
        name={fieldName}
        class={`form-check-input ${fieldInputCss}`}
        checked={checked}
        on:change={handleSwitchChange}
    >
    <label class="form-label" for={id}>
        {#if label}{label}{:else}<slot></slot>{/if}
    </label>
</div>
