<script>
    // Props equivalent to Blade @props directive
    export let id = 'switch';
    export let name = 'switch';
    export let label = null;
    export let checked = false;
    export let className = '';
    export let required = false;
    export let attributes = {};
</script>

<div class={`form-check form-switch ${className}`}>
    <input 
        class="form-check-input" 
        type="checkbox" 
        role="switch" 
        id={id} 
        name={name} 
        bind:checked={checked} 
        {...attributes} 
    />
    
    {#if label}
        <label class="form-check-label" for={id}>
            {label}
        </label>
    {/if}

    <slot></slot> <!-- Slot for additional content -->
</div>
