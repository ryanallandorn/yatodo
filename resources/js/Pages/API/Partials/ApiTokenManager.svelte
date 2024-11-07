<script>

// resources/js/Pages/API/Partials/ApiTokenManager.svelte

    import { t } from 'svelte-i18n';
    import { ray } from 'node-ray';

    import { route } from 'ziggy-js';
    import axios from 'axios';

    import { useForm, page, router } from "@inertiajs/svelte";

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
    import ConfirmsPassword from '@components/ConfirmsPassword.svelte';

    // Props
    export let tokens = [];
    export let availablePermissions = [];
    export let defaultPermissions = [];

    // Reactive state
    let displayingToken = false;
    let managingPermissionsFor = null;
    let apiTokenBeingDeleted = null;
    let createTokenName = '';
    let selectedPermissions = [...defaultPermissions];
    let updatePermissions = [];
    let formErrors = {};
    let recentlySuccessful = false;
    let processing = false;
    let selectedTokenValue = null;

    //const createApiTokenForm = useForm({
    $: createApiTokenForm = useForm({
        name: '',
        permissions: defaultPermissions,
    });

    //const updateApiTokenForm = useForm({
    $: updateApiTokenForm = useForm({
        permissions: [],
    });

    const deleteApiTokenForm = useForm({});

    const createApiToken = () => {
        $createApiTokenForm.post(route('api-tokens.store'), {
            preserveScroll: true,
            onSuccess: () => {
                displayingToken = true;
                $createApiTokenForm.reset();
            },
        });
    };

    const manageApiTokenPermissions = (token) => {
        updateApiTokenForm.permissions = token.abilities;
        managingPermissionsFor = token;
    };

    const updateApiToken = () => {
        updateApiTokenForm.put(route('api-tokens.update', managingPermissionsFor), {
            preserveScroll: true,
            preserveState: true,
            onSuccess: () => (managingPermissionsFor = null),
        });
    };

    const confirmApiTokenDeletion = (token) => {
        apiTokenBeingDeleted = token;
    };

    const deleteApiToken = () => {
        deleteApiTokenForm.delete(route('api-tokens.destroy', apiTokenBeingDeleted), {
            preserveScroll: true,
            preserveState: true,
            onSuccess: () => (apiTokenBeingDeleted = null),
        });
    };

    async function showToken(token) {
        try {
            console.log('Token being passed:', token); // Debug log
            
            // Ensure we're passing the token ID as a string
            const tokenId = token.id.toString();
            
            // Pass token ID as named parameter object
            const response = await axios.get(route('api-tokens.show', { token: tokenId }), {
                headers: {
                    'Accept': 'application/json',
                    // Ensure you have CSRF token and authentication headers if needed
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                }
            });
            
            selectedTokenValue = response.data.token || 'No token found.';
            displayingToken = true;
        } catch (error) {
            console.error('Failed to fetch token:', error);
            // Log the actual error response if available
            if (error.response) {
                console.error('Error response:', error.response.data);
            }
            selectedTokenValue = 'Error fetching token.';
            displayingToken = false;
        }
    }

</script>


