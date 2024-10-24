<!-- resources/js/Pages/Dashboard.svelte -->
<script>


    import App from "@layouts/App.svelte";
    import ApiTokenManager from '@pages/API/Partials/ApiTokenManager.svelte';
    
    export let tokens = [];
    export let availablePermissions = [];
    export let defaultPermissions = [];

</script>

<svelte:head>
    <title>API</title>
</svelte:head>

<App>

    <!-- This content will be passed to the named "hero" slot -->
    <div slot="hero" class="hero-contents">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            <i class="bi bi-braces-asterisk me-2"></i> API
        </h2>
        <!-- <p>Additional content here, like a description or other elements.</p> -->
    </div>

    <div class="page-content container py-5">
        <ApiTokenManager
            {tokens}
            {availablePermissions}
            {defaultPermissions}
        />
    </div>

</App>
