<script>

// resources/js/Pages/Auth/Login.svelte

    import { t } from 'svelte-i18n';

    import { useForm } from "@inertiajs/svelte";
    import GuestLayout from "@layouts/Guest.svelte";

    // Components > Branding
    import AuthenticationCard from '@components/Auth/Card.svelte';
    import AuthenticationLogo from '@components/Auth/Logo.svelte';

    // Components >Fields
    import Button from '@components/UI/Buttons/Button.svelte';
    import FieldCheckbox from '@components/Fields/Checkbox.svelte';
    import FieldSwitch from '@components/Fields/Switch.svelte';
    import FieldText from '@components/Fields/Text.svelte';
    import FieldPassword from '@components/Fields/Password.svelte';

    // Components > Forms
    import ValidationErrors from '@components/Forms/ValidationErrors.svelte';


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
    <title>{$t('Log In')}</title>
</svelte:head>

<GuestLayout>

    <AuthenticationCard>

        <AuthenticationLogo slot="logo" />

        <ValidationErrors errors={Object.values($form.errors)} />

        <form on:submit|preventDefault={submit} class="form-auth text-center">
            <FieldText form={$form} name="email" id="email" label="{$t('Email')}" />
            <FieldPassword form={$form} />
            <div class="block mt-4 text-center">
                <FieldSwitch id="terms" name="terms" checked={true} className="d-inline-flex" required={true}>
                    <div class="ms-2">
                        {$t('Remember me')}
                    </div>
                </FieldSwitch>
            </div>
            <div class="flex items-center justify-end mt-4">
                <Button type="submit"  disabled={form.processing} className="btn-lg btn-primary w-100 py-2 mb-2">
                    {$t('Log In')}
                </Button>
            </div>
        </form>

    </AuthenticationCard>

</GuestLayout>