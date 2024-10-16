<script>

// resources/js/Components/Fields/Text.svelte

    import { t } from 'svelte-i18n';
    export let form; // Accept the form object as a prop
    export let id = null;
    export let name = null;
    export let attributes = {};
    export let className = '';
    export let label = ''; // Default label for the input field
</script>

<div class="form-floating">
    <input 
        type="text" 
        {...(id ? { id } : {})} 
        {...(name ? { name } : {})} 
        {...(Object.keys(attributes).length ? attributes : {})} 
        bind:value={form[name]}
        placeholder={label} 
        class={`form-control ${className}`}
    >
    <label class="form-label" for={id}>{label}</label>
    {#if form.errors && form.errors[name]}  <!-- Dynamically check for errors on the field -->
        <div class="alert alert-danger field-error">{form.errors[name]}</div>
    {/if}
</div>
