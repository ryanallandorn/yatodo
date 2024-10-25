<script>

// resources/js/Pages/Dashboard.svelte

    import { useForm } from "@inertiajs/svelte";
    import { writable } from 'svelte/store';

    import App from "@layouts/App.svelte";
    import ModalBox from '@components/UI/Modal/Box.svelte';

    let modal; // Store the modal instance reference

    const initialUser = { id: 1, name: 'Alice', email: 'alice@example.com' }; // Initial data
    const userApiUrl = 'http://127.0.0.1:8000/api/get/users?filters[id]=2'; // API route

    // Function to open the modal and trigger data fetch
    function openUserModal() {
        modal.open(); // Access the modal's `open` method through the bound instance
    }

    import TiptapEditor  from '@components/Fields/Tiptap/Editor.svelte';


</script>

<App>

    <TiptapEditor
    modelValue="<p>Hello, world!</p>"
    autosave={true}
    on:update={(event) => console.log('Editor content updated:', event.detail.value)}
/>


    <button class="btn btn-primary" on:click={openUserModal}>
        Open User Modal
    </button>

    <ModalBox
        bind:this={modal}
        initialData={initialUser} 
        fetchUrl={userApiUrl} 
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
