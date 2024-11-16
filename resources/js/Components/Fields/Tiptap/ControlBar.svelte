<script>

// resources/js/Components/Fields/Tiptap/ControlBar.svelte

    //
    import { onMount, onDestroy } from 'svelte';
    import { t } from 'svelte-i18n';
    import 'material-symbols';
    
    // Components
    import DropdownButton from '@components/UI/Dropdowns/Button.svelte';
    import TableGridSelector from '@components/Fields/Tiptap/TableGridSelector.svelte';

    import 'remixicon/fonts/remixicon.css'; // Import Remix Icon CSS
    //import remixiconUrl from 'remixicon/fonts/remixicon.symbol.svg';



    export let editor;

    // Undo and Redo functions
    const undo = () => editor.chain().focus().undo().run();
    const redo = () => editor.chain().focus().redo().run();

// // Event listener for keyboard shortcuts
// onMount(() => {
//     const handleKeyDown = (event) => {
//         if (event.ctrlKey && event.key === 'z') {
//             event.preventDefault();
//             undo();
//         }
//     };

//     window.addEventListener('keydown', handleKeyDown);

//     // The event listener should be removed using onDestroy outside of onMount
//     return () => {
//         window.removeEventListener('keydown', handleKeyDown);
//     };
// });


    // Text formatting functions
    const toggleBold = () => editor.chain().focus().toggleBold().run();
    const toggleItalic = () => editor.chain().focus().toggleItalic().run();
    const toggleStrikethrough = () => editor.chain().focus().toggleStrike().run();
    const toggleHighlight = (color) => editor.chain().focus().setMark('highlight', { color }).run();
    const clearFormatting = () => editor.chain().focus().unsetAllMarks().clearNodes().run();
    const insertHorizontalRule = () => editor.chain().focus().setHorizontalRule().run();
    const insertHardBreak = () => editor.chain().focus().setHardBreak().run();

        // Alignment functions
    const alignLeft = () => editor.chain().focus().setTextAlign('left').run();
    const alignCenter = () => editor.chain().focus().setTextAlign('center').run();
    const alignRight = () => editor.chain().focus().setTextAlign('right').run();
    const alignJustify = () => editor.chain().focus().setTextAlign('justify').run();



    const highlightColors = [
        { color: '#FFEA00', label: 'Yellow' },
        { color: '#FF5722', label: 'Orange' },
        { color: '#4CAF50', label: 'Green' },
        { color: '#2196F3', label: 'Blue' },
        { color: '#E91E63', label: 'Pink' },
    ];


    // const toggleHeading = (level) => {
    //     editor.chain().focus().toggleHeading({ level }).run();
    // };

    // const setParagraph = () => {
    //     editor.chain().focus().setParagraph().run();
    // };

    // Heading and Paragraph functions
    const toggleHeading = (level) => editor.chain().focus().toggleHeading({ level }).run();
    const setParagraph = () => editor.chain().focus().setParagraph().run();


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



    <!-- Undo and Redo Buttons -->
 
        <button
            on:click={undo}
            data-bs-toggle="tooltip"
            title="Undo"
            class="btn btn-sm"
        >
            <!-- <span class="material-symbols-outlined d-flex">undo</span> -->
            <i class="ri-arrow-go-back-line"></i>
        </button>
        <button
            on:click={redo}
            data-bs-toggle="tooltip"
            title="Redo"
            class="btn btn-sm"
        >
            <!-- <span class="material-symbols-outlined d-flex">redo</span> -->
            <i class="ri-arrow-go-forward-line"></i>
        </button>

        <div class="vr mx-2"></div>
 
        <button
            on:click={toggleBold}
            class="btn btn-sm"
            data-bs-toggle="tooltip"
            title="Bold"
            class:active={editor?.isActive('bold')}
        >
            <!-- <span class="material-symbols-outlined d-flex">format_bold</span> -->
            <i class="ri-bold"></i>
        </button>
        <button
            on:click={toggleItalic}
            class="btn btn-sm"
            data-bs-toggle="tooltip"
            title="Italic"
            class:active={editor?.isActive('italic')}
        >
            <i class="ri-italic"></i>
        </button>
        <button
            on:click={toggleStrikethrough}
            class="btn btn-sm"
            data-bs-toggle="tooltip"
            title="Strikethrough"
            class:active={editor?.isActive('italic')}
        >
            <i class="ri-strikethrough"></i>
        </button>


        <!-- Highlight Dropdown Button -->
        <DropdownButton buttonCss="btn-sm" tooltip={$t('Highlight')}>
            <span slot="button"><i class="ri-mark-pen-line"></i></span>
            <div slot="menu">
                {#each highlightColors as { color, label }}
                    <button
                        on:click={() => toggleHighlight(color)}
                        style="background-color: {color}; color: #fff; border: none; margin: 2px; padding: 4px 8px;"
                        class="dropdown-item"
                    >
                        {label}
                    </button>
                {/each}
            </div>
        </DropdownButton>

        <button 
            on:click={clearFormatting} 
            class="btn btn-sm" 
            data-bs-toggle="tooltip" 
            title="Clear Formatting"
        >
            <i class="ri-format-clear"></i>
        </button>

    <div class="vr mx-2"></div>

    <!-- Insert Horizontal Rule and Hard Break Buttons -->
    <button on:click={insertHorizontalRule} class="btn btn-sm" data-bs-toggle="tooltip" title="Insert Horizontal Line">
        <i class="ri-separator"></i>
    </button>
    <button on:click={insertHardBreak} class="btn btn-sm" data-bs-toggle="tooltip" title="Insert Hard Break">
        <i class="ri-arrow-down-s-line"></i>
    </button>

    <div class="vr mx-2"></div>

    <DropdownButton
        buttonCss="btn-sm"
        tooltip={$t('Table')}
    >
        <!-- <span slot="button" class="material-symbols-outlined d-inline-flex">table</span> -->
        <i class="ri-table-line" slot="button"></i>
        <div slot="menu">
            <TableGridSelector {editor} />
        </div>
    </DropdownButton>

    <div class="vr mx-2"></div>

    <DropdownButton
        buttonCss="btn-sm"
        tooltip={$t('Lists')}
    >
        <!-- <span slot="button" class="material-symbols-outlined d-inline-flex">list</span> -->
        <i class="ri-list-unordered" slot="button"></i>
        


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
        buttonCss="btn-sm d-none"
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
                    class="btn btn-sm"
                >
                    <i class="bi bi-table"></i>
                </button>
                <button
                    on:click={insertHTMLTable}
                    data-bs-toggle="tooltip"
                    title="Insert HTML table"
                    class="btn btn-sm"
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
                    class="btn btn-sm"
                >
                <span class="material-symbols-outlined d-flex">add_column_left</span>
                </button>
                <button
                    on:click={addColumnAfter}
                    disabled={!editor.can().addColumnAfter()}
                    data-bs-toggle="tooltip"
                    title="Add column after"
                    class="btn btn-sm"
                >
                <span class="material-symbols-outlined d-flex">add_column_right</span>
                </button>
                <button
                    on:click={deleteColumn}
                    disabled={!editor.can().deleteColumn()}
                    data-bs-toggle="tooltip"
                    title="Delete column"
                    class="btn btn-sm"
                >
                    <i class="bi bi-x-square"></i>
                </button>
            </div>


            <button
                on:click={insertHTMLTable}
                data-bs-toggle="tooltip"
                title="Insert HTML table"
                class="btn btn-sm"
            >
                <i class="bi bi-code-square"></i>
            </button>



            <div class="btn-group me-2" role="group">
                <button
                    on:click={fixTables}
                    disabled={!editor.can().fixTables()}
                    data-bs-toggle="tooltip"
                    title="Fix tables"
                    class="btn btn-sm"
                >
                    <i class="bi bi-tools"></i>
                </button>
                <button
                    on:click={goToPreviousCell}
                    disabled={!editor.can().goToPreviousCell()}
                    data-bs-toggle="tooltip"
                    title="Go to previous cell"
                    class="btn btn-sm"
                >
                    <span class="material-symbols-outlined d-flex">arrow_left_alt</span>
                </button>
                <button
                    on:click={goToNextCell}
                    disabled={!editor.can().goToNextCell()}
                    data-bs-toggle="tooltip"
                    title="Go to next cell"
                    class="btn btn-sm"
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
                    class="btn btn-sm"
                >
                <span class="material-symbols-outlined d-flex">add_row_above</span>
                </button>
                <button
                    on:click={addRowAfter}
                    disabled={!editor.can().addRowAfter()}
                    data-bs-toggle="tooltip"
                    title="Add row after"
                    class="btn btn-sm"
                >
                    <span class="material-symbols-outlined d-flex">add_row_below</span>
                </button>
                <button
                    on:click={deleteRow}
                    disabled={!editor.can().deleteRow()}
                    data-bs-toggle="tooltip"
                    title="Delete row"
                    class="btn btn-sm"
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
                    class="btn btn-sm"
                >
                    <i class="bi bi-arrows-angle-contract"></i>
                </button>
                <button
                    on:click={splitCell}
                    disabled={!editor.can().splitCell()}
                    data-bs-toggle="tooltip"
                    title="Split cell"
                    class="btn btn-sm"
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
                    class="btn btn-sm"
                >
                    <i class="bi bi-layout-sidebar"></i>
                </button>
                <button
                    on:click={toggleHeaderRow}
                    disabled={!editor.can().toggleHeaderRow()}
                    data-bs-toggle="tooltip"
                    title="Toggle header row"
                    class="btn btn-sm"
                >
                    <i class="bi bi-layout-text-sidebar"></i>
                </button>
                <button
                    on:click={toggleHeaderCell}
                    disabled={!editor.can().toggleHeaderCell()}
                    data-bs-toggle="tooltip"
                    title="Toggle header cell"
                    class="btn btn-sm"
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
                    class="btn btn-sm"
                >
                    <i class="bi bi-arrows-fullscreen"></i>
                </button>
                <button
                    on:click={setCellBackground}
                    disabled={!editor.can().setCellAttribute('backgroundColor', '#FAF594')}
                    data-bs-toggle="tooltip"
                    title="Set cell background"
                    class="btn btn-sm"
                >
                    <i class="bi bi-paint-bucket"></i>
                </button>
            </div>
        


        </div>
    </DropdownButton>



    <div class="vr mx-2"></div>

        <!-- Alignment Buttons -->
        <button on:click={alignLeft} class="btn btn-sm" data-bs-toggle="tooltip" title="Align Left" class:active={editor?.isActive({ textAlign: 'left' })}>
            <i class="ri-align-left"></i>
        </button>
        <button on:click={alignCenter} class="btn btn-sm" data-bs-toggle="tooltip" title="Align Center" class:active={editor?.isActive({ textAlign: 'center' })}>
            <i class="ri-align-center"></i>
        </button>
        <button on:click={alignRight} class="btn btn-sm" data-bs-toggle="tooltip" title="Align Right" class:active={editor?.isActive({ textAlign: 'right' })}>
            <i class="ri-align-right"></i>
        </button>
        <button on:click={alignJustify} class="btn btn-sm" data-bs-toggle="tooltip" title="Justify" class:active={editor?.isActive({ textAlign: 'justify' })}>
            <i class="ri-align-justify"></i>
        </button>
    
        <div class="vr mx-2"></div>
    

    <!-- Heading Dropdown Button -->
    <DropdownButton buttonCss="btn-sm" tooltip={$t('Headings')}>
        <span slot="button"><i class="ri-heading"></i></span>
        <div slot="menu">
            {#each [1, 2, 3, 4, 5, 6] as level}
                <button
                    on:click={() => toggleHeading(level)}
                    class="dropdown-item"
                    class:active={editor?.isActive('heading', { level })}
                >
                    H{level}
                </button>
            {/each}
            <li><hr class="dropdown-divider" /></li>
            <button on:click={setParagraph} class="dropdown-item" class:active={editor?.isActive('paragraph')}>
                Paragraph
            </button>
        </div>
    </DropdownButton>




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
