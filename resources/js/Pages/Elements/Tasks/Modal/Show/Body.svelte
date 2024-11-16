<script>
// resources/js/Pages/Elements/Tasks/Index.svelte

    import { t } from 'svelte-i18n';
    import { useForm } from "@inertiajs/svelte";
    import { tick } from 'svelte';
    import App from "@layouts/App.svelte";

    import ModalBox from '@components/UI/Modal/Box.svelte';
    //import CreateTaskForm from '@pages/Elements/Tasks/Forms/StoreSingle.svelte';
    import ModalAddBody from '@pages/Elements/Tasks/Modal/Add/Body.svelte';
    import ModalShowBody from '@pages/Elements/Tasks/Modal/Show/Body.svelte';

    import Accordion from '@components/UI/Accordion/Accordion.svelte';
    
</script>