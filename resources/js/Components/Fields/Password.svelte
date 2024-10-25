<script>
    // resources/js/Components/Fields/Password.svelte
    
        import { t } from 'svelte-i18n';
        export let form; // Accept the form object as a prop
        export let id = 'password'; // Default id for the input field
        export let name = 'password'; // Default name for the input field
        export let attributes = {};
        export let className = '';
        export let fieldInputCss = '';
        export let fieldWrapperCss = '';
        export let label = ''; // Default label for the input field
        let showPassword = false; // Reactive variable to toggle password visibility
        let inputRef;
    </script>
    
    <div class="input-group position-relative">
        <div class={`form-floating ${fieldWrapperCss}`}>
            <!-- Conditionally render based on showPassword -->
            {#if showPassword}
                <input 
                    bind:this={inputRef}
                    type="text" 
                    id={id}
                    name={name}
                    bind:value={form[name]}
                    placeholder="Password" 
                    class={`form-control ${fieldInputCss}`}
                >
            {:else}
                <input 
                    bind:this={inputRef}
                    type="password" 
                    id={id}
                    name={name}
                    bind:value={form[name]}
                    placeholder="Password" 
                    class={`form-control ${fieldInputCss}`}
                >
            {/if}
    
            <label class="form-label" for="password">{label ?? $t('Password')}</label>
    
            {#if form.errors && form.errors.password} <!-- Conditionally render password errors -->
                <div class="alert alert-danger field-error">{form.errors.password}</div>
            {/if}
        </div>
    
        <!-- Password visibility toggle button -->
        <button 
            type="button" 
            class="btn btn-toggle-pw-visibility position-absolute top-50 start-100 translate-middle ms-n4 rounded-0 h-100"
            on:click={() => showPassword = !showPassword}
            style="border-top-left-radius: 0; border-bottom-left-radius: 0;">
            <i class={showPassword ? 'bi bi-eye' : 'bi bi-eye-slash'}></i>
        </button>
    </div>
    