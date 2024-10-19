<script>

// resources/js/Pages/Profile/Partials/TwoFactorAuthenticationForm.svelte

    import { t } from 'svelte-i18n';
    import { router, useForm, page } from '@inertiajs/svelte'; 
    import ActionSection from '@components/Forms/ActionSection.svelte';
    import ActionMessage from '@components/Forms/ActionMessage.svelte';
    import ConfirmsPassword from '@components/ConfirmsPassword.svelte';
    import Button from '@components/UI/Buttons/Button.svelte';

    const enabling = false;
    const confirming = false;
    const disabling = false;
    const qrCode = null;
    const setupKey = null;
    const recoveryCodes = [];

    const confirmationForm = useForm({
        code: '',
    });

    // Computed equivalent
    $: twoFactorEnabled = !enabling && page?.props?.auth?.user?.two_factor_enabled;

    // Watch equivalent
    $: if (!twoFactorEnabled) {
        confirmationForm.reset?.();      // Optional chaining to avoid errors
        confirmationForm.clearErrors?.(); // Optional chaining
    }

    const enableTwoFactorAuthentication = () => {
        enabling.value = true;

        router.post(route('two-factor.enable'), {}, {
            preserveScroll: true,
            onSuccess: () => Promise.all([
                showQrCode(),
                showSetupKey(),
                showRecoveryCodes(),
            ]),
            onFinish: () => {
                enabling.value = false;
                confirming.value = props.requiresConfirmation;
            },
        });
    };

    const showQrCode = () => {
        return axios.get(route('two-factor.qr-code')).then(response => {
            qrCode.value = response.data.svg;
        });
    };

    const showSetupKey = () => {
        return axios.get(route('two-factor.secret-key')).then(response => {
            setupKey.value = response.data.secretKey;
        });
    }

    const showRecoveryCodes = () => {
        return axios.get(route('two-factor.recovery-codes')).then(response => {
            recoveryCodes.value = response.data;
        });
    };

    const confirmTwoFactorAuthentication = () => {
        confirmationForm.post(route('two-factor.confirm'), {
            errorBag: "confirmTwoFactorAuthentication",
            preserveScroll: true,
            preserveState: true,
            onSuccess: () => {
                confirming.value = false;
                qrCode.value = null;
                setupKey.value = null;
            },
        });
    };

    const regenerateRecoveryCodes = () => {
        axios
            .post(route('two-factor.recovery-codes'))
            .then(() => showRecoveryCodes());
    };

    const disableTwoFactorAuthentication = () => {
        disabling.value = true;

        router.delete(route('two-factor.disable'), {
            preserveScroll: true,
            onSuccess: () => {
                disabling.value = false;
                confirming.value = false;
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
        {#if twoFactorEnabled && !confirming}
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {$t('You have enabled two-factor authentication.')}
            </h3>
        {:else if twoFactorEnabled && confirming}
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {$t('Finish enabling two-factor authentication.')}
            </h3>
        {:else}
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {$t('You have not enabled two-factor authentication.')}
            </h3>
        {/if}

        <div class="mt-3 max-w-xl text-sm text-gray-600 dark:text-gray-400">
            <p>
                {$t("When two-factor authentication is enabled, you will be prompted for a secure, random token during authentication. You may retrieve this token from your phone's Google Authenticator application.")}
            </p>
        </div>

        {#if !twoFactorEnabled}
        <ConfirmsPassword on:confirmed={enableTwoFactorAuthentication}>
            <Button>{$t('Enable')}</Button>
        </ConfirmsPassword>
        {/if}
    </div>
    <div slot="actions">
        <ActionMessage on={$confirmationForm.recentlySuccessful}>
            {$t('Saved')}
        </ActionMessage>

        <Button
            cssClass="btn-outline-primary"
            type="submit"
            disabled={$confirmationForm.processing}
            aria-label="Save Profile"
        >
            {$t('Save')}
        </Button>
    </div>
</ActionSection>
