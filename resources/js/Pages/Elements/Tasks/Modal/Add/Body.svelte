<script>
// resources/js/Pages/Elements/Tasks/Modal/Add/Body.svelte

// Libraries
import { ray } from 'node-ray';
import { t } from 'svelte-i18n';

// Import the forms
import CreateTaskFormSingle from '@pages/Elements/Tasks/Forms/StoreSingle.svelte';
import CreateTaskFormMultiple from '@pages/Elements/Tasks/Forms/StoreMultiple.svelte';
import ButtonSwitch from '@components/UI/Buttons/Switch.svelte';

// Props
export let formInstance;
export let showSubmitButton = false;

// Local state for toggling forms
let showCreateMultipleTasks = false;

// Function to toggle the form display
function toggleForm() {
    showCreateMultipleTasks = !showCreateMultipleTasks;
}
</script>

<ButtonSwitch 
    id="add-multiple" 
    checked={showCreateMultipleTasks} 
    onToggle={toggleForm}
>
    <div class="ms-2">
        {$t('Add multiple')}
    </div>
</ButtonSwitch>

{#if !showCreateMultipleTasks}
    <CreateTaskFormSingle bind:this={formInstance} {showSubmitButton} />
{:else}
    <CreateTaskFormMultiple bind:this={formInstance} {showSubmitButton} />
{/if}
