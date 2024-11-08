<script>

// resources/js/Pages/Dashboard.svelte


    import { t } from 'svelte-i18n';
    import { useForm } from "@inertiajs/svelte";
    import { writable } from 'svelte/store';

    import App from "@layouts/App.svelte";

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