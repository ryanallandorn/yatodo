<script>
// resources/js/Pages/Elements/Notes/Modal/Add/Body.svelte

// Libraries
import { ray } from 'node-ray';
import { t } from 'svelte-i18n';

// Import the forms
import CreateTaskFormSingle from '@pages/Elements/Notes/Forms/StoreSingle.svelte';
import CreateTaskFormMultiple from '@pages/Elements/Notes/Forms/StoreMultiple.svelte';
import ButtonSwitch from '@components/UI/Buttons/Switch.svelte';

// Props
export let formInstance;
export let showSubmitButton = false;

// Local state for toggling forms
let showCreateMultipleNotes = false;

// Function to toggle the form display
function toggleForm() {
    showCreateMultipleNotes = !showCreateMultipleNotes;
}
</script>

<ButtonSwitch 
    id="add-multiple" 
    checked={showCreateMultipleNotes} 
    onToggle={toggleForm}
>
    <div class="ms-2">
        {$t('Add multiple')}
    </div>
</ButtonSwitch>

{#if !showCreateMultipleNotes}
    <CreateTaskFormSingle bind:this={formInstance} {showSubmitButton} />
{:else}
    <CreateTaskFormMultiple bind:this={formInstance} {showSubmitButton} />
{/if}
