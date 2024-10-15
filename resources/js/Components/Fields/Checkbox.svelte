<script>

// resources/js/Components/Fields/Checkbox.svelte

    export let id = null;
    export let name = null;
    export let attributes = {};
    export let className = '';
    export let checked = false;
</script>

<input 
    type="checkbox" 
    {...(id ? { id } : {})} 
    {...(name ? { name } : {})} 
    {...(Object.keys(attributes).length ? attributes : {})} 
    class={`rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800 ${className}`}
    bind:checked={checked}
/>
