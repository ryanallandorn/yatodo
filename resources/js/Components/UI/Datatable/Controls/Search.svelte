<script>

// resources/js/Components/UI/Datatable/Controls/Search.svelte

    export let searchQuery;
    export let onEnter; // Function to call when Enter is pressed
    export let onButtonClick; // Function to call on button click
    export let trigger = 'onEnter'; // Default to 'onEnter'

    const handleKeyPress = (event) => {
        if (event.key === 'Enter' && trigger === 'onEnter') {
            onEnter();
        }
    };
</script>

<div class="form-floating mb-3">
    <input 
        type="text" 
        placeholder="Search..." 
        class="form-control"
        name="searchQuery" 
        bind:value={searchQuery} 
        on:keypress={handleKeyPress}
    />
    <label for="floatingInput">Search</label>

    {#if trigger === 'onButtonClick'}
        <button class="btn btn-primary" on:click={onButtonClick}>Search</button>
    {/if}
</div>
