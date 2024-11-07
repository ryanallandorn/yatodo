<script>

// resources/js/Pages/Elements/Projects/Show/Page.svelte

    //
    import { ray } from 'node-ray';
    import { t } from 'svelte-i18n';
    import { useForm } from "@inertiajs/svelte";
    import { writable } from 'svelte/store';

    //
    import App from "@layouts/App.svelte";

    //
    export let project; 

</script>

<App>

    <div slot="hero" class="hero-contents">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            <i class="bi bi-check2-square"></i> 
            {$t('Project')}: {project.name} <!-- Display project name here -->
        </h2>
    </div>

        <!-- Additional project details -->
        <div class="project-details">
            <p>{project.description}</p> <!-- Example for other fields, adjust as needed -->
        </div>

</App>