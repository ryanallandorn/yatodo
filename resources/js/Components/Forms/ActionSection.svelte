<script>

// resources/js/Components/ActionSection.svelte

    import { createEventDispatcher } from 'svelte';
    import SectionTitle from '@components/SectionTitle.svelte';

    export let submit;

    const dispatch = createEventDispatcher();

    function handleSubmit(event) {
        event.preventDefault();
        dispatch('submit');
    }
</script>

<div class="row g-3">
    <div class="col-md-3">
        <SectionTitle>
            <span slot="title">
                <slot name="title"></slot>
            </span>
            <span slot="description">
                <slot name="description"></slot>
            </span>
        </SectionTitle>
    </div>
    <div class="col-md-9 mt-3">
        <div class="card">
            <div class="p-4">
                <slot name="content" />
            </div>
            {#if $$slots.actions}
                <div class="d-flex justify-content-end p-3 rounded-bottom card-footer">
                    <slot name="actions"></slot>
                </div>
            {/if}
        </div>
    </div>
</div>