<script>

// /home/dorn/Dev/yatodo-inertia/resources/js/Pages/Profile/Partials/UpdateProfileInformationForm.svelte

    import { t } from 'svelte-i18n';

    import { useForm, page } from '@inertiajs/svelte'; // Ensure proper import

    //
    import FormSection from '@components/Forms/FormSection.svelte';
    import ActionMessage from '@components/Forms/ActionMessage.svelte';

    //
    import Button from '@components/UI/Buttons/Button.svelte';
    import FieldText from '@components/Fields/Text.svelte';
    import FieldPassword from '@components/Fields/Password.svelte';

    let photoInput;
    let photoPreview = null;
    let photoName = null;
    let verificationLinkSent = false;

    //$: user = $page?.props?.user; // Use optional chaining to prevent errors
    $: user = $page?.props?.auth?.user || {};

    // Reactive form object initialization
    const form = useForm({
        current_password: '',
        password: '',
        password_confirmation: '',
    })

    const updatePassword = () => {
        $form.put(route('user-password.update'), {
            errorBag: 'updatePassword',
            preserveScroll: true,
            onSuccess: () => $form.reset(),
            onError: () => {
                if ($form.errors.password) {
                    $form.reset('password', 'password_confirmation');
                    passwordInput.value.focus();
                }

                if ($form.errors.current_password) {
                    $form.reset('current_password');
                    currentPasswordInput.value.focus();
                }
            },
        });
    };

</script>

<FormSection on:submit={updatePassword}>

    <div slot="title">{$t('Update Password')}</div>
    <div slot="description">{$t('Ensure your account is using a long, random password to stay secure.')}</div>

    <div slot="form">
        <FieldPassword form={$form} name="current_password" id="current_password"  label="{$t('Current Password')}"/>
        <FieldPassword form={$form} name="password" id="password"  label="{$t('New Password')}"/>
        <FieldPassword form={$form} name="password_confirmation" id="password_confirmation"  label="{$t('Confirm Password')}"/>
    </div>

    <div slot="actions">
        <ActionMessage on={form.recentlySuccessful}>{$t('Saved')}</ActionMessage>

        <Button
            cssClass="btn-outline-primary"
            type="submit"
            disabled={form.processing}
            aria-label="Save Profile"
        >
            {$t('Save')}
        </Button>
    </div>
</FormSection>