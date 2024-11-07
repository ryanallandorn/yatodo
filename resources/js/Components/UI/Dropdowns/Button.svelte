<script>
    import { onMount } from 'svelte';

    export let buttonCss = 'btn-primary'; // Customizable button CSS classes
    export let tooltip = ''; // Tooltip text
    export let closeAction = 'default'; // Controls the dropdown close behavior

    let buttonRef;
    let dropdownRef;

    const handleDropdownClick = (event) => {
        if (closeAction === 'clickOff') {
            event.stopPropagation(); // Prevents dropdown from closing on click outside
        }
    };

    const handleButtonClick = (event) => {
        if (closeAction === 'button') {
            event.stopPropagation(); // Prevents dropdown from closing when button is clicked again
        }
    };

    onMount(() => {
        // Initialize Bootstrap tooltip on the button
        if (tooltip) {
            new bootstrap.Tooltip(buttonRef);
        }

        if (closeAction === 'clickOff' || closeAction === 'button') {
            dropdownRef?.addEventListener('click', handleDropdownClick);
        }

        if (closeAction === 'button') {
            buttonRef?.addEventListener('click', handleButtonClick);
        }
    });
</script>

<div class="dropdown" bind:this={dropdownRef}>
    <!-- Button with Tooltip -->
    <button
        bind:this={buttonRef}
        class={`btn dropdown-toggle ${buttonCss}`}
        type="button"
        data-bs-toggle="dropdown"
        aria-expanded="false"
        title={tooltip}
    >
        <slot name="button">Dropdown Button</slot>
    </button>

    <!-- Dropdown Menu -->
    <div class="dropdown-menu">
        <slot name="menu"></slot>
    </div>
</div>

<style>
    .dropdown-menu {
        width: 200px;
    }
</style>
