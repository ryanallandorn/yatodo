<script>
    import { onMount } from 'svelte';
    import axios from 'axios';
    import Pagination from './Pagination.svelte';

    export let apiUrl;
    export let columns = [];

    let items = [];
    let loading = true;
    let total = 0;
    let limit = 10;
    let currentPage = 1;

    async function fetchData(page = 1) {
        loading = true;
        try {
            const response = await axios.get(apiUrl, {
                params: {
                    page,
                    limit,
                }
            });
            items = response.data.items;
            total = response.data.total;
            currentPage = page;
        } catch (error) {
            console.error("Error fetching data:", error);
        } finally {
            loading = false;
        }
    }

    onMount(() => {
        fetchData(currentPage);
    });

    const handlePageSizeChange = (event) => {
        limit = parseInt(event.target.value);
        currentPage = 1;
        fetchData(currentPage);
    }

    const handlePageChange = (page) => {
        fetchData(page);
    }
</script>

{#if loading}
    <p>Loading...</p>
{:else}
    <table class="table">
        <thead>
            <tr>
                {#each columns as column}
                    <th>{column.label}</th>
                {/each}
            </tr>
        </thead>
        <tbody>
            {#each items as item}
                <tr>
                    {#each columns as column}
                        <td>
                            {#if column.render?.component}
                                <!-- Render component with dynamic props -->
                                <svelte:component
                                    this={column.render.component}
                                    {...Object.fromEntries(Object.entries(column.render.props || {}).map(([key, value]) => [key, typeof value === 'function' ? value(item) : value]))}
                                />
                            {:else}
                                {item[column.key]}
                            {/if}
                        </td>
                    {/each}
                </tr>
            {/each}
        </tbody>
    </table>

    <Pagination
        {currentPage}
        {total}
        {limit}
        onPageChange={handlePageChange}
    />
{/if}
