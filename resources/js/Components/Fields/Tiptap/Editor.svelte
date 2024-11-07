<script>
    import { onMount, onDestroy } from 'svelte';

    //
    import { Editor } from '@tiptap/core';
    import Table from '@tiptap/extension-table';
    import TableCell from '@tiptap/extension-table-cell';
    import TableHeader from '@tiptap/extension-table-header';
    import TableRow from '@tiptap/extension-table-row';
    //import StarterKit from '@tiptap/starter-kit';
    import BulletList from '@tiptap/extension-bullet-list'
    import Document from '@tiptap/extension-document'
    import ListItem from '@tiptap/extension-list-item'
    import OrderedList from '@tiptap/extension-ordered-list'
    import Paragraph from '@tiptap/extension-paragraph'
    import Text from '@tiptap/extension-text'
    import { BubbleMenu as TiptapBubbleMenu } from '@tiptap/extension-bubble-menu';

    // Components
    import EditorContent from '@components/Fields/Tiptap/EditorContent.svelte';
    import MenuBar from '@components/Fields/Tiptap/ControlBar.svelte';

    let editor;
    let element;

    // Custom Table Cell Extension with background color support
    const CustomTableCell = TableCell.extend({
        addAttributes() {
            return {
                ...this.parent?.(),
                backgroundColor: {
                    default: null,
                    parseHTML: element => element.getAttribute('data-background-color'),
                    renderHTML: attributes => ({
                        'data-background-color': attributes.backgroundColor,
                        style: `background-color: ${attributes.backgroundColor}`,
                    })
                },
            }
        },
    });

    const CustomListItem = ListItem.configure({
        keepMarks: true,
        keepAttributes: false,
        HTMLAttributes: {
            class: 'list-item',
        },
    });

    const CustomTable = Table.extend({
        resizable: true,
        renderHTML({ HTMLAttributes }) {
            return ['table', { 
                class: 'table table-bordered table-striped table-striped-columnstable-responsive',
                ...HTMLAttributes 
            }, ['tbody', 0]];
        }
    });

    const tableHTML = `
        <table style="width:100%">
            <tr>
                <th>Firstname</th>
                <th>Lastname</th>
                <th>Age</th>
            </tr>
            <tr>
                <td>Jill</td>
                <td>Smith</td>
                <td>50</td>
            </tr>
            <tr>
                <td>Eve</td>
                <td>Jackson</td>
                <td>94</td>
            </tr>
            <tr>
                <td>John</td>
                <td>Doe</td>
                <td>80</td>
            </tr>
        </table>`;

    onMount(() => {
        editor = new Editor({
            element: element,
            extensions: [
                StarterKit.configure({
                    history: true,
                    listItem: false,  // Disable default listItem to use our custom one
                }),
                CustomListItem,
                CustomTable,
                TableRow,
                TableHeader,
                CustomTableCell,
                TiptapBubbleMenu.configure({
                    element: element.querySelector('.bubble-menu-container'),
                    shouldShow: ({ editor }) => editor.isActive('tableCell'),
                }),
            ],
            editorProps: {
                handleKeyDown: ({ event }) => {
                    if (event.key === 'Tab') {
                        if (editor.isActive('listItem')) {
                            if (event.shiftKey) {
                                // Dedent on Shift+Tab
                                editor.chain().focus().liftListItem('listItem').run();
                            } else {
                                // Indent on Tab
                                editor.chain().focus().sinkListItem('listItem').run();
                            }
                            event.preventDefault();
                            return true;
                        }
                    }
                    return false;
                },
            },
            content: `
                <h3>Have you seen our tables? They are amazing!</h3>
                <ul>
                    <li>Tables with rows, cells and headers (optional)</li>
                    <li>Support for <code>colgroup</code> and <code>rowspan</code></li>
                    <li>And even resizable columns (optional)</li>
                </ul>
                <p>Here is an example:</p>
                <table>
                    <tbody>
                        <tr>
                            <th>Name</th>
                            <th colspan="3">Description</th>
                        </tr>
                        <tr>
                            <td>Cyndi Lauper</td>
                            <td>Singer</td>
                            <td>Songwriter</td>
                            <td>Actress</td>
                        </tr>
                        <tr>
                            <td>Marie Curie</td>
                            <td>Scientist</td>
                            <td>Chemist</td>
                            <td>Physicist</td>
                        </tr>
                        <tr>
                            <td>Indira Gandhi</td>
                            <td>Prime minister</td>
                            <td colspan="2">Politician</td>
                        </tr>
                    </tbody>
                </table>
            `,
            onTransaction: () => {
                editor = editor;
            }
        });
    });

    onDestroy(() => {
        if (editor) {
            editor.destroy();
        }
    });
</script>

<div class="card m-2">
    {#if editor}
    <div class="card-header">
        <MenuBar {editor} />
    </div>
    {/if}
    <div class="card-body" bind:this={element} />
</div>