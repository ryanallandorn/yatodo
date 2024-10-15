<script>
    // resources/js/Components/Forms/ValidationErrors.svelte
    export let errors = []; // Accept the errors array as a prop
</script>

{#if errors.length > 0}
    <div class="alert alert-danger">
        <div>Whoops! Something went wrong.</div>
        <ul class="m-0 mt-1">
            {#each errors as error}
                <li>{error}</li>
            {/each}
        </ul>
    </div>
{/if}
