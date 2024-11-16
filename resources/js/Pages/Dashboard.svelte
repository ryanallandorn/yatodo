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
    >

        <h3>Have you seen our tables? They are amazing!</h3>
        <ul>
            <li>Tables with rows, cells and headers (optional)</li>
            <li>Support for <code>colgroup</code> and <code>rowspan</code></li>
            <li>And even resizable columns (optional)</li>
        </ul>
        <p>Here is an example:</p>
        <table>
            <tbody>
                <tr>
                    <th>Name</th>
                    <th colspan="3">Description</th>
                </tr>
                <tr>
                    <td>Cyndi Lauper</td>
                    <td>Singer</td>
                    <td>Songwriter</td>
                    <td>Actress</td>
                </tr>
                <tr>
                    <td>Marie Curie</td>
                    <td>Scientist</td>
                    <td>Chemist</td>
                    <td>Physicist</td>
                </tr>
                <tr>
                    <td>Indira Gandhi</td>
                    <td>Prime minister</td>
                    <td colspan="2">Politician</td>
                </tr>
            </tbody>
        </TiptapEditor>

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