<FormSection on:submit={createApiToken}>
    <div slot="title">{$t('Create API Token')}</div>
    <div slot="description">{$t('API tokens allow third-party services to authenticate with our application on your behalf.')}</div>
    <div slot="form">
        <FieldText 
            form={$createApiTokenForm} 
            name="name" 
            id="name" 
            label="{$t('Name')}" 
        />
        {#if availablePermissions.length > 0}
            <div class="d-flex justify-content-start mt-3">
                {#each availablePermissions as permission}
                    <FieldSwitch form={$createApiTokenForm} name="selectedPermissions">
                        <span class="me-4 ms-0">
                            {permission}
                        </span>
                    </FieldSwitch>
                {/each}
            </div>
        {/if}
    </div>
    <div slot="actions">
        <ActionMessage on={$createApiTokenForm.recentlySuccessful}>{$t('Saved')}</ActionMessage>
        <Button
            cssClass="btn-outline-primary"
            type="submit"
            disabled={$createApiTokenForm.processing}
            aria-label="Save Profile"
        >
            {$t('Save')}
        </Button>
    </div>
</FormSection>


{#if (tokens.length )}
<SectionBorder />
<ActionSection>
    <div slot="title">{$t('Manage API Tokens')}</div>
    <div slot="description">
        {$t('You may delete any of your existing tokens if they are no longer needed.')}
    </div>
    <div slot="content">

        <div class="d-flex flex-column gap-3">
            {#each tokens as token (token.id)}
                <div class="d-flex align-items-center justify-content-between">
                    <div class="text-break text-light">
                        <ConfirmsPassword on:confirmed={() => showToken(token)}>
                            <Button cssClass="btn">
                                {token.name}
                            </Button>
                        </ConfirmsPassword>
                        <!-- <pre>{JSON.stringify(token, null, 4)}</pre> -->
                    </div>
                    <div class="d-flex align-items-center ms-2">
                        {#if token.last_used_ago}
                            <div class="small text-secondary">
                                Last used {token.last_used_ago}
                            </div>
                        {/if}
        
                        {#if availablePermissions.length > 0}
                            <button
                                class="btn btn-link btn-sm ms-3 text-secondary p-0"
                                on:click={() => manageApiTokenPermissions(token)}
                            >
                                Permissions
                            </button>
                        {/if}
        
                        <button 
                            class="btn btn-link btn-sm ms-3 text-danger p-0" 
                            on:click={() => confirmApiTokenDeletion(token)}
                        >
                            Delete
                        </button>
                    </div>
                </div>
            {/each}
        </div>
    </div>  
</ActionSection>
{/if}



<!-- Token Value Modal -->
<DialogModal bind:show={displayingToken} on:close={() => displayingToken = false}>
    <span slot="title">
        {$t('API Token')}
    </span>

    <div slot="content">
        <div>
            {$t('Please copy your new API token. For your security, it won\'t be shown again.')}
        </div>

        {#if $page.props.jetstream?.flash?.token}
            <div class="mt-4 bg-gray-100 dark:bg-gray-900 px-4 py-2 rounded font-mono text-sm text-gray-500 break-all">
                {$page.props.jetstream.flash.token}
            </div>
        {/if}
    </div>

    <div slot="footer">
        <Button 
            cssClass="btn-secondary" 
            on:click={() => displayingToken = false}
        >
            {$t('Close')}
        </Button>
    </div>
</DialogModal>

<!-- API Token Permissions Modal -->
<DialogModal 
    bind:show={managingPermissionsFor} 
    on:close={() => managingPermissionsFor = null}
>
    <span slot="title">
        {$t('API Token Permissions')}
    </span>

    <div slot="content">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            {#each availablePermissions as permission}
                <FieldSwitch 
                    form={$updateApiTokenForm} 
                    name="permissions" 
                    value={permission}
                >
                    <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">
                        {permission}
                    </span>
                </FieldSwitch>
            {/each}
        </div>
    </div>

    <div slot="footer">
        <Button 
            cssClass="btn-secondary" 
            on:click={() => managingPermissionsFor = null}
        >
            {$t('Cancel')}
        </Button>

        <Button
            cssClass="btn-primary ms-3"
            disabled={$updateApiTokenForm.processing}
            on:click={updateApiToken}
        >
            {$t('Save')}
        </Button>
    </div>
</DialogModal>

<!-- Delete Token Confirmation Modal -->
<DialogModal 
    bind:show={apiTokenBeingDeleted} 
    on:close={() => apiTokenBeingDeleted = null}
>
    <span slot="title">
        {$t('Delete API Token')}
    </span>

    <div slot="content">
        {$t('Are you sure you would like to delete this API token?')}
    </div>

    <div slot="footer">
        <Button 
            cssClass="btn-secondary" 
            on:click={() => apiTokenBeingDeleted = null}
        >
            {$t('Cancel')}
        </Button>

        <Button
            cssClass="btn-danger ms-3"
            disabled={$deleteApiTokenForm.processing}
            on:click={deleteApiToken}
        >
            {$t('Delete')}
        </Button>
    </div>
</DialogModal>