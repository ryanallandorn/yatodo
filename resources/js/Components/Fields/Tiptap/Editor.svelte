<script>

// resources/js/Components/Fields/Tiptap/Editor.svelte

    import { onMount, onDestroy } from 'svelte';

    import { page, router } from '@inertiajs/svelte';

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
    
    // Import the new extensions
    import Strike from '@tiptap/extension-strike'; // For strikethrough
    import Highlight from '@tiptap/extension-highlight'; // For highlight

    // Components
    import MenuBar from '@components/Fields/Tiptap/ControlBar.svelte';

    export let apiPutRoute; // Accept the API route as a prop

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
        // Use the innerHTML of the contentElement as the initial content
        const initialContent = contentElement ? contentElement.innerHTML : '';

        editor = new Editor({
            element: element,
            extensions: [
                StarterKit.configure({
                    history: true,
                    listItem: false, // Disable default listItem to use our custom one
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
                Strike, // Add the strikethrough extension
                Highlight.configure({ // Configure the highlight extension
                    multicolor: true,
                }),
                TextAlign.configure({
                    types: ['heading', 'paragraph'], // Elements you want to align
                }),
            ],
            content: initialContent, // Set the initial content
            onTransaction: () => {
                editor = editor;
            },
            onUpdate: ({ editor }) => {
                updateField(); // Call updateField when the content updates
            },
        });
    });

    onDestroy(() => {
        if (editor) {
            editor.destroy();
        }
    });


    // // Function to handle updating the field
    // const updateField = async () => {
    //     if (!apiPutRoute) {
    //         console.error("API route not specified");
    //         return;
    //     }

    //     try {
    //         console.log(apiPutRoute);
    //         const response = await fetch(apiPutRoute, {
    //             method: 'PUT',
    //             headers: {
    //                 'Content-Type': 'application/json',
    //                 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
    //             },
    //             body: JSON.stringify({
    //                 content: editor.getHTML(),
    //             }),
    //         });

    //         if (!response.ok) {
    //             throw new Error("Failed to update the field");
    //         }

    //         console.log("Field updated successfully");
    //     } catch (error) {
    //         console.error("Error updating field:", error);
    //     }
    // };

        // Function to handle updating the field
        const updateField = () => {
            if (!apiPutRoute) {
                console.error("API route not specified");
                return;
            }

            // Use Inertia's router to make the request
            router.put(apiPutRoute, {
                // _method: 'PUT', // Spoof the PUT method
                // _token: $page.props.csrf_token,
                content: editor.getHTML(),
            }).then(() => {
                console.log("Field updated successfully");
            }).catch(error => {
                console.error("Error updating field:", error);
            });
        };


</script>

<div class="card card-wysiwyg p-0 m-2">
    {#if editor}
    <div class="card-header p-0">
        <MenuBar {editor} />
    </div>
    {/if}
    <div class="card-body" bind:this={element}></div>
    <!-- Hidden div to hold the slot content -->
    <div bind:this={contentElement} style="display: none;">
        <slot></slot>
    </div>
</div>
