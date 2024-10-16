<script>

// resources/js/Components/Structure/Sidebar/BtnToggleCollapse.svelte

    import { onMount } from 'svelte';

    let sidebarState = 'expanded'; // default state
    let btnSidebarToggle;

    // Function to get the sidebar state from localStorage
    const getStoredSidebarState = () => localStorage.getItem('sidebarState');
    
    // Function to set the sidebar state in localStorage
    const setStoredSidebarState = (state) => localStorage.setItem('sidebarState', state);
    
    // Function to get the preferred sidebar state
    const getPreferredSidebarState = () => {
        const storedState = getStoredSidebarState();
        if (storedState) {
            return storedState;
        }
        // Default to expanded if no stored state is available
        return 'expanded';
    };

    // Function to toggle the sidebar state
    const switchSidebarState = () => {
        const newState = sidebarState === 'expanded' ? 'collapsed' : 'expanded';
        setSidebarState(newState);
    };

    // Function to set the sidebar state and update the DOM
    const setSidebarState = (state) => {
        if (state === 'expanded') {
            document.body.classList.add('sidebar-expanded');
            document.body.classList.remove('sidebar-collapsed');
        } else {
            document.body.classList.add('sidebar-collapsed');
            document.body.classList.remove('sidebar-expanded');
        }
        sidebarState = state;
        setStoredSidebarState(state);
    };

    // Initialize the sidebar state on component mount
    onMount(() => {
        sidebarState = getPreferredSidebarState();
        setSidebarState(sidebarState);

        // Add the event listener for the button
        if (btnSidebarToggle) {
            btnSidebarToggle.addEventListener('click', switchSidebarState);
        }
    });
</script>

<button 
    id="btn-sidebar-toggle" 
    bind:this={btnSidebarToggle}
    type="button" 
    class="btn btn-outline-secondary rounded-circle mt-3 p-0 position-absolute bottom-0 end-0" 
    title="Collapse Sidebar"
>
    <i class="bi bi-chevron-left shrink-hide"></i>
    <i class="bi bi-chevron-right shrink-show"></i>
</button>
