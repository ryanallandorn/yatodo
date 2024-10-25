<script>
    export let editor;

    function toggleBold() {
        editor.chain().focus().toggleBold().run();
    }

    function toggleItalic() {
        editor.chain().focus().toggleItalic().run();
    }
</script>

<div class="menu-bar">
    <button on:click={toggleBold}>Bold</button>
    <button on:click={toggleItalic}>Italic</button>
</div>

<style>
.menu-bar {
    margin-bottom: 8px;
}
button {
    margin-right: 4px;
    padding: 4px 8px;
}
</style>
