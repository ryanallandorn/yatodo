<script>

// resources/js/Pages/Auth/Login.svelte

    import { Link } from "@inertiajs/svelte";
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

    export let canResetPassword;
    export let status;

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
            <FieldPassword form={$form} name="password" id="password"  label="{$t('Password')}" />
            <div class="block mt-4 text-center">
                <FieldSwitch id="remember" name="remember" checked={true} className="d-inline-flex" required={true}>
                    <div class="ms-2">
                        {$t('Remember me')}
                    </div>
                </FieldSwitch>
            </div>
            <div class="flex items-center justify-end mt-4">
                <Button type="submit" disabled={form.processing} cssClass="btn-lg btn-primary w-100 py-2 mb-2">
                    {$t('Log In')}
                </Button>
                {#if canResetPassword}
                    <Link href={route('password.request')} class="mt-4 opacity-75">
                        {$t('Forgot password?')}
                    </Link>
                {/if}
            </div>
        </form>

    </AuthenticationCard>

</GuestLayout>