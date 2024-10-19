
<script>

// resources/js/Components/SectionTitle.svelte

    export let title = '';
    export let description = '';
</script>

<div class="md:col-span-1 flex justify-between">
    <div class="px-4 sm:px-0">
        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {#if $$slots.title}
                <slot name="title" />
            {:else}
                {title}
            {/if}
        </h3>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
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