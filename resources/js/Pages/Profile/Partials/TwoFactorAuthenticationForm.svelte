<script>

// resources/js/Pages/Profile/Partials/TwoFactorAuthenticationForm.svelte

    import { t } from 'svelte-i18n';
    import { writable } from 'svelte/store';
    import { router, useForm, page } from '@inertiajs/svelte'; 
    import ActionSection from '@components/Forms/ActionSection.svelte';
    import ActionMessage from '@components/Forms/ActionMessage.svelte';
    import ConfirmsPassword from '@components/ConfirmsPassword.svelte';
    import Button from '@components/UI/Buttons/Button.svelte';
    import CodeBlock from '@components/UI/CodeBlock.svelte';
    import FieldText from '@components/Fields/Text.svelte';

    export let requiresConfirmation = false;

    // // Reactive state
    // let enabling = writable(false);
    // let confirming = writable(false);
    // let twoFactorEnabled = writable(false);
    // let disabling = writable(false);
    // let qrCode = writable(false);
    // let setupKey = writable(false);
    // let recoveryCodes = [];

    // let enabling = false;
    // let confirming = false;
    // let disabling = false;
    // let qrCode = null;
    // let setupKey = null;
    // let recoveryCodes = [];


    let enabling = writable(false);
    let confirming = writable(false);
    let twoFactorEnabled = writable(false);
    let disabling = writable(false);
    let qrCode = writable(null);
    let setupKey = writable(null);
    let recoveryCodes = writable([]);

    // Computed equivalent
    $: twoFactorEnabled = !enabling && $page?.props?.auth?.user?.two_factor_enabled;

    // Watch equivalent
    $: if (!twoFactorEnabled) {
        confirmationForm.reset?.();      // Optional chaining to avoid errors
        confirmationForm.clearErrors?.(); // Optional chaining
    }

    const confirmationForm = useForm({
        code: '',
    });

    const enableTwoFactorAuthentication = () => {
        enabling = true;
        router.post(route('two-factor.enable'), {}, {
            preserveScroll: true,
            onSuccess: () => Promise.all([
                showQrCode(),
                showSetupKey(),
                showRecoveryCodes(),
            ]),
            onFinish: () => {
                enabling = false;
                confirming = requiresConfirmation;
            },
        });
    };

    const showQrCode = () => {
        return axios.get(route('two-factor.qr-code')).then(response => {
            qrCode = response.data.svg;
        });
    };

    const showSetupKey = () => {
        return axios.get(route('two-factor.secret-key')).then(response => {
            setupKey = response.data.secretKey;
        });
    }

    const showRecoveryCodes = () => {
        return axios.get(route('two-factor.recovery-codes')).then(response => {
            recoveryCodes = response.data;
        });
    };

    const confirmTwoFactorAuthentication = () => {
        $confirmationForm.post(route('two-factor.confirm'), {
            errorBag: "confirmTwoFactorAuthentication",
            preserveScroll: true,
            preserveState: true,
            onSuccess: () => {
                confirming = false;
                qrCode = null;
                setupKey = null;
            },
        });
    };

    const regenerateRecoveryCodes = () => {
        axios
            .post(route('two-factor.recovery-codes'))
            .then(() => showRecoveryCodes());
    };

    const disableTwoFactorAuthentication = () => {
        disabling = true;

        router.delete(route('two-factor.disable'), {
            preserveScroll: true,
            onSuccess: () => {
                disabling = false;
                confirming = false;
            },
        });
    };

</script>

