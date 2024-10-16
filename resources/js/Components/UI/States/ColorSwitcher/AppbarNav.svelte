<script>
    import { onMount } from 'svelte';
    import { writable } from 'svelte/store';

    // Store to hold the theme state
    const theme = writable('auto');

    // Helper functions for storing theme state
    const getStoredTheme = () => localStorage.getItem('theme');
    const setStoredTheme = (theme) => localStorage.setItem('theme', theme);

    const getPreferredTheme = () => {
        const stored = getStoredTheme();
        if (stored) return stored;
        return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
    };

    const setTheme = (theme) => {
        if (theme === 'auto') {
            document.documentElement.setAttribute(
                'data-bs-theme',
                window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'
            );
        } else {
            document.documentElement.setAttribute('data-bs-theme', theme);
        }
    };

    const showActiveTheme = (selectedTheme, focus = false) => {
        const btnToActivate = document.querySelector(`[data-bs-theme-value="${selectedTheme}"]`);
        const iconClass = Array.from(btnToActivate.querySelector('i').classList).find((cls) =>
            cls.startsWith('bi-')
        );

        document.querySelectorAll('[data-bs-theme-value]').forEach((btn) => {
            btn.classList.remove('active');
            btn.setAttribute('aria-pressed', 'false');
        });

        btnToActivate.classList.add('active');
        btnToActivate.setAttribute('aria-pressed', 'true');

        const activeIcon = document.querySelector('.theme-icon-active');
        activeIcon.className = `bi ${iconClass}`;

        const themeSwitcher = document.getElementById('bd-theme');
        const themeSwitcherText = document.getElementById('bd-theme-text').textContent;
        themeSwitcher.setAttribute(
            'aria-label',
            `${themeSwitcherText} (${selectedTheme})`
        );

        if (focus) themeSwitcher.focus();
    };

    onMount(() => {
        // Initialize theme and listen for system theme changes
        const initialTheme = getPreferredTheme();
        theme.set(initialTheme);
        setTheme(initialTheme);
        showActiveTheme(initialTheme);

        // Handle changes in system's color scheme
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
            const stored = getStoredTheme();
            if (stored !== 'light' && stored !== 'dark') {
                const preferred = getPreferredTheme();
                theme.set(preferred);
                setTheme(preferred);
            }
        });
    });

    const handleThemeChange = (selectedTheme) => {
        theme.set(selectedTheme);
        setStoredTheme(selectedTheme);
        setTheme(selectedTheme);
        showActiveTheme(selectedTheme, true);
    };
</script>

<div class="flex-shrink-0 dropdown me-3 bd-mode-toggle">
    <button
        class="btn btn-bd-primary py-2 dropdown-toggle d-flex align-items-center"
        id="bd-theme"
        type="button"
        aria-expanded="false"
        data-bs-toggle="dropdown"
        aria-label="Toggle theme (auto)">
        <i class="bi bi-circle-half theme-icon-active"></i>
        <span class="visually-hidden" id="bd-theme-text">Toggle theme</span>
    </button>

    <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="bd-theme-text">
        {#each ['light', 'dark', 'auto'] as option}
            <li>
                <button
                    type="button"
                    class="dropdown-item d-flex align-items-center"
                    data-bs-theme-value={option}
                    aria-pressed={option === $theme}
                    on:click={() => handleThemeChange(option)}>
                    <i class={`bi bi-${option === 'light' ? 'sun-fill' : option === 'dark' ? 'moon-stars-fill' : 'circle-half'} me-2 opacity-50`}></i>
                    {option.charAt(0).toUpperCase() + option.slice(1)}
                    <i class="bi bi-check2 ms-auto d-none"></i>
                </button>
            </li>
        {/each}
    </ul>
</div>
