<script>

// resources/js/Pages/Docs/Modal.svelte

    import { useForm } from "@inertiajs/svelte";
    import { writable } from 'svelte/store';

    import App from "@layouts/App.svelte";
    import ModalBox from '@components/UI/Modal/Box.svelte';

    let modal; // Store the modal instance reference

    const initialUser = { id: 1, name: 'Alice', email: 'alice@example.com' }; // Initial data
    const userApiUrl = 'https://jsonplaceholder.typicode.com/users/1'; // API route

    // Custom function to fetch user data
    async function customFetchUser() {
        return new Promise((resolve) =>
            setTimeout(() => resolve({ id: 2, name: 'Bob', email: 'bob@example.com' }), 1000)
        );
    }

    // Function to open the modal
    function openUserModal() {
        modal.open(); // Access the modal's `open` method through the bound instance
    }
</script>

<App>
    <button class="btn btn-primary" on:click={openUserModal}>
        Open User Modal
    </button>

    <ModalBox
        bind:this={modal}
        initialData={initialUser} 
        fetchUrl={userApiUrl} 
        fetchOnLoad={customFetchUser}
    >
        <span slot="title">User Details</span>

        <div slot="staticContent">
            <h5>Static Content</h5>
            <p>This is additional static content inside the modal.</p>
        </div>

        <div slot="footer">
            <button type="button" class="btn btn-secondary" on:click={() => console.log('Custom Close')}>
                Custom Close
            </button>
            <button type="button" class="btn btn-primary">
                Custom Save
            </button>
        </div>

    </ModalBox>
</App>