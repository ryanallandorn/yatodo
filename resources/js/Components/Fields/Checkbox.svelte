<script>

// resources/js/Components/Fields/Text.svelte

    import { t } from 'svelte-i18n';
    export let form; // Accept the form object as a prop
    export let id = null;
    export let name = null;
    export let attributes = {};
    export let fieldInputCss = '';
    export let fieldWrapperCss = '';
    export let label = ''; // Default label for the input field
</script>

<div class="{`form-check ${fieldWrapperCss}`}">
    <input 
        type="checkbox" 
        {...(id ? { id } : {})} 
        {...(name ? { name } : {})} 
        {...(Object.keys(attributes).length ? attributes : {})} 
        bind:checked={form[name]}
        class={`form-check-input ${fieldInputCss}`}
    >
    <label class="form-label" for={id}>
        <slot></slot>
    </label>
    {#if form.errors && form.errors[name]}  <!-- Dynamically check for errors on the field -->
        <div class="alert alert-danger field-error">{form.errors[name]}</div>
    {/if}
</div>