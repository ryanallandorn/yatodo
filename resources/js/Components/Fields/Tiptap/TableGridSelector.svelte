<script>
    export let editor;
    
    let isVisible = false;
    let hoveredCell = { row: 0, col: 0 };
    let maxRows = 8;
    let maxCols = 8;

    function handleMouseEnter(row, col) {
        hoveredCell = { row, col };
    }

    function handleClick() {
        editor.chain().focus().insertTable({ 
            rows: hoveredCell.row + 1, 
            cols: hoveredCell.col + 1, 
            withHeaderRow: true 
        }).run();
        isVisible = false;
    }

    function toggleVisible() {
        isVisible = !isVisible;
    }

    // Create arrays for grid generation
    $: rows = Array(maxRows).fill(0);
    $: cols = Array(maxCols).fill(0);
</script>

<div class="relative inline-block">
    <button
        on:click={toggleVisible}
        class="btn btn-outline-secondary"
        data-bs-toggle="tooltip"
        title="Insert table"
    >
        <i class="bi bi-table"></i>
    </button>
    
    {#if isVisible}
        <div class="absolute left-0 top-full mt-2 bg-white rounded shadow-lg border p-4 z-50">
            <div class="mb-2 text-sm text-secondary">
                {hoveredCell.row + 1} x {hoveredCell.col + 1} Table
            </div>
            <div 
                class="grid gap-1" 
                style="grid-template-columns: repeat({maxCols}, 1.5rem);"
            >
                {#each rows as _, rowIndex}
                    {#each cols as _, colIndex}
                        <div
                            class="cell"
                            class:active={rowIndex <= hoveredCell.row && colIndex <= hoveredCell.col}
                            on:mouseenter={() => handleMouseEnter(rowIndex, colIndex)}
                            on:click={handleClick}
                        />
                    {/each}
                {/each}
            </div>
        </div>
    {/if}
</div>

<style>
    .cell {
        width: 1.5rem;
        height: 1.5rem;
        border: 1px solid #dee2e6;
        cursor: pointer;
        transition: all 0.15s ease-in-out;
    }

    .cell:hover {
        border-color: var(--bs-primary);
    }

    .cell.active {
        background-color: var(--bs-primary-bg-subtle);
        border-color: var(--bs-primary);
    }

    .absolute {
        position: absolute;
    }

    .relative {
        position: relative;
    }

    .grid {
        display: grid;
    }

    .z-50 {
        z-index: 50;
    }
</style>