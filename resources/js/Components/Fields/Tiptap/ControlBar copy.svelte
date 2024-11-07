<script>
    import { onMount } from 'svelte';

    export let editor;

    // Initialize tooltips on mount
    onMount(() => {
        const tooltipTriggerList = [...document.querySelectorAll('[data-bs-toggle="tooltip"]')];
        tooltipTriggerList.forEach((tooltipTriggerEl) => {
            new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });

    // Prevent dropdown from closing on item click
    const preventClose = (event) => event.stopPropagation();

    // Table operation functions
    const insertTable = () => editor.chain().focus().insertTable({ rows: 3, cols: 3, withHeaderRow: true }).run();
    const insertHTMLTable = () => editor.chain().focus().insertContent('<table><tr><td></td></tr></table>').run();
    const addColumnBefore = () => editor.chain().focus().addColumnBefore().run();
    const addColumnAfter = () => editor.chain().focus().addColumnAfter().run();
    const deleteColumn = () => editor.chain().focus().deleteColumn().run();
    const addRowBefore = () => editor.chain().focus().addRowBefore().run();
    const addRowAfter = () => editor.chain().focus().addRowAfter().run();
    const deleteRow = () => editor.chain().focus().deleteRow().run();
    const deleteTable = () => editor.chain().focus().deleteTable().run();
    const mergeCells = () => editor.chain().focus().mergeCells().run();
    const splitCell = () => editor.chain().focus().splitCell().run();
    const toggleHeaderColumn = () => editor.chain().focus().toggleHeaderColumn().run();
    const toggleHeaderRow = () => editor.chain().focus().toggleHeaderRow().run();
    const toggleHeaderCell = () => editor.chain().focus().toggleHeaderCell().run();
    const mergeOrSplit = () => editor.chain().focus().mergeOrSplit().run();
    const setCellBackground = () => editor.chain().focus().setCellAttribute('backgroundColor', '#FAF594').run();
    const fixTables = () => editor.chain().focus().fixTables().run();
    const goToNextCell = () => editor.chain().focus().goToNextCell().run();
    const goToPreviousCell = () => editor.chain().focus().goToPreviousCell().run();
</script>

<div class="btn-toolbar" role="toolbar">
    <div class="btn-group me-2" role="group">
        <button
            on:click={() => toggleHeading(1)}
            class="btn btn-outline-secondary"
            data-bs-toggle="tooltip"
            title="Heading 1"
        >
            <i class="bi bi-type-h1"></i>
        </button>
        <button
            on:click={() => toggleHeading(2)}
            class="btn btn-outline-secondary"
            data-bs-toggle="tooltip"
            title="Heading 2"
        >
            <i class="bi bi-type-h2"></i>
        </button>
        <button
            on:click={setParagraph}
            class="btn btn-outline-secondary"
            data-bs-toggle="tooltip"
            title="Paragraph"
        >
            <i class="bi bi-paragraph"></i>
        </button>
    </div>

    <div class="dropdown">
        <button
            class="btn btn-outline-primary dropdown-toggle"
            type="button"
            id="tableDropdown"
            data-bs-toggle="dropdown"
            aria-expanded="false"
        >
            <i class="bi bi-table"></i> Table Actions
        </button>
        <ul class="dropdown-menu" aria-labelledby="tableDropdown" on:click={preventClose}>
            <li>
                <button class="dropdown-item" on:click={insertTable}>Insert Table</button>
            </li>
            <li>
                <button class="dropdown-item" on:click={insertHTMLTable}>Insert HTML Table</button>
            </li>
            <li><hr class="dropdown-divider" /></li>
            <li>
                <button class="dropdown-item" on:click={addColumnBefore}>Add Column Before</button>
            </li>
            <li>
                <button class="dropdown-item" on:click={addColumnAfter}>Add Column After</button>
            </li>
            <li>
                <button class="dropdown-item" on:click={deleteColumn}>Delete Column</button>
            </li>
            <li><hr class="dropdown-divider" /></li>
            <li>
                <button class="dropdown-item" on:click={addRowBefore}>Add Row Before</button>
            </li>
            <li>
                <button class="dropdown-item" on:click={addRowAfter}>Add Row After</button>
            </li>
            <li>
                <button class="dropdown-item" on:click={deleteRow}>Delete Row</button>
            </li>
            <li><hr class="dropdown-divider" /></li>
            <li>
                <button class="dropdown-item" on:click={mergeCells}>Merge Cells</button>
            </li>
            <li>
                <button class="dropdown-item" on:click={splitCell}>Split Cell</button>
            </li>
            <li><hr class="dropdown-divider" /></li>
            <li>
                <button class="dropdown-item" on:click={toggleHeaderColumn}>Toggle Header Column</button>
            </li>
            <li>
                <button class="dropdown-item" on:click={toggleHeaderRow}>Toggle Header Row</button>
            </li>
            <li>
                <button class="dropdown-item" on:click={toggleHeaderCell}>Toggle Header Cell</button>
            </li>
            <li><hr class="dropdown-divider" /></li>
            <li>
                <button class="dropdown-item" on:click={mergeOrSplit}>Merge or Split</button>
            </li>
            <li>
                <button class="dropdown-item" on:click={setCellBackground}>Set Cell Background</button>
            </li>
            <li>
                <button class="dropdown-item" on:click={fixTables}>Fix Tables</button>
            </li>
            <li><hr class="dropdown-divider" /></li>
            <li>
                <button class="dropdown-item" on:click={goToNextCell}>Go to Next Cell</button>
            </li>
            <li>
                <button class="dropdown-item" on:click={goToPreviousCell}>Go to Previous Cell</button>
            </li>
        </ul>
    </div>
</div>

<style>
    .btn-toolbar {
        margin-top: 10px;
        display: flex;
        gap: 10px;
    }

    .dropdown-menu {
        min-width: 200px;
    }
</style>
