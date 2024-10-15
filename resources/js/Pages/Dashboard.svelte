<script>

// resources/js/Pages/Dashboard.svelte

    import { useForm } from "@inertiajs/svelte";
    import App from "@layouts/App.svelte";


    let form = useForm({
        email: '',
        password: ''
    });

    function submit() {
        $form.post('/login', {
            onSubmit: () => $form.reset('password'),
        });
    }
</script>

<svelte:head>
    <title>Log In</title>
</svelte:head>

<App>

    <!-- This content will be passed to the named "hero" slot -->
    <div slot="hero" class="hero-contents">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            <i class="bi bi-speedometer2 me-2"></i> Dashbaord
        </h2>
        <p>Additional content here, like a description or other elements.</p>
    </div>

    <!-- Main content -->
    <div>
        <p>Welcome to the Dashboard!</p>
    </div>

</App>