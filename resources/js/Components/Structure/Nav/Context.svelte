<script>
// resources/js/Components/Structure/Nav/Context.svelte

    import InnerMainNav from '@components/Structure/Nav/InnerMain.svelte';

    // Accept `navItems` as a prop
    export let navItems = [];
</script>

<nav class="navbar bg-body-tertiary justify-content-between p-2 mb-4">
    <!-- Pass `navItems` prop to `InnerMainNav` component -->
    <InnerMainNav {navItems} />
    
    <slot name="actions"></slot>
</nav>
