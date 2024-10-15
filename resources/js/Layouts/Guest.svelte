<script>

// resources/js/Layouts/Guest.svelte

    import { page, Link } from "@inertiajs/svelte";
    $: currentUrl = $page.url.pathname; 
    import { router } from '@inertiajs/svelte';
    //import Alert from "../Components/Alert.svelte";
    import Appbar from "@components/Structure/Appbar.svelte";
    import Sidebar from "@components/Structure/Sidebar.svelte";

    function logout() {
        router.post('/logout');
    }
</script>




<main class="form-auth { $page.url.startsWith('/login') ? 'form-signin' : ($page.url.startsWith('/register') ? 'form-register' : '') } w-100 m-auto">
        
    
    
    <slot />

</main>

<!-- <main class="container mx-auto md:w-[720px] my-6 px-6 md:my-12">
    <nav class="flex justify-between items-center border-b border-primary pb-2 mb-8">
        <h1 class="font-bold uppercase"><Link href="/">{$page.props.appName}</Link></h1>

        <div class="inline-flex gap-3">
            {#if $page.props.auth.user}
                <Link href="/posts/create"><i class="bi bi-plus-circle"></i></Link>
                <button on:click|preventDefault={logout}><i class="bi bi-box-arrow-right"></i></button>
            {/if}
            
            {#if ! $page.props.auth.user}
                <Link href="/login"><i class="bi bi-box-arrow-in-right"></i></Link>
            {/if}
        </div>
    </nav>

    {#if $page.props.flash.message}
        <Alert>{@html $page.props.flash.message}</Alert>
    {/if}

    <slot />
</main> -->