<script>
    import { onMount, reactive } from 'svelte';
    import { createEventDispatcher } from 'svelte';
    import DialogModal from './__DialogModal.svelte';
    import InputError from './InputError.svelte';
    import PrimaryButton from './PrimaryButton.svelte';
    import SecondaryButton from './SecondaryButton.svelte';
    import TextInput from './TextInput.svelte';
    import axios from 'axios';

    // Props
    export let title = 'Confirm Password';
    export let content = 'For your security, please confirm your password to continue.';
    export let button = 'Confirm';

    const dispatch = createEventDispatcher(); // Emits events to the parent

    let confirmingPassword = false;
    let passwordInput;

    // Reactive form state
    let form = reactive({
        password: '',
        error: '',
        processing: false,
    });

    // Methods
    const startConfirmingPassword = async () => {
        try {
            const response = await axios.get(route('password.confirmation'));
            if (response.data.confirmed) {
                dispatch('confirmed'); // Emit confirmed event
            } else {
                confirmingPassword = true;
                setTimeout(() => passwordInput.focus(), 250); // Focus password input
            }
        } catch (error) {
            console.error('Error confirming password:', error);
        }
    };

    const confirmPassword = async () => {
        form.processing = true;
        try {
            await axios.post(route('password.confirm'), {
                password: form.password,
            });
            form.processing = false;
            closeModal();
            dispatch('confirmed'); // Emit confirmed event after close
        } catch (error) {
            form.processing = false;
            form.error = error.response?.data?.errors?.password[0] || 'An error occurred.';
            passwordInput.focus(); // Focus input on error
        }
    };

    const closeModal = () => {
        confirmingPassword = false;
        form.password = '';
        form.error = '';
    };
</script>

<span>
    <!-- Trigger -->
    <span on:click={startConfirmingPassword}>
        <slot />
    </span>

    <!-- Dialog Modal -->
    <DialogModal show={confirmingPassword} on:close={closeModal}>
        <h3 slot="title">{title}</h3>

        <div slot="content">
            {content}

            <div class="mt-4">
                <TextInput
                    bind:this={passwordInput}
                    bind:value={form.password}
                    type="password"
                    class="mt-1 block w-3/4"
                    placeholder="Password"
                    autocomplete="current-password"
                    on:keyup={(e) => e.key === 'Enter' && confirmPassword()}
                />
                <InputError message={form.error} class="mt-2" />
            </div>
        </div>

        <div slot="footer">
            <SecondaryButton on:click={closeModal}>Cancel</SecondaryButton>

            <PrimaryButton
                cssClass:opacity-25={form.processing}
                disabled={form.processing}
                on:click={confirmPassword}
            >
                {button}
            </PrimaryButton>
        </div>
    </DialogModal>
</span>