<ActionSection>
    <div slot="title">{$t('Two Factor Authentication')}</div>
    <div slot="description">
        {$t('Add additional security to your account using two-factor authentication.')}
    </div>
    <div slot="content">

        <pre>{JSON.stringify($page?.props?.auth?.user?.two_factor_enabled, null, 4)}</pre>

        <pre>{JSON.stringify($page?.props?.auth?.user?.two_factor_enabled, null, 4)}</pre>

        {#if twoFactorEnabled && !confirming}
            <h3 class="h5">
                {$t('You have enabled two-factor authentication.')}
            </h3>
        {:else if twoFactorEnabled && confirming}
            <h3 class="h5">
                {$t('Finish enabling two-factor authentication.')}
            </h3>
        {:else}
            <h3 class="h5">
                {$t('You have not enabled two-factor authentication.')}
            </h3>
        {/if}

        <div class="mt-3 max-w-xl text-sm text-gray-600 dark:text-gray-400">
            <div class="alert alert-secondary" role="alert">
                {$t("When two-factor authentication is enabled, you will be prompted for a secure, random token during authentication. You may retrieve this token from your phone's Google Authenticator application.")}
            </div>
        </div>

        {#if twoFactorEnabled}

            {#if qrCode}
                <div class="mt-4 text-body-secondary">
                    {#if confirming}
                        <div class="alert alert-info" role="alert">
                        {$t('To finish enabling two-factor authentication, scan the following QR code using your phone\'s authenticator application or enter the setup key and provide the generated OTP code.')}
                        </div>
                    {:else}
                    <div class="alert alert-info" role="alert">
                        {$t('Two-factor authentication is now enabled. Scan the following QR code using your phone\'s authenticator application or enter the setup key.')}
                        </div>
                    {/if}
                </div>
                
                <!-- Display the QR code -->
                <div class="mt-4 p-2 d-inline-block bg-white">
                    {@html qrCode}
                </div>
                
                <!-- Conditionally render the setup key -->
                {#if setupKey}
                    <div class="mt-4 text-body-secondary">
                        <p class="fw-semibold">
                            <CodeBlock language="plaintext">
                                <span slot="title">Setup Key</span>
                                {@html setupKey.trim()}
                            </CodeBlock>
                        </p>
                    </div>
                {/if}

                {#if confirming}

    
                <FieldText 
                    form={$confirmationForm} 
                    name="code" 
                    id="code" 
                    inputmode="numeric"
                    label="{$t('Code')}" 
                />

                {/if}

            {/if}

        {/if}


    </div>
    <div slot="actions">

        {#if !twoFactorEnabled}
            <ConfirmsPassword on:confirmed={enableTwoFactorAuthentication}>
                <Button cssClass="btn-outline-warning">{$t('Enable')}</Button>
            </ConfirmsPassword>
        {:else}
            {#if confirming}
            <ConfirmsPassword on:confirmed={disableTwoFactorAuthentication}>
                <Button cssClass="btn-outline-secondary">{$t('Cancel')}</Button>
            </ConfirmsPassword>
            <ConfirmsPassword on:confirmed={confirmTwoFactorAuthentication}>
                <Button cssClass="btn-outline-warning">{$t('Confirm')}</Button>
            </ConfirmsPassword>
            {/if}
            {#if recoveryCodes.length > 0 && ! confirming}
            <ConfirmsPassword on:confirmed={regenerateRecoveryCode}>
                <Button cssClass="btn-outline-warning">{$t('Regenerate Recovery Codes')}</Button>
            </ConfirmsPassword>
            {/if}
            {#if recoveryCodes.length === 0 && ! confirming}
            <ConfirmsPassword on:confirmed={showRecoveryCodes}>
                <Button cssClass="btn-outline-warning">{$t('Show Recovery Codes')}</Button>
            </ConfirmsPassword>
            {/if}
            {#if !confirming}
            <ConfirmsPassword on:confirmed={disableTwoFactorAuthentication}>
                <Button cssClass="btn-outline-danger">{$t('Disable')}</Button>
            </ConfirmsPassword>
            {/if}
        {/if}

        <!-- <ActionMessage on={$confirmationForm.recentlySuccessful}>
            {$t('Saved')}
        </ActionMessage>

        <Button
            cssClass="btn-outline-primary"
            type="submit"
            disabled={$confirmationForm.processing}
            aria-label="Save Profile"
        >
            {$t('Save')}
        </Button> -->
    </div>
</ActionSection>
