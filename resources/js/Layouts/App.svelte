<script>

// resources/js/Layouts/App.svelte

    import { page, Link } from "@inertiajs/svelte";
    import { router } from '@inertiajs/svelte';
    //import Alert from "../Components/Alert.svelte";
    import Appbar from "@components/Structure/Appbar.svelte";
    import Sidebar from "@components/Structure/Sidebar.svelte";
    import Hero from "@components/Structure/Hero.svelte";

    function logout() {
        router.post('/logout');
    }
</script>

<Appbar />

<div class="container-fluid d-flex flex-grow-1 p-0">

    <Sidebar />
    <main class="flex-grow-1 d-flex flex-column ms-sm-auto">
        <!-- Hero Slot (optional) -->
        {#if $$slots.hero}
            <Hero>
                <slot name="hero"></slot> <!-- Named slot for the hero content -->
            </Hero>
        {/if}

        <div class="flex-grow-1 d-flex flex-column px-md-4 main-content">
            <slot />
        </div>
    </main>
</div>

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