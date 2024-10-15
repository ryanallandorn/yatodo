<script>
    // resources/js/Components/Fields/Password.svelte
    
        import { t } from 'svelte-i18n';
        export let form; // Accept the form object as a prop
        export let id = 'password'; // Default id for the input field
        export let name = 'password'; // Default name for the input field
        let showPassword = false; // Reactive variable to toggle password visibility
    </script>
    
    <div class="input-group position-relative">
        <div class="form-floating">
            <!-- Conditionally render based on showPassword -->
            {#if showPassword}
                <input 
                    type="text" 
                    id={id}
                    name={name}
                    bind:value={form.password}
                    placeholder="Password" 
                    class="form-control"
                >
            {:else}
                <input 
                    type="password" 
                    id={id}
                    name={name}
                    bind:value={form.password}
                    placeholder="Password" 
                    class="form-control"
                >
            {/if}
    
            <label class="form-label" for="password">{$t('Password')}</label>
    
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
    