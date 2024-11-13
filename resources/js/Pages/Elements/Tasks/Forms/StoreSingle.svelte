<script>

// resources/js/Pages/Elements/Tasks/Forms/StoreSingle.svelte

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
    import DynamicTypeaheadSelect from '@components/Fields/DynamicTypeaheadSelect.svelte';

    // Components > Forms
    import ValidationErrors from '@components/Forms/ValidationErrors.svelte';
    


    export let task = null; 
    export let tasks = [];
    export let filtersFieldTask = {};
    export let showSubmitButton = true; 

    //const currentRoute = $page.url; // Full URL of the current page
    const currentRoute = route().current();
    const currentComponent = $page.component; // Name of the current component

    let form = useForm({
        name: '',
        project_id:null,
        parent_task_id:null,  
    });

    const formRoute = task ? route('tasks.update') : route('tasks.store');

    const submit = () => {
        console.log('Form values before submission:', $form);
        //ray($form).label('Form Data'); 

        $form.post(route('tasks.store'), {
            onFinish: () => {
                $form.reset('name'); // Reset the form field after submission if needed
                // Optionally, you could handle more actions here, like closing a modal
            }
        });
    };

    // Expose the submit function to the parent
    export function externalSubmit() {
        alert('called externalSubmit');
        submit();
    }


</script>




<form on:submit|preventDefault={submit} class="text-center">


    <DynamicTypeaheadSelect
        source="/api/get/projects"
        filters={filtersFieldTask}
        inputClass="custom-class mb-3"
        inputPlaceholder="{$t('Search projects...')}"
        name="project_id"
        label="{$t('Search Projects')}"
        bind:storedValue={$form.project_id}
        displayValue=""
        searchFields={['projects.name']}
    />

    <DynamicTypeaheadSelect
        source="/api/get/tasks"
        filters={filtersFieldTask}
        inputClass="custom-class mb-3"
        inputPlaceholder="{$t('Search tasks...')}"
        name="parent_task_id"
        label="{$t('Search Tasks')}"
        bind:storedValue={$form.parent_task_id}
        displayValue=""
        searchFields={['tasks.name', 'tasks.description']}
    />


    <FieldText form={$form} name="name" id="name" label="{$t('Name')}" />
    {#if showSubmitButton}
    <div class="flex items-center justify-end mt-4">
        <Button type="submit" disabled={$form.processing} cssClass="btn-lg btn-primary w-100 py-2 mb-2">
            {#if task } {$t('Update')} {:else} {$t('Create')} {/if}  {$t('Task')}
        </Button>
    </div>
    {/if}
    <div class="form-polling"></div>
    <div class="form-result"></div>
</form>