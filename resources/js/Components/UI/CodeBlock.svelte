<script>
    import { onMount, createEventDispatcher } from 'svelte';
    import 'prismjs/themes/prism-okaidia.css';
    import Prism from 'prismjs';
    import 'prismjs/components/prism-javascript'; // Example: Importing JavaScript
    import 'prismjs/components/prism-css'; // Example: Importing CSS
    import 'prismjs/components/prism-clike'; // Add more as needed
    import { showToast } from '@scripts/toasts'; // Import the toast utility

    const dispatch = createEventDispatcher();

    export let language = 'plaintext';
    let codeContent;

    onMount(() => {
        if (codeContent) Prism.highlightElement(codeContent);
    });

    async function copyToClipboard() {
        try {
            await navigator.clipboard.writeText(codeContent.innerText);
            dispatch('copied', { success: true });
            showToast('Code copied to clipboard!', 'success', 3000, 'bottom-middle');
        } catch {
            dispatch('copied', { success: false });
            showToast('Failed to copy code.', 'danger', 3000, 'bottom-middle');
        }
    }
</script>

<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center p-2">
            <slot name="title">Code Snippet</slot>
            <button 
                on:click={copyToClipboard} 
                class="btn p-0"    
                data-bs-toggle="tooltip" 
                title="Copy to clipboard"
            >
                <i class="bi bi-copy"></i>
            </button>
        </div>
    </div>
    <div class="card-body p-0">
        <pre class="m-0"><code bind:this={codeContent} class={`language-${language}`}><slot /></code></pre>
    </div>
</div>