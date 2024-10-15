<script>
    // resources/js/Components/UI/Buttons/Button.svelte
    
        export let type = 'button'; // Default to 'submit'
        export let disabled = false; // Default disabled state
        export let className = ''; // Allow passing in additional classes
    </script>
    
    <button 
        type={type} 
        class={`btn ${className}`} 
        disabled={disabled}
    >
        <slot></slot> <!-- Use slot for button content -->
    </button>
    