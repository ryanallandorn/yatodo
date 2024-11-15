<script>

    // resources/js/Components/Structure/Appbar/UserNav.svelte
    
    import { page, inertia, Link } from "@inertiajs/svelte";
    import { t } from 'svelte-i18n';
    import UserAvatar from '@components/User/Avatar.svelte';
    
    </script>
    
    <div class="flex-shrink-0 dropdown me-3">
        <a href="/" class="d-block link-body-emphasis text-decoration-none dropdown-toggle position-relative" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-chat position-relative" style="font-size:2rem">
                <span style="font-size:0.75rem" class="d-flex justify-content-center align-items-center position-absolute top-50 start-50 translate-middle ms-n1 mt-n1">
                    99
                </span>
                <span style="font-size:0.65rem" 
                class="position-absolute top-0 start-100 translate-middle badge rounded-pill text-bg-secondary badge border border-light rounded-circle bg-danger ms-n2 mt-1">
                    3
                    <span class="visually-hidden">unread messages</span>
                </span>
            </i>

        </a>
        <ul class="dropdown-menu text-small shadow">
            <li>
                <div class="dropdown-header">
                    {$t('Manage Account')}
                </div>
            </li>
            <li>
                <Link href="{route('profile.show')}" class="dropdown-item">
                    {$t('Profile')}
                </Link>
            </li>
            {#if $page.props.jetstream.hasApiFeatures}
            <li>
                <Link href="{route('api-tokens.index')}" class="dropdown-item">
                    {$t('API Tokens')}
                </Link>
            </li>
            {/if}
            <li><hr class="dropdown-divider"></li>
            <li>
                <button use:inertia="{{ href: '/logout', method: 'post' }}" type="button" class="dropdown-item">
                    {$t('Logout')}
                </button>
            </li>
        </ul>
    </div>