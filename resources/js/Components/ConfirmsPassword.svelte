
<script>


// resources/js/Components/ConfirmsPassword.svelte

    import { onMount } from 'svelte'; // For focusing input on mount
    import { writable } from 'svelte/store';
    import DialogModal from '@components/DialogModal.svelte';
    import InputError from '@components/Forms/InputError.svelte'; 
    import Button from '@components/UI/Buttons/Button.svelte';
    import TextInput from '@components/Fields/Text.svelte';
    import axios from 'axios';

    export let title = 'Confirm Password';
    export let content = 'For your security, please confirm your password to continue.';
    export let button = 'Confirm';

    const confirmingPassword = writable(false);

    const form = writable({
        password: '',
        error: '',
        processing: false,
    });

    let passwordInput;

    const startConfirmingPassword = async () => {
        try {
            const response = await axios.get(route('password.confirmation'));
            if (response.data.confirmed) {
                confirmed();
            } else {
                confirmingPassword.set(true);
                await new Promise(resolve => setTimeout(resolve, 250));
                passwordInput?.focus();
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
            confirmed();
        } catch (error) {
            form.update(f => ({
                ...f,
                processing: false,
                error: error.response.data.errors.password[0],
            }));
            passwordInput?.focus();
        }
    };

    const closeModal = () => {
        confirmingPassword.set(false);
        form.set({ password: '', error: '', processing: false });
    };

    function confirmed() {
        const event = new CustomEvent('confirmed');
        dispatchEvent(event); // Emit the 'confirmed' event
    }
</script>

<span>
    <span on:click={startConfirmingPassword}>
        <slot />
    </span>

    <DialogModal {confirmingPassword} on:close={closeModal}>
        <h2 slot="title">{title}</h2>

        <div slot="content">
            <p>{content}</p>
            <div class="mt-4">
                <TextInput
                    bind:this={passwordInput}
                    bind:value={$form.password}
                    type="password"
                    class="mt-1 block w-3/4"
                    placeholder="Password"
                    autocomplete="current-password"
                    on:keyup={(e) => e.key === 'Enter' && confirmPassword()}
                />
                <InputError message={$form.error} class="mt-2" />
            </div>
        </div>

        <div slot="footer">
            <Button on:click={closeModal}>Cancel</Button>
            <Button
                class={`ms-3 ${$form.processing ? 'opacity-25' : ''}`}
                disabled={$form.processing}
                on:click={confirmPassword}
            >
                {button}
            </Button>
        </div>
    </DialogModal>
</span>
