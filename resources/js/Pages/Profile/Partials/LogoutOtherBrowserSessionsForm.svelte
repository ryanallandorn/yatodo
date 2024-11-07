<script>

// resources/js/Pages/Profile/Partials/LogoutOtherBrowserSessionsForm.svelte

    // Scripts > External
    import { t } from 'svelte-i18n';
    import { ray } from 'node-ray';
    import { route } from 'ziggy-js';
    import axios from 'axios';
    import { useForm, page, router } from "@inertiajs/svelte";

    // Scripts > Internal
    import { showToast } from '@scripts/toasts'; // Import the toast utility

    // Components
    import FormSection from '@components/Forms/FormSection.svelte';
    import ActionMessage from '@components/Forms/ActionMessage.svelte';
    import UserAvatar from '@components/User/Avatar.svelte';
    import Button from '@components/UI/Buttons/Button.svelte';
    import FieldText from '@components/Fields/Text.svelte';
    import FieldCheckbox from '@components/Fields/Checkbox.svelte';
    import FieldSwitch from '@components/Fields/Switch.svelte';
    import SectionBorder from '@components/SectionBorder.svelte'
    import ActionSection from '@components/Forms/ActionSection.svelte';
    import DialogModal from '@components/UI/Modal/Dialog.svelte';
    import FieldPassword from '@components/Fields/Password.svelte';
    import ConfirmsPassword from '@components/ConfirmsPassword.svelte';

    // Props
    export let sessions = [];

    // Reactive state
    let confirmingLogout = false;
    let passwordInput;

    //const updateApiTokenForm = useForm({
    $: form = useForm({
        password: '',
    });

    const confirmLogout = () => {
        confirmingLogout = true;
        // Use timeout to wait for modal to render
        setTimeout(() => {
            if (passwordInput && typeof passwordInput.focus === 'function') {
                passwordInput.focus();
            }
        }, 250);
    };


    const logoutOtherBrowserSessions = () => {
        ray('Attempting to log out from other browser sessions.');

        $form.delete(route('other-browser-sessions.destroy'), {
            preserveScroll: true,
            onSuccess: (response) => {
                ray('Logout successful.', response);
                showToast('Successfully logged out from other sessions.', 'success', 3000, 'bottom-center');
                closeModal();
            },
            onError: (error) => {
                ray('Error occurred:', error);
                if (passwordInput && typeof passwordInput.focus === 'function') {
                    passwordInput.focus();
                } else {
                    ray('passwordInput.focus is not a function');
                }
            },
            onFinish: () => {
                ray('Form reset complete.');
                $form.reset();
            },
        });
    };


    const closeModal = () => {
        confirmingLogout = false;

        $form.reset();
    };


    const removeSession = (sessionId) => {
        axios
            .delete(route('session.destroy', { id: sessionId }))
            .then(() => {
                showToast('Session removed successfully.', 'success', 3000, 'bottom-center');
                sessions = sessions.filter((session) => session.id !== sessionId);
            })
            .catch(() => {
                showToast('Failed to remove session.', 'error', 3000, 'bottom-center');
            });
    };

</script>



<ActionSection>
    <div slot="title">{$t('Browser Sessions')}</div>
    <div slot="description">
        {$t('Manage and log out your active sessions on other browsers and devices.')}
    </div>
    <div slot="content">
        <div class="max-w-xl text-sm text-gray-600 dark:text-gray-400">
            If necessary, you may log out of all of your other browser sessions across all of your devices. Some of your recent sessions are listed below; however, this list may not be exhaustive. If you feel your account has been compromised, you should also update your password.
        </div>
        {#if sessions.length > 0}
            <table class="table table-striped table-active-sessions mt-3">
                <tbody>
                    {#each sessions as session, i}
                    <tr>
                        <td>
                        {#if session?.agent?.is_desktop}
                            <svg class="w-8 h-8 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15V5.25m18 0A2.25 2.25 0 0018.75 3H5.25A2.25 2.25 0 003 5.25m18 0V12a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 12V5.25" />
                            </svg>
                        {:else}
                            <svg class="w-8 h-8 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3" />
                            </svg>
                        {/if}
                        </td>
                        <td>
                            <div class="text-sm text-gray-600 dark:text-gray-400">
                                {session?.agent?.platform || 'Unknown'} - {session?.agent?.browser || 'Unknown'}
                            </div>
                            <!-- <pre>{JSON.stringify(session, null, 4)}</pre> -->
                        </td>
                        <td>
                            <div class="text-xs text-gray-500">
                                {session?.ip_address || 'No IP address'},
                                {#if session?.is_current_device}
                                    <span class="text-success fw-semibold">This device</span>
                                {:else}
                                    Last active {session?.last_active || 'Unknown'}
                                {/if}
                            </div>
                        </td>
                        <td>
                            {#if !session.is_current_device}
                                <Button 
                                    cssClass="btn-outline-danger btn-sm" 
                                    on:click={() => removeSession(session.id)}
                                >
                                    {$t('Remove')}
                                </Button>
                            {/if}
                        </td>
                    </tr>
                    {/each}
                </tbody>
            </table>
        {/if}
    </div>
    <div slot="actions">
        <Button 
            cssClass="btn-outline-secondary" 
            on:click={confirmLogout}
        >
            {$t('Log Out Other Browser Sessions')}
        </Button>
    </div>
</ActionSection>



<!-- Token Value Modal -->
<DialogModal bind:show={confirmingLogout} on:close={() => confirmingLogout = false}>
    <span slot="title">
        {$t('Log Out Other Browser Sessions')}
    </span>

    <div slot="content">
        <div>
            {$t(' Please enter your password to confirm you would like to log out of your other browser sessions across all of your devices.')}
        </div>

        <div class="mt-4">
        <FieldPassword 
            bind:this={passwordInput} 
            form={$form} 
            name="password" 
            id="password"  
            label="{$t('Password')}"
        />
        </div>
    </div>

    <div slot="footer">
        <Button 
            cssClass="btn-outline-secondary" 
            on:click={closeModal}
        >
            {$t('Cancel')}
        </Button>
        <Button 
            cssClass="btn-warning" 
            on:click={logoutOtherBrowserSessions}
        >
            {$t('Log Out Other Browser Sessions')}
        </Button>
    </div>
</DialogModal>