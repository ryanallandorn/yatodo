<script>
    import { t } from 'svelte-i18n';
    import { writable } from 'svelte/store';
    import ModalDialog from '@components/UI/Modal/Dialog.svelte'; 
    import InputError from '@components/Forms/InputError.svelte';
    import Button from '@components/UI/Buttons/Button.svelte';
    import FieldPassword from '@components/Fields/Password.svelte';
    import axios from 'axios';

    export let title = 'Confirm Password';
    export let content = 'For your security, please confirm your password to continue.';
    export let button = 'Confirm';

    let showConfirmingPassword = false;
    let confirmingPassword = false;

    import {  tick, createEventDispatcher } from 'svelte';
    const dispatch = createEventDispatcher();

    const form = writable({
        password: '',
        error: '',
        processing: false,
    });

    let passwordInput = null;
    const startConfirmingPassword = async () => {
        try {
            const response = await axios.get(route('password.confirmation'));
            if (response.data.confirmed) {
                //confirmed();
                dispatch('confirmed'); 
                //alert(1);
            } else {
                //alert(2);
                confirmingPassword = true;
                showConfirmingPassword = true;
                // await tick(); // Ensure DOM updates before focusing
                // passwordInput?.focus(); 
            }
        } catch (error) {
            console.error('Error confirming password:', error);
        }
    };

    const confirmPassword = async () => {
        form.update(f => ({ ...f, processing: true }));
        try {
            await axios.post(route('password.confirm'), { password: $form.password });
            form.update(f => ({ ...f, processing: false }));
            closeModal();
            //confirmed();
            dispatch('confirmed'); 
            //alert('confirmPassword: SUCCESS');
        } catch (error) {
            //alert('confirmPassword: FAIL');
            form.update(f => ({
                ...f,
                processing: false,
                error: error.response?.data?.errors?.password?.[0] || 'Error confirming password',
            }));
            passwordInput?.focus(); 
        }
    };

    const closeModal = () => {
        confirmingPassword = false;
        showConfirmingPassword = false;
        form.set({ password: '', error: '', processing: false });
    };

    function confirmed() {
        const event = new CustomEvent('confirmed');
        dispatchEvent(event);
    }
</script>

<!-- Trigger for Password Confirmation -->
<span on:click={startConfirmingPassword}>
    <slot />
</span>

<!-- Password Confirmation Modal -->
<ModalDialog
    bind:show={showConfirmingPassword}
    maxWidth="sm"
    on:close={closeModal}
>
    <div slot="title">
        {title}
    </div>

    <div slot="content">
        <p>{content}</p>
        <div class="mt-4">
            <FieldPassword 
                bind:this={passwordInput} 
                form={$form} 
                name="password" 
                id="password" 
                label="{$t('Password')}"
            />
            <InputError message={$form.error} class="mt-2" />
        </div> <!-- Closing inner div -->
    </div> <!-- Closing content slot -->

    <div slot="footer">
        <Button 
            cssClass="btn-secondary" 
            on:click={closeModal}
        >
            {$t('Cancel')}
        </Button>

        <Button
            cssClass="btn-primary ms-3"
            disabled={$form.processing}
            on:click={confirmPassword}
        >
            {button}
        </Button>
    </div>
</ModalDialog>
