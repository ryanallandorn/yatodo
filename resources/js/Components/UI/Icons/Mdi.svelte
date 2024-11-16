<!-- resources/js/Components/UI/Icon.svelte -->

<script>
    export let icon; // `icon` will be passed as a prop
</script>

<svg 
    viewBox="0 0 24 24" 
    width="24" 
    height="24" 
    class="inline-block align-middle"
>
    <path d={icon} fill="currentColor" />
</svg>
