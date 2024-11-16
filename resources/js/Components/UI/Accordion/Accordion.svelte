<script>

// resources/js/Components/UI/Accordion/Accordion.svelte

    export let id;
</script>

<div class="accordion" id={id}>
    <slot></slot>
</div>