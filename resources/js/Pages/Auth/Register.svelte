<script>

    // resources/js/Pages/Auth/Login.svelte

    import { ray } from 'node-ray';
    
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
            name: '',
            email: '',
            password: '',
            password_confirmation: '',
            terms: false,
        });
    
        const submit = () => {
            console.log('Form values before submission:', $form);
            //ray($form).label('Form Data');  // Ray debugging

            $form.post(route('register'), {
                onFinish: () => $form.reset('password', 'password_confirmation'),
            });
        };
    </script>
    
    <svelte:head>
        <title>{$t('Log In')}</title>
    </svelte:head>
    
    <GuestLayout>
    
        <AuthenticationCard>
    
            <AuthenticationLogo slot="logo" />
    
            <ValidationErrors errors={Object.values($form.errors)} />
    
            <form on:submit|preventDefault={submit} class="form-auth text-center">
                <FieldText form={$form} name="name" id="name" label="{$t('Name')}" />
                <FieldText form={$form} name="email" id="email" label="{$t('Email')}" />
                <FieldPassword form={$form} name="password" id="password"  label="{$t('Password')}"/>
                <FieldPassword form={$form} name="password_confirmation" id="password_confirmation"  label="{$t('Confirm Password')}"/>
                <FieldCheckbox form={$form} name="terms">
                    <div class="ms-2">
                        I agree to the <a target="_blank" :href="route('terms.show')" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">Terms of Service</a> and <a target="_blank" :href="route('policy.show')" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">Privacy Policy</a>
                    </div>
                </FieldCheckbox>
                <div class="flex items-center justify-end mt-4">
                    <Button type="submit" disabled={form.processing} cssClass="btn-lg btn-primary w-100 py-2 mb-2">
                        {$t('Sign-up')}
                    </Button>
                    <Link href={route('login')} class="mt-4 opacity-75">
                        {$t('Already registered?')}
                    </Link>
                </div>
            </form>
    
        </AuthenticationCard>
    
    </GuestLayout>