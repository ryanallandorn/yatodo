<script>
// resources/js/Components/Fields/Tiptap/Editor.svelte
    
    // External Imports
    import { t } from 'svelte-i18n';
    import { onMount, onDestroy } from 'svelte';
    import { page } from '@inertiajs/svelte';
    import { debounce } from 'lodash';
    import axios from 'axios';

    // Internal Imports
    import { handleCallbackMessages } from '@scripts/toasts';
    
    // Tiptap Imports
    import { Editor } from '@tiptap/core';
    import StarterKit from '@tiptap/starter-kit';
    import Table from '@tiptap/extension-table';
    import TableCell from '@tiptap/extension-table-cell';
    import TableHeader from '@tiptap/extension-table-header';
    import TableRow from '@tiptap/extension-table-row';
    import BulletList from '@tiptap/extension-bullet-list';
    import Document from '@tiptap/extension-document';
    import ListItem from '@tiptap/extension-list-item';
    import OrderedList from '@tiptap/extension-ordered-list';
    import Paragraph from '@tiptap/extension-paragraph';
    import Text from '@tiptap/extension-text';
    import TextAlign from '@tiptap/extension-text-align';
    import { BubbleMenu as TiptapBubbleMenu } from '@tiptap/extension-bubble-menu';
    import Strike from '@tiptap/extension-strike';
    import Highlight from '@tiptap/extension-highlight';
    
    // Components
    import MenuBar from '@components/Fields/Tiptap/ControlBar.svelte';
    
    // Props
    export let apiPutRoute; // API route prop
    export let fieldName = 'description'; // Field name prop, defaulting to 'description'
    
    let editor;
    let element;
    let contentElement; // Element to hold the slot content
    
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
                    }),
                },
            };
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
                class: 'table table-bordered table-striped table-responsive',
                ...HTMLAttributes,
            }, ['tbody', 0]];
        },
    });
    
    onMount(() => {
        const initialContent = contentElement ? contentElement.innerHTML : '';
    
        editor = new Editor({
            element: element,
            extensions: [
                StarterKit.configure({ history: true, listItem: false }),
                CustomListItem,
                CustomTable,
                TableRow,
                TableHeader,
                CustomTableCell,
                TiptapBubbleMenu.configure({
                    element: element.querySelector('.bubble-menu-container'),
                    shouldShow: ({ editor }) => editor.isActive('tableCell'),
                }),
                Strike,
                Highlight.configure({ multicolor: true }),
                TextAlign.configure({ types: ['heading', 'paragraph'] }),
            ],
            content: initialContent,
            onTransaction: () => {
                editor = editor;
            },
            onUpdate: () => {
                updateField();
            },
        });
    });
    
    onDestroy(() => {
        if (editor) {
            editor.destroy();
        }
    });
    
    // Debounced update function using Axios
    const debouncedUpdate = debounce(async (editorContent) => {
        if (!apiPutRoute) {
            console.error("API route not specified");
            return;
        }
    
        try {
            console.log('Attempting to update task:', {
                route: apiPutRoute,
                // [fieldName]: editorContent,
                fieldName: fieldName,
                fieldValue: editorContent
            });
    
            const response = await axios.put(apiPutRoute, {
                [fieldName]: editorContent
            }, {
                headers: {
                    'X-CSRF-TOKEN': $page.props.csrf_token
                }
            });
    
            handleCallbackMessages(response.data);

            // console.log('Update successful:', response.data);
    
        } catch (error) {
            if (error.response && error.response.status === 422) {
                console.error("Validation failed:", error.response.data.errors);
            } else {
                console.error("An error occurred:", error);
            }
        }
    }, 250);


    
    
    const updateField = () => {
        debouncedUpdate(editor.getHTML());
    };
    </script>
    
    <div class="card card-wysiwyg p-0 m-2">
        {#if editor}
        <div class="card-header p-0">
            <MenuBar {editor} />
        </div>
        {/if}
        <div class="card-body" bind:this={element}></div>
        <div bind:this={contentElement} style="display: none;">
            <slot></slot>
        </div>
    </div>
    