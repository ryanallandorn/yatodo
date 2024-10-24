
<script>

// resources/js/Components/SectionTitle.svelte

    export let title = '';
    export let description = '';
</script>

<div class="md:col-span-1 flex justify-between">
    <div class="px-4 sm:px-0">
        <h3 class="h5">
            {#if $$slots.title}
                <slot name="title" />
            {:else}
                {title}
            {/if}
        </h3>

        <p class="mt-1 fw-light fs-7">
            {#if $$slots.description}
                <slot name="description" />
            {:else}
                {description}
            {/if}
        </p>
    </div>

    <div class="px-4 sm:px-0">
        <slot name="aside" />
    </div>
</div>