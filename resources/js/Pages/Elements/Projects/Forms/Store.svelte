<script>

    // resources/js/Pages/Auth/Login.svelte
    
        import { ray } from 'node-ray';
        import { t } from 'svelte-i18n';
        import { Link, page, useForm } from "@inertiajs/svelte";

    

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
    
        import DynamicTypeaheadSelect from '@components/Fields/DynamicTypeaheadSelect.svelte';
        export let project = null; 
        export let projects = [];
        export let filtersFieldProject = {};

        //const currentRoute = $page.url; // Full URL of the current page
        const currentRoute = route().current();
        const currentComponent = $page.component; // Name of the current component
    
        let form = useForm({
            name: '',
        });

        const formRoute = project ? route('projects.update') : route('projects.store');

        const submit = () => {
            console.log('Form values before submission:', $form);
            //ray($form).label('Form Data'); 
    
            $form.post(formRoute, {
               // onFinish: () => $form.reset('name'),
            });
        };
    </script>
    

<p>Current Route: {currentRoute}</p>
<p>Current Component: {currentComponent}</p>


<form on:submit|preventDefault={submit} class="form-auth text-center">

    <FieldText form={$form} name="name" id="name" label="{$t('Name')}" />
    <div class="flex items-center justify-end mt-4">
        <Button type="submit" disabled={form.processing} cssClass="btn-lg btn-primary w-100 py-2 mb-2">
            {#if project } {$t('Update')} {:else} {$t('Create')} {/if}  {$t('Project')}
        </Button>
    </div>
    <div class="form-polling"></div>
    <div class="form-result"></div>

</form>