<script>
// resources/js/Components/Fields/MultiLine.svelte

    import { t } from 'svelte-i18n';
    export let form; // Accept the form object as a prop
    export let id = null;
    export let name = null;
    export let attributes = {};
    export let fieldInputCss = '';
    export let fieldWrapperCss = '';
    export let label = ''; // Default label for the input field
</script>

<div class="{`form-floating ${fieldWrapperCss}`}">
    <textarea
        {...(id ? { id } : {})} 
        {...(name ? { name } : {})} 
        {...(Object.keys(attributes).length ? attributes : {})} 
        bind:value={form[name]} 
        placeholder={label} 
        class={`form-control ${fieldInputCss}`}
    ></textarea> <!-- Keep this empty -->
    <label class="form-label" for={id}>{label}</label>
    
    {#if form.errors && form.errors[name]}  <!-- Check for errors and display -->
        <div class="alert alert-danger field-error">{form.errors[name]}</div>
    {/if}
</div>
