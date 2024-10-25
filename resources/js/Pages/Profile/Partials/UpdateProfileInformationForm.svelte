<script>

    // /home/dorn/Dev/yatodo-inertia/resources/js/Pages/Profile/Partials/UpdateProfileInformationForm.svelte
    
        import { t } from 'svelte-i18n';
        import { ray } from 'node-ray';
    
        import { useForm, page, router } from '@inertiajs/svelte';
    
        // Components
        import FormSection from '@components/Forms/FormSection.svelte';
        import ActionMessage from '@components/Forms/ActionMessage.svelte';
        import UserAvatar from '@components/User/Avatar.svelte';
        import Button from '@components/UI/Buttons/Button.svelte';
        import FieldText from '@components/Fields/Text.svelte';
    
        let verificationLinkSent = false;
        let photoPreview = null;
        let photoInput;
    
        //$: user = $page?.props?.user; // Use optional chaining to prevent errors
        $: user = $page?.props?.auth?.user || {};
    
        // Reactive form object initialization
        $: form = useForm({
            _method: 'PUT',
            name: user.name || '',  // Reactively update when user changes
            email: user.email || '',
            photo: null,
        })
    
        const updateProfileInformation = () => {
            // const formData = new FormData();

            if (photoInput?.files?.[0]) {
                $form.photo = photoInput.files[0];
            }
            // Submit the form using a POST request with FormData
            $form.submit('post', route('user-profile-information.update'), {
                //_method: 'PUT',
                //forceFormData: true,
                preserveScroll: true,
                onSuccess: () => {
                    console.log('Form submission successful.');
                    clearPhotoFileInput(); // Reset photo input
                },
                onError: (errors) => {
                    console.error('Form submission failed:', errors);
                }
            });
            
        };

    
        const sendEmailVerification = () => {
            verificationLinkSent = true; // Correct usage
            console.log('Verification link sent.');
        };

    
        const selectNewPhoto = () => {
            if (photoInput) {
                photoInput.click(); // ✅ Correct
            } else {
                console.error("Photo input is not bound properly.");
            }
        };
    
        const updatePhotoPreview = () => {
            const photo = photoInput?.files?.[0]; // Optional chaining for safety
    
            if (!photo) return;
    
            const reader = new FileReader();
            reader.onload = (e) => {
                photoPreview = e.target.result; // Assign directly, no .value
            };
    
            reader.readAsDataURL(photo);
        };
    
    
        const deletePhoto = () => {
            router.delete(route('current-user-photo.destroy'), {
                preserveScroll: true,
                onSuccess: () => {
                    photoPreview = null; // Correct usage
                    clearPhotoFileInput();
                    console.log('Photo deleted.');
                },
            });
        };
    
        const clearPhotoFileInput = () => {
            if (photoInput) {
                photoInput.value = null; // Reset file input correctly
                photoPreview = null;     // Reset the preview if needed
                console.log('File input and photo preview cleared.');
            }
        };

            
    
    </script>
    
    <FormSection on:submit={updateProfileInformation}>
        <div slot="title">{$t('Profile Information')}</div>
        <div slot="description">{$t('Update your account\'s profile information and email address.')}</div>
        <div slot="form">
            {#if $page.props.jetstream.managesProfilePhotos}
    
            <div class="d-flex align-items-center gap-3 mb-4">
                <input
                    type="file"
                    class="d-none"
                    bind:this={photoInput}
                    on:change={updatePhotoPreview}
                />
            
                {#if !photoPreview}

                <UserAvatar 
                    profilePhotoUrl={user.profile_photo_url || 
                                `https://ui-avatars.com/api/?name=${encodeURIComponent(user.name)}&background=0D8ABC&color=fff`} 
                    name="{user.name}" 
                    width={32} 
                    height={32} 
                />

<!-- 
                {$user?.profile_photo_url} 

                    <img 
                        src={user?.profile_photo_url} 
                        alt={user?.name} 
                        class="rounded-circle" 
                        style="width: 50px; height: 50px; object-fit: cover;" 
                    /> -->
                {/if}
            
                {#if photoPreview}
                    <div 
                        class="rounded-circle bg-cover bg-center"
                        style="width: 50px; height: 50px; background-image: url('{photoPreview}');">
                    </div>
                {/if}
            
                <Button
                    cssClass="btn-outline-primary"
                    type="button"
                    on:click={selectNewPhoto}
                    disabled={form.processing}
                    aria-label="Select A New Photo"
                >
                    {$t('Select A New Photo')}
                </Button>
            
                {#if user?.profile_photo_path}
                    <button 
                        type="button" 
                        class="btn btn-outline-secondary"
                        on:click|preventDefault={deletePhoto}
                    >
                        {$t('Remove Photo')}
                    </button>
                {/if}
            </div>
            
    
    
        
    
    
    
            {/if}
    
    
            <FieldText 
                form={$form} 
                name="name" 
                id="name" 
                label="{$t('Name')}" 
            />
    
            <FieldText 
                form={$form} 
                name="email" 
                id="email" 
                label="{$t('email')}" 
            />  
    
        </div>
    
        <div slot="actions">
            <ActionMessage on={form.recentlySuccessful}>{$t('Saved')}</ActionMessage>
            <Button
                cssClass="btn-outline-primary"
                type="submit"
                disabled={form.processing}
                aria-label="Save Profile"
            >
                {$t('Save')}
            </Button>
        </div>
    </FormSection>
    
    
    
    
    <!-- 
    
    <div class="form-floating mb-3">
    <TextInput id="name" type="text" bind:value={form.name} required autocomplete="name" />
    <InputLabel for="name" value="Name" />
    <InputError message={form.errors.name} class="mt-2" />
    </div>
    
    <div class="form-floating mb-3">
    <TextInput id="email" type="email" bind:value={form.email} required autocomplete="username" />
    <InputLabel for="email" value="Email" />
    <InputError message={form.errors.email} class="mt-2" />
    
    {#if page.props.jetstream.hasEmailVerification && !user.email_verified_at}
        <p class="text-sm mt-2 dark:text-white">
            Your email address is unverified.
            <button
                type="button"
                class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                on:click|preventDefault={sendEmailVerification}
            >
                Click here to re-send the verification email.
            </button>
        </p>
    
        {#if verificationLinkSent}
            <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                A new verification link has been sent to your email address.
            </p>
        {/if}
    {/if}
    </div> -->