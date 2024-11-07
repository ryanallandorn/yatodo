<script>

// resources/js/Components/Fields/Tiptap/ControlBar.svelte

    //
    import { t } from 'svelte-i18n';
    import 'material-symbols';
    
    // Components
    import DropdownButton from '@components/UI/Dropdowns/Button.svelte';
    import TableGridSelector from '@components/Fields/Tiptap/TableGridSelector.svelte';


    export let editor;

    const toggleHeading = (level) => {
        editor.chain().focus().toggleHeading({ level }).run();
    };

    const setParagraph = () => {
        editor.chain().focus().setParagraph().run();
    };


    // List operation functions
    const toggleBulletList = () => editor.chain().focus().toggleBulletList().run();
    const toggleOrderedList = () => editor.chain().focus().toggleOrderedList().run();
    const toggleTaskList = () => editor.chain().focus().toggleTaskList().run();
    const sinkListItem = () => editor.chain().focus().sinkListItem('listItem').run();
    const liftListItem = () => editor.chain().focus().liftListItem('listItem').run();
    const splitListItem = () => editor.chain().focus().splitListItem('listItem').run();


    // Table operation functions
    const insertTable = () => editor.chain().focus().insertTable({ rows: 3, cols: 3, withHeaderRow: true }).run();
    const insertHTMLTable = () => editor.chain().focus().insertContent(tableHTML, { parseOptions: { preserveWhitespace: false }}).run();
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

