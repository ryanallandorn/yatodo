<script>

// resources/js/Pages/Auth/Login.svelte

    import { ray } from 'node-ray';

    import { onMount, tick } from 'svelte';
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

    let recovery = false;
    let codeInput, recoveryCodeInput;

    const toggleRecovery = async () => {
        recovery = !recovery;
        await tick();

        if (recovery) {
            recoveryCodeInput.focus();
            form.code = '';
        } else {
            codeInput.focus();
            form.recovery_code = '';
        }
    };


    let form = useForm({
        code: '',
        recovery_code: '',
    });

    const submit = () => {
        // Log the form data on submit
        ray(form.data()).label('Form Data on Submit');

        $form.post(route('two-factor.login'), {
            onSubmit: () => {
                ray(form.data()).label('Form Data Submitted');
                $form.reset('password'); // Reset the password field
            },
            onSuccess: () => {
                ray(form.data()).label('Form Data on Success');
                console.log('Login successful');
            },
            onError: (errors) => {
                ray(errors).label('Form Errors');
                console.error('Login failed:', errors);
            },
        });
    };


</script>

<svelte:head>
    <title>{$t('Two-factor Confirmation')}</title>
</svelte:head>

<GuestLayout>

    <AuthenticationCard>

        <AuthenticationLogo slot="logo" />

        <ValidationErrors errors={Object.values($form.errors)} />

        <p class="fw-light fs-8 text-center p-3 py-1">
            {#if !recovery}
            {$t('Please confirm access to your account by entering the authentication code provided by your authenticator application.')}
            {:else}
            {$t('Please confirm access to your account by entering one of your emergency recovery codes.')}
            {/if}
        </p>

        <form on:submit|preventDefault={submit} class="form-auth text-center">
        {#if !recovery}
            <FieldText 
                form={$form} 
                id="code"
                ref="codeInput"
                inputmode="numeric"
                autofocus
                autocomplete="one-time-code"
                label="{$t('Code')}" 
            />
        {:else}
            <FieldText 
                form={$form} 
                id="recovery_code"
                ref="recoveryCodeInput"
                autocomplete="one-time-code"
                label="{$t('Recovery Code')}" 
            />
        {/if}

            <div class="flex items-center justify-end mt-4">
                <Button type="submit" disabled={$form.processing} cssClass="btn-lg btn-primary w-100 py-2 mb-2">
                    {$t('Log In')}
                </Button>
                <Button 
                    type="button"
                    cssClass="fs-8 fw-light"
                    on:click={toggleRecovery}
                >
                    {#if !recovery}
                        {$t('Use a recovery code')}
                    {:else}
                        {$t('Use an authentication code')}
                    {/if}
                    </Button>
            </div>
        </form>

    </AuthenticationCard>

</GuestLayout>