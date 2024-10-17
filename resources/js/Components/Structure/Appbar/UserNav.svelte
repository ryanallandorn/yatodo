<script>

// resources/js/Components/Structure/Appbar/UserNav.svelte

import { page, inertia, Link } from "@inertiajs/svelte";
import { t } from 'svelte-i18n';
import UserAvatar from '@components/User/Avatar.svelte';

</script>

<div class="flex-shrink-0 dropdown me-3">
    <a href="/" class="d-block link-body-emphasis text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
        {#if $page.props.jetstream.managesProfilePhotos}
        <UserAvatar 
            profilePhotoUrl={$page.props.auth.user.profile_photo_url || 
                        `https://ui-avatars.com/api/?name=${encodeURIComponent($page.props.auth.user.name)}&background=0D8ABC&color=fff`} 
            name="{$page.props.auth.user.name}" 
            width={32} 
            height={32} 
        />
      {:else}
        <span>{$page.props.auth.user.name}</span>
      {/if}
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