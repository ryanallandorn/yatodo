<!-- AccordionItem.svelte -->
<script>
    export let headerId;
    export let collapseId;
    export let isOpen = false;
</script>

<div class="accordion-item">
    <h2 class="accordion-header" id={headerId}>
        <button
            class="accordion-button {isOpen ? '' : 'collapsed'}"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target={"#" + collapseId}
            aria-expanded={isOpen ? 'true' : 'false'}
            aria-controls={collapseId}
        >
            <slot name="header"></slot>
        </button>
    </h2>
    <div
        id={collapseId}
        class="accordion-collapse collapse {isOpen ? 'show' : ''}"
        data-bs-parent={"#" + headerId}
    >
        <div class="accordion-body">
            <slot name="body"></slot>
        </div>
    </div>
</div>
