<script>
    export let editor; // Editor instance passed in as a prop
    let editorElement; // Local variable to bind the DOM element

    import { onDestroy, afterUpdate } from 'svelte';

    // Assign the element to the editor after the component has been updated
    afterUpdate(() => {
        if (editor && editorElement) {
            editor.setOptions({ element: editorElement });
        }
    });

    // Cleanup when the component is destroyed
    onDestroy(() => {
        editor?.destroy();
    });
</script>

<div class="editor-content" bind:this={editorElement}></div>

<style>
.editor-content {
    min-height: 200px;
    border: 1px solid #ddd;
    padding: 1rem;
    border-radius: 4px;
}
</style>
