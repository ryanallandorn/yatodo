<script>

// resources/js/Components/UI/Accordion/Accordion.svelte

    export let id;
    export let cssClass = '';
</script>

<div class={`accordion ${cssClass}`} id={id}>
    <slot></slot>
</div>