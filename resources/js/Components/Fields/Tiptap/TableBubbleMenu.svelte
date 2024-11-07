<!-- src/components/Fields/Tiptap/TableBubbleMenu.svelte -->
<script>
    import { BubbleMenu } from '@tiptap/extension-bubble-menu';

    export let editor;

    const actions = [
        { label: 'Insert Row Before', action: () => editor.chain().focus().addRowBefore().run() },
        { label: 'Insert Row After', action: () => editor.chain().focus().addRowAfter().run() },
        { label: 'Insert Column Left', action: () => editor.chain().focus().addColumnBefore().run() },
        { label: 'Insert Column Right', action: () => editor.chain().focus().addColumnAfter().run() },
        { label: 'Delete Row', action: () => editor.chain().focus().deleteRow().run() },
        { label: 'Delete Column', action: () => editor.chain().focus().deleteColumn().run() },
        { label: 'Delete Table', action: () => editor.chain().focus().deleteTable().run() },
        { label: 'Split Cell', action: () => editor.chain().focus().splitCell().run() }
    ];
</script>

<style>
    .bubble-menu {
        background-color: white;
        border: 1px solid #ddd;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        padding: 8px;
        display: flex;
        flex-direction: column;
        gap: 4px;
        z-index: 1000;
    }

    .bubble-menu button {
        all: unset;
        padding: 4px;
        cursor: pointer;
        border-radius: 4px;
        transition: background-color 0.2s;
    }

    .bubble-menu button:hover {
        background-color: #f0f0f0;
    }
</style>

{#if editor}
    <div class="bubble-menu">
        {#each actions as { label, action }}
            <button on:click={action}>{label}</button>
        {/each}
    </div>
{/if}
