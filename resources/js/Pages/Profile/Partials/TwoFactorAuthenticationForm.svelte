<script>
    import { t } from 'svelte-i18n';
    import { writable, derived } from 'svelte/store';
    import { router, useForm, page } from '@inertiajs/svelte'; 
    import ActionSection from '@components/Forms/ActionSection.svelte';
    import ActionMessage from '@components/Forms/ActionMessage.svelte';
    import ConfirmsPassword from '@components/ConfirmsPassword.svelte';
    import Button from '@components/UI/Buttons/Button.svelte';
    import CodeBlock from '@components/UI/CodeBlock.svelte';
    import FieldText from '@components/Fields/Text.svelte';

    export let requiresConfirmation = false;

    // Create stores for reactive state
    const enabling = writable(false);
    const confirming = writable(false);
    const disabling = writable(false);
    const qrCode = writable(null);
    const setupKey = writable(null);
    const recoveryCodes = writable([]);

    // Derive twoFactorEnabled from page store and enabling state
    const twoFactorEnabled = derived(
        [page, enabling],
        ([$page, $enabling]) => !$enabling && $page?.props?.auth?.user?.two_factor_enabled
    );

    const confirmationForm = useForm({
        code: '',
    });

    // Watch equivalent using subscription
    twoFactorEnabled.subscribe(value => {
        if (!value) {
            confirmationForm.reset?.();
            confirmationForm.clearErrors?.();
        }
    });



    const enableTwoFactorAuthentication = () => {
        enabling.set(true);
        
        router.post(route('two-factor.enable'), {}, {
            preserveScroll: true,
            onSuccess: () => Promise.all([
                showQrCode(),
                showSetupKey(),
                showRecoveryCodes(),
            ]),
            onFinish: () => {
                enabling.set(false);
                confirming.set(requiresConfirmation);
            },
        });
    };

    const showQrCode = async () => {
        const response = await axios.get(route('two-factor.qr-code'));
        qrCode.set(response.data.svg);
    };

    const showSetupKey = async () => {
        const response = await axios.get(route('two-factor.secret-key'));
        setupKey.set(response.data.secretKey);
    };

    const showRecoveryCodes = async () => {
        const response = await axios.get(route('two-factor.recovery-codes'));
        recoveryCodes.set(response.data);
    };

    const confirmTwoFactorAuthentication = () => {
        $confirmationForm.post(route('two-factor.confirm'), {
            errorBag: "confirmTwoFactorAuthentication",
            preserveScroll: true,
            preserveState: true,
            onSuccess: () => {
                confirming.set(false);
                qrCode.set(null);
                setupKey.set(null);
            },
        });
    };

    const regenerateRecoveryCodes = () => {
        axios.post(route('two-factor.recovery-codes'))
            .then(() => showRecoveryCodes());
    };

    const disableTwoFactorAuthentication = () => {
        disabling.set(true);

        router.delete(route('two-factor.disable'), {
            preserveScroll: true,
            onSuccess: () => {
                disabling.set(false);
                confirming.set(false);
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
        {#if $twoFactorEnabled && !$confirming}
            <h3 class="h5">
                {$t('You have enabled two-factor authentication.')}
            </h3>
        {:else if $twoFactorEnabled && $confirming}
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

        {#if $twoFactorEnabled}
            {#if $qrCode}
                <div class="mt-4 text-body-secondary">
                    {#if $confirming}
                        <div class="alert alert-info" role="alert">
                            {$t('To finish enabling two-factor authentication, scan the following QR code using your phone\'s authenticator application or enter the setup key and provide the generated OTP code.')}
                        </div>
                    {:else}
                        <div class="alert alert-info" role="alert">
                            {$t('Two-factor authentication is now enabled. Scan the following QR code using your phone\'s authenticator application or enter the setup key.')}
                        </div>
                    {/if}
                </div>
                
                <div class="mt-4 p-2 d-inline-block bg-white">
                    {@html $qrCode}
                </div>
                
                {#if $setupKey}
                    <div class="mt-4 text-body-secondary">
                        <CodeBlock language="plaintext">
                            <span slot="title">Setup Key</span>
                            {@html $setupKey.trim()}
                        </CodeBlock>
                    </div>
                {/if}

                {#if $confirming}
                    <FieldText 
                        form={$confirmationForm} 
                        name="code" 
                        id="code" 
                        inputmode="numeric"
                        label="{$t('Code')}" 
                    />
                {/if}
            {/if}

            {#if $recoveryCodes.length > 0 && !$confirming}
                <div class="mt-4 text-body-secondary">
                    <div class="alert alert-warning" role="alert">
                        {$t('Store these recovery codes in a secure password manager. They can be used to recover access to your account if your two factor authentication device is lost.')}
                    </div>
                    <CodeBlock language="plaintext">
                        <span slot="title">Recovery Codes</span>
                        {@html $recoveryCodes.join('\n')}
                    </CodeBlock>
                </div>
            {/if}
        {/if}
    </div>

    <div slot="actions">
        {#if !$twoFactorEnabled}
            <ConfirmsPassword on:confirmed={enableTwoFactorAuthentication}>
                <Button cssClass="btn-outline-warning" disabled={$enabling}>
                    {$t('Enable')}
                </Button>
            </ConfirmsPassword>
        {:else}
            {#if $confirming}
                <ConfirmsPassword on:confirmed={disableTwoFactorAuthentication}>
                    <Button cssClass="btn-outline-secondary" disabled={$disabling}>
                        {$t('Cancel')}
                    </Button>
                </ConfirmsPassword>
                <ConfirmsPassword on:confirmed={confirmTwoFactorAuthentication}>
                    <Button cssClass="btn-outline-warning" disabled={$enabling}>
                        {$t('Confirm')}
                    </Button>
                </ConfirmsPassword>
            {/if}
            {#if $recoveryCodes.length > 0 && !$confirming}
                <ConfirmsPassword on:confirmed={regenerateRecoveryCodes}>
                    <Button cssClass="btn-outline-warning">
                        {$t('Regenerate Recovery Codes')}
                    </Button>
                </ConfirmsPassword>
            {/if}
            {#if $recoveryCodes.length === 0 && !$confirming}
                <ConfirmsPassword on:confirmed={showRecoveryCodes}>
                    <Button cssClass="btn-outline-warning">
                        {$t('Show Recovery Codes')}
                    </Button>
                </ConfirmsPassword>
            {/if}
            {#if !$confirming}
                <ConfirmsPassword on:confirmed={disableTwoFactorAuthentication}>
                    <Button cssClass="btn-outline-danger" disabled={$disabling}>
                        {$t('Disable')}
                    </Button>
                </ConfirmsPassword>
            {/if}
        {/if}
    </div>
</ActionSection>