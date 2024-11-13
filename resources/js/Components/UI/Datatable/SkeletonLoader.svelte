<script>

// resources/js/Components/UI/Datatable/SkeletonLoader.svelte

    export let columns = []; // Array of column definitions
    export let limit = 10; // Number of rows to display
</script>

{#each Array(limit).fill() as _}
    <tr>
        {#each columns as column}
            <td>
                <span class="placeholder-glow opacity-50">
                    <span class="placeholder col-12 placeholder-lg"></span>
                </span>
            </td>
        {/each}
    </tr>
{/each}