<div class="control-group btn-toolbar" role="toolbar">


    <DropdownButton
        buttonCss="btn-outline-secondary"
        tooltip={$t('Table')}
    >
        <span slot="button" class="material-symbols-outlined d-inline-flex">table</span>
        <div slot="menu">
            <TableGridSelector {editor} />
        </div>
    </DropdownButton>

    <DropdownButton
        buttonCss="btn-outline-secondary"
        tooltip={$t('Lists')}
    >
        <span slot="button" class="material-symbols-outlined d-inline-flex">list</span>
        <div slot="menu">
            <button
                on:click={toggleBulletList}
                data-bs-toggle="tooltip"
                title="Toggle bullet list"
                class="dropdown-item"
            >
                <i class="bi bi-list-ul"></i> Toggle Bullet List
            </button>
            <button
                on:click={toggleOrderedList}
                data-bs-toggle="tooltip"
                title="Toggle ordered list"
                class="dropdown-item"
            >
                <i class="bi bi-list-ol"></i> Toggle Ordered List
            </button>
            <button
                on:click={toggleTaskList}
                data-bs-toggle="tooltip"
                title="Toggle task list"
                class="dropdown-item"
            >
                <i class="bi bi-check-square"></i> Task List
            </button>
            <li><hr class="dropdown-divider" /></li>
            <button
                on:click={sinkListItem}
                disabled={!editor.can().sinkListItem('listItem')}
                data-bs-toggle="tooltip"
                title="Sink list item"
                class="dropdown-item"
            >
                <span class="material-symbols-outlined d-inline-flex">format_indent_increase</span> Sink
            </button>
            <button
                on:click={liftListItem}
                disabled={!editor.can().liftListItem('listItem')}
                data-bs-toggle="tooltip"
                title="Lift list item"
                class="dropdown-item"
            >
                <span class="material-symbols-outlined d-inline-flex">format_indent_decrease</span> Lift
            </button>
            <button
                on:click={splitListItem}
                disabled={!editor.can().splitListItem('listItem')}
                data-bs-toggle="tooltip"
                title="Split list item"
                class="dropdown-item"
            >
                <i class="bi bi-scissors"></i> Split
            </button>

        </div>
    </DropdownButton>


    <DropdownButton
        buttonCss="btn-outline-secondary"
        tooltip={$t('Random Shit')}
    >
        <span slot="button">X</span>
        <div slot="menu">

            <h2>Table Shit</h2>

            <div class="btn-group me-2" role="group">
                <button
                    on:click={insertTable}
                    data-bs-toggle="tooltip"
                    title="Insert table"
                    class="btn btn-outline-secondary"
                >
                    <i class="bi bi-table"></i>
                </button>
                <button
                    on:click={insertHTMLTable}
                    data-bs-toggle="tooltip"
                    title="Insert HTML table"
                    class="btn btn-outline-secondary"
                >
                    <i class="bi bi-code-square"></i>
                </button>
            </div>
        
            <div class="btn-group me-2" role="group">
                <button
                    on:click={addColumnBefore}
                    disabled={!editor.can().addColumnBefore()}
                    data-bs-toggle="tooltip"
                    title="Add column before"
                    class="btn btn-outline-secondary"
                >
                <span class="material-symbols-outlined d-flex">add_column_left</span>
                </button>
                <button
                    on:click={addColumnAfter}
                    disabled={!editor.can().addColumnAfter()}
                    data-bs-toggle="tooltip"
                    title="Add column after"
                    class="btn btn-outline-secondary"
                >
                <span class="material-symbols-outlined d-flex">add_column_right</span>
                </button>
                <button
                    on:click={deleteColumn}
                    disabled={!editor.can().deleteColumn()}
                    data-bs-toggle="tooltip"
                    title="Delete column"
                    class="btn btn-outline-secondary"
                >
                    <i class="bi bi-x-square"></i>
                </button>
            </div>


            <button
                on:click={insertHTMLTable}
                data-bs-toggle="tooltip"
                title="Insert HTML table"
                class="btn btn-outline-secondary"
            >
                <i class="bi bi-code-square"></i>
            </button>



            <div class="btn-group me-2" role="group">
                <button
                    on:click={fixTables}
                    disabled={!editor.can().fixTables()}
                    data-bs-toggle="tooltip"
                    title="Fix tables"
                    class="btn btn-outline-secondary"
                >
                    <i class="bi bi-tools"></i>
                </button>
                <button
                    on:click={goToPreviousCell}
                    disabled={!editor.can().goToPreviousCell()}
                    data-bs-toggle="tooltip"
                    title="Go to previous cell"
                    class="btn btn-outline-secondary"
                >
                    <span class="material-symbols-outlined d-flex">arrow_left_alt</span>
                </button>
                <button
                    on:click={goToNextCell}
                    disabled={!editor.can().goToNextCell()}
                    data-bs-toggle="tooltip"
                    title="Go to next cell"
                    class="btn btn-outline-secondary"
                >
                    <span class="material-symbols-outlined d-flex">arrow_right_alt</span>
                </button>
            </div>



            <div class="btn-group me-2" role="group">
                <button
                    on:click={addRowBefore}
                    disabled={!editor.can().addRowBefore()}
                    data-bs-toggle="tooltip"
                    title="Add row before"
                    class="btn btn-outline-secondary"
                >
                <span class="material-symbols-outlined d-flex">add_row_above</span>
                </button>
                <button
                    on:click={addRowAfter}
                    disabled={!editor.can().addRowAfter()}
                    data-bs-toggle="tooltip"
                    title="Add row after"
                    class="btn btn-outline-secondary"
                >
                    <span class="material-symbols-outlined d-flex">add_row_below</span>
                </button>
                <button
                    on:click={deleteRow}
                    disabled={!editor.can().deleteRow()}
                    data-bs-toggle="tooltip"
                    title="Delete row"
                    class="btn btn-outline-secondary"
                >
                    <span class="material-symbols-outlined d-flex">delete_forever</span>
                </button>
            </div>
        
            <div class="btn-group me-2" role="group">
                <button
                    on:click={mergeCells}
                    disabled={!editor.can().mergeCells()}
                    data-bs-toggle="tooltip"
                    title="Merge cells"
                    class="btn btn-outline-secondary"
                >
                    <i class="bi bi-arrows-angle-contract"></i>
                </button>
                <button
                    on:click={splitCell}
                    disabled={!editor.can().splitCell()}
                    data-bs-toggle="tooltip"
                    title="Split cell"
                    class="btn btn-outline-secondary"
                >
                    <i class="bi bi-arrows-angle-expand"></i>
                </button>
            </div>
        
            <div class="btn-group me-2" role="group">
                <button
                    on:click={toggleHeaderColumn}
                    disabled={!editor.can().toggleHeaderColumn()}
                    data-bs-toggle="tooltip"
                    title="Toggle header column"
                    class="btn btn-outline-secondary"
                >
                    <i class="bi bi-layout-sidebar"></i>
                </button>
                <button
                    on:click={toggleHeaderRow}
                    disabled={!editor.can().toggleHeaderRow()}
                    data-bs-toggle="tooltip"
                    title="Toggle header row"
                    class="btn btn-outline-secondary"
                >
                    <i class="bi bi-layout-text-sidebar"></i>
                </button>
                <button
                    on:click={toggleHeaderCell}
                    disabled={!editor.can().toggleHeaderCell()}
                    data-bs-toggle="tooltip"
                    title="Toggle header cell"
                    class="btn btn-outline-secondary"
                >
                    <i class="bi bi-square"></i>
                </button>
            </div>
        
            <div class="btn-group me-2" role="group">
                <button
                    on:click={mergeOrSplit}
                    disabled={!editor.can().mergeOrSplit()}
                    data-bs-toggle="tooltip"
                    title="Merge or split"
                    class="btn btn-outline-secondary"
                >
                    <i class="bi bi-arrows-fullscreen"></i>
                </button>
                <button
                    on:click={setCellBackground}
                    disabled={!editor.can().setCellAttribute('backgroundColor', '#FAF594')}
                    data-bs-toggle="tooltip"
                    title="Set cell background"
                    class="btn btn-outline-secondary"
                >
                    <i class="bi bi-paint-bucket"></i>
                </button>
            </div>
        


        </div>
    </DropdownButton>



    <div class="btn-group me-2" role="group">



    </div>

    <div class="button-group">
        <button
            on:click={() => toggleHeading(1)}
            class:active={editor?.isActive('heading', { level: 1 })}
        >
            H1
        
        </button>
        <button
            on:click={() => toggleHeading(2)}
            class:active={editor?.isActive('heading', { level: 2 })}
        >
            H2
        
        </button>
        <button
            on:click={setParagraph}
            class:active={editor?.isActive('paragraph')}
        >
            P
        
        </button>
    </div>





</div>


<style>
    .menu-bar {
        display: flex;
        gap: 10px;
        margin-bottom: 10px;
    }
    
    button.active {
        background: black;
        color: white;
    }
</style>
