<script>

    // resources/js/Components/UI/Datatable/Controls/PageLength.svelte

    import { t } from 'svelte-i18n';
    import { createEventDispatcher } from 'svelte';

    export let limit;
    export let options = [10, 25, 50, 100]; // Default options for page length

    const dispatch = createEventDispatcher();

    const handlePageLengthChange = (event) => {
        const newLimit = parseInt(event.target.value, 10);
        dispatch('change', newLimit); // Dispatch the custom 'change' event
    };
</script>

<div class="d-flex input-group input-group-sm w-auto h-auto align-items-center">
    <select
        id="pageLengthSelect"
        class="form-select"
        bind:value={limit}
        on:change={handlePageLengthChange}
    >
        {#each options as option}
            <option value={option}>{option}</option>
        {/each}
    </select>
    <label for="pageLengthSelect" class="input-group-text">{$t('Item(s)')}</label>
</div>
