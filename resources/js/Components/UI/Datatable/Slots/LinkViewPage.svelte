
<script>

// resources/js/Components/UI/Datatable/Slots/LinkViewPage.svelte

import { Link } from "@inertiajs/svelte";
import { t } from 'svelte-i18n';

    export let route = '';
    export let displaySlot = '';
</script>

<Link href={route} class="mt-4 opacity-75">
    {displaySlot}
</Link>
