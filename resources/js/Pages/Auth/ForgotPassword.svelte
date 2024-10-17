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

        // import InputError from '@/Components/InputError.vue';
        // import InputLabel from '@/Components/InputLabel.vue';
    
        // Components > Forms
        import ValidationErrors from '@components/Forms/ValidationErrors.svelte';
    
        export let canResetPassword;
        export let status;
    
        let form = useForm({
            email: '',
        });
    
        const submit = () => {
            $form.post(route('password.email'), {
                onSubmit: () => form.reset('email'),
            });
        };
    </script>
    
    <svelte:head>
        <title>{$t('Log In')}</title>
    </svelte:head>
    
    <GuestLayout>
    
        <AuthenticationCard>
    
            <AuthenticationLogo slot="logo" />
            
            <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
                Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.
            </div>

            {#if status}
                <Link href={route('password.request')} class="mt-4 opacity-75">
                    {$t('Forgot password?')}
                </Link>
            {/if}

            <ValidationErrors errors={Object.values($form.errors)} />
    
            <form on:submit|preventDefault={submit} class="form-auth text-center">
                <FieldText form={$form} name="email" id="email" label="{$t('Email')}" />
                <div class="flex items-center justify-end mt-4">
                    <Button type="submit" disabled={$form.processing} cssClass="btn-lg btn-primary w-100 py-2 mb-2">
                        {$t('Email Password Reset Link')}
                    </Button>
                    <Link href={route('login')} class="mt-4 opacity-75">
                        {$t('Login')}
                    </Link>
                </div>
            </form>
    
        </AuthenticationCard>
    
    </GuestLayout>