<script>

// resources/js/Pages/Elements/Tasks/Show/Modal.svelte

    import { t } from 'svelte-i18n';
    import ModalBox from '@components/UI/Modal/Box.svelte';
    import Accordion from '@components/UI/Accordion/Accordion.svelte';
    import AccordionItem from '@components/UI/Accordion/AccordionItem.svelte';
    import TiptapEditor  from '@components/Fields/Tiptap/Editor.svelte';

    export let apiGetRoute = null;
    export let displaySlot = '';
    export let modalTitle = '';
    
    let modal;
    
    const openModal = () => {
        modal.open();
    };

    const handleDataLoaded = (event) => {
        // Handle the loaded data if needed
        console.log('Data loaded:', event.detail);
    };
</script>

<span on:click={openModal} class="cursor-pointer text-primary interactable-underline">
    {displaySlot}
</span>

<ModalBox 
    bind:this={modal} 
    fetchUrl={apiGetRoute}
    on:dataLoaded={handleDataLoaded}
    showFooter={false}
>
    <span slot="title">{modalTitle}</span>
    
    <div slot="dynamicContent" let:fetchedData let:initialData>

        <div class="row align-items-center">
            <div class="col-9">
  
                <Accordion id="taskModalAccordions" cssClass="simplified-sides">
                    <AccordionItem 
                        headerId="taskModalAccordionDescription" 
                        collapseId="collapseModalAccordionDescription" 
                        isOpen={false}
                    >
                        <span slot="header">
                            {$t('Description')}
                        </span>
                        <div slot="body">

                            {route('api.update.task', { task: 1 })}
                            
                            <TiptapEditor
                                modelValue="<p>Hello, world!</p>"
                                autosave={true}
                                on:update={(event) => console.log('Editor content updated:', event.detail.value)}
                                apiPutRoute="{route('api.update.task', { task: 1 })}"
                            >
                            {#if fetchedData?.description}
                                {@html fetchedData.description}
                            {/if}
                            </TiptapEditor>
                        
                        </div>
                    </AccordionItem>
                    <AccordionItem 
                        headerId="taskModalAccordionComments" 
                        collapseId="collapseModalAccordionComments" 
                        isOpen={false}
                    >
                        <span slot="header">
                            {$t('Comments')}
                        </span>
                        <div slot="body">
                            {$t('Comments')}
                        </div>
                    </AccordionItem>
                    <AccordionItem 
                        headerId="taskModalAccordionFiles" 
                        collapseId="collapseModalAccordionFiles" 
                        isOpen={false}
                    >
                        <span slot="header">
                            {$t('Files')}
                        </span>
                        <div slot="body">
                            {$t('Files')}
                        </div>
                    </AccordionItem>
                    <AccordionItem 
                    headerId="taskModalAccordionActivity" 
                    collapseId="collapseModalAccordionActivity" 
                    isOpen={false}
                    >
                    <span slot="header">
                        {$t('Activity')}
                    </span>
                    <div slot="body">
                        {$t('Activity')}
                    </div>
                    </AccordionItem>
                    <AccordionItem 
                        headerId="headingData" 
                        collapseId="collapseData" 
                        isOpen={false}
                    >
                        <span slot="header">
                            Data
                        </span>
                        <div slot="body">
        
                        {#if fetchedData}
                            <!-- Dynamic content based on fetched data -->
                            <div class="dynamic-content">
                                <div>
                                    <h6>Fetched Data</h6>
                                    <pre>{JSON.stringify(fetchedData, null, 2)}</pre>
                                </div>
                            </div>
                        {/if}
        
                        {#if initialData}
                            <!-- Dynamic content based on fetched data -->
                            <div class="dynamic-content">
                                <div>
                                    <h6>Fetched Data</h6>
                                    <pre>{JSON.stringify(initialData, null, 2)}</pre>
                                </div>
                            </div>
                        {/if}
        
                        </div>
                    </AccordionItem>
                </Accordion>



            </div>
            <div class="col-3">
              Right fart
            </div>
          </div>



    </div>

    <div slot="staticContent" let:fetchedData let:initialData>
        <!-- TEST -->
    </div>

    <div slot="footer">
        <button type="button" class="btn btn-secondary" on:click={() => modal.close()}>
            {$t('Close')}
        </button>
    </div>
    
</ModalBox>