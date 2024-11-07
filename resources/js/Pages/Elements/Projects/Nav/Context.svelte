<script>
    // resources/js/Pages/Elements/Projects/ContextNav.svelte
    
        import InnerMainNav from '@components/Structure/Nav/InnerMain.svelte';
    
        // Accept `navItems` as a prop
        export let navItems = [];
    </script>
    
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <!-- Pass `navItems` prop to `InnerMainNav` component -->
        <InnerMainNav {navItems} />
        
        <!-- Actions slot for custom content -->
        <div>
            <slot name="actions"></slot>
        </div>
    </nav>
    