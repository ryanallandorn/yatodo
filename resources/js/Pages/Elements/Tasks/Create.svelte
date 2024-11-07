<script>

// resources/js/Pages/Dashboard.svelte


    import { t } from 'svelte-i18n';
    import { useForm } from "@inertiajs/svelte";
    import { writable } from 'svelte/store';

    import App from "@layouts/App.svelte";
    import ModalBox from '@components/UI/Modal/Box.svelte';

    let modal; // Store the modal instance reference

    const initialUser = { id: 1, name: 'Alice', email: 'alice@example.com' }; // Initial data
    const userApiUrl = 'http://127.0.0.1:8000/api/get/users?filters[id]=2'; // API route
 // API route

    // Function to open the modal and trigger data fetch
    function openUserModal() {
        modal.open(); // Access the modal's `open` method through the bound instance
    }

    // import TiptapEditor  from '@components/Fields/Tiptap/Editor.svelte';
    import CreateTaskForm from '@pages/Elements/Tasks/Forms/Store.svelte';


</script>

<svelte:head>
    <title>{$t('Log In')}</title>
</svelte:head>

<App>

    <div slot="hero" class="hero-contents">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            <i class="bi bi-check2-square me-2"></i> Create A Project
        </h2>
    </div>

    <CreateTaskForm />

</App>