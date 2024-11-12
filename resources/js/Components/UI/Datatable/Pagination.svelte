<script>
    export let currentPage;
    export let total;
    export let limit;
    export let onPageChange;

    // Calculate total pages
    const totalPages = () => Math.ceil(total / limit);

    // Navigate to a specific page
    const goToPage = (page) => {
        if (page > 0 && page <= totalPages()) {
            onPageChange(page);
        }
    };

    // Navigate to the next page
    const goToNextPage = () => {
        if (currentPage < totalPages()) {
            goToPage(currentPage + 1);
        }
    };

    // Navigate to the previous page
    const goToPreviousPage = () => {
        if (currentPage > 1) {
            goToPage(currentPage - 1);
        }
    };
</script>

<nav class="pagination-controls" aria-label="Page navigation example">
    <ul class="pagination">
        <li class="page-item">
            <button class="page-link" on:click={goToPreviousPage} disabled={currentPage === 1}>
                Previous
            </button>
        </li>

        {#each Array(totalPages()).fill(0).map((_, i) => i + 1) as page}
            <li class="page-item {page === currentPage ? 'active' : ''}">
                <button class="page-link" on:click={() => goToPage(page)}>
                    {page}
                </button>
            </li>
        {/each}

        <li class="page-item">
            <button class="page-link" on:click={goToNextPage} disabled={currentPage >= totalPages()}>
                Next
            </button>
        </li>
    </ul>
</nav>
