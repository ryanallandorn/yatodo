<script>
    // resources/js/Components/UI/DynamicTypeaheadSelect.svelte
    
    import { onMount } from 'svelte';
    
    export let source = '';
    export let filters = {};
    export let inputClass = '';
    export let inputPlaceholder = 'Search...';
    export let name = '';
    export let label = '';
    export let storedValue = '';
    export let displayValue = '';
    export let searchFields = 'name';  // New prop for search fields
    export let offsetX = 0;            // Horizontal offset for dropdown
    export let offsetY = 0;            // Vertical offset for dropdown
    
    let inputValue = displayValue;
    let results = [];
    let loading = false;
    let showDropdown = false;
    
    const fetchResults = async () => {
        loading = true;
        
        const query = new URLSearchParams({
            ...filters,
            searchQuery: inputValue,
            searchFields,        // Use the `searchFields` prop here
            limit: 10,
            page: 1
        });
    
        const response = await fetch(`${source}?${query}`);
        const data = await response.json();
        
        results = data.items || [];
        showDropdown = results.length > 0;
    
        loading = false;
    };
    
    const handleInputChange = (event) => {
        inputValue = event.target.value;
        if (inputValue.length >= 2) {
            fetchResults();
        } else {
            results = [];
            showDropdown = false;
        }
    };
    
    const handleSelectResult = (result) => {
        inputValue = result.name;
        storedValue = result.id;
        showDropdown = false;
    };
    
    const handleCancel = (event) => {
        event.preventDefault();
        inputValue = '';
        storedValue = '';
        showDropdown = false;
        results = [];
    };
    </script>
    

<div class="position-relative dropdown">
    <div class="form-floating form-group">
        <input
            type="text"
            class={`form-control ${inputClass} field-typeahead`}
            placeholder={inputPlaceholder}
            autocomplete="off"
            bind:value={inputValue}
            on:input={handleInputChange}
        />
        <input type="hidden" name={name} bind:value={storedValue} />

        <label for={name}>{label}</label>

        <!-- Cancel Button -->
        <button id="cancel-button" class="btn btn-sm btn-danger position-absolute end-0 top-0 mt-2 me-2" on:click={handleCancel} style="display: {showDropdown ? 'block' : 'none'};">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>

    <!-- Dropdown with dynamic results -->
    {#if showDropdown}
        <div 
            class="dropdown-menu show w-100 p-0 shadow" 
            style="position: absolute; z-index: 1000; top: calc(100% + {offsetY}px); left: {offsetX}px;"
        >
            {#each results as result}
                <div class="dropdown-item" on:click={() => handleSelectResult(result)}>
                    {result.name}
                </div>
            {/each}
        </div>
    {/if}

    <!-- Loading Spinner -->
    {#if loading}
        <div class="position-absolute spinner-border text-primary" role="status" style="right: 10px; top: 8px;">
            <span class="visually-hidden">Loading...</span>
        </div>
    {/if}
</div>
