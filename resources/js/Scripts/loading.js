// resources/js/loading.js

/**
 * Adds a spinner to the specified element
 * @param {HTMLElement} element - The DOM element
 * @param {boolean} showText - Whether to show the "Loading..." text
 */
export function addSpinner(element, showText = true) {
    element.classList.add('loading');
    
    const loadingWrapper = document.createElement('div');
    loadingWrapper.className = 'loading-wrapper';
    
    const spinner = document.createElement('div');
    spinner.className = 'spinner-border';
    spinner.setAttribute('role', 'status');
    
    const spinnerInner = document.createElement('div');
    spinnerInner.className = 'spinner-inner';
    spinnerInner.appendChild(spinner);
    
    loadingWrapper.appendChild(spinnerInner);

    if (showText) {
        const loadingText = document.createElement('span');
        loadingText.className = 'loading-text';
        loadingText.textContent = 'Loading...';
        loadingWrapper.appendChild(loadingText);
    }

    element.appendChild(loadingWrapper);
}

/**
 * Removes the spinner from the specified element
 * @param {HTMLElement} element - The DOM element
 */
export function removeSpinner(element) {
    element.classList.remove('loading');
    
    const loadingWrapper = element.querySelector('.loading-wrapper');
    if (loadingWrapper) {
        element.removeChild(loadingWrapper);
    }
}

/**
 * Adds a non-blocking spinner to the bottom right of the screen
 * @param {boolean} showText - Whether to show the "Loading..." text
 */
export function addNonBlockingSpinner(showText = true) {
    const existingSpinner = document.querySelector('.non-blocking-spinner-wrapper');
    if (existingSpinner) return; // Avoid adding multiple spinners

    const loadingWrapper = document.createElement('div');
    loadingWrapper.className = 'non-blocking-spinner-wrapper';
    loadingWrapper.style.position = 'fixed';
    loadingWrapper.style.bottom = '20px';
    loadingWrapper.style.right = '20px';
    loadingWrapper.style.zIndex = '1050';

    const spinner = document.createElement('div');
    spinner.className = 'spinner-border';
    spinner.setAttribute('role', 'status');

    const spinnerInner = document.createElement('div');
    spinnerInner.className = 'spinner-inner';
    spinnerInner.appendChild(spinner);
    
    loadingWrapper.appendChild(spinnerInner);

    if (showText) {
        const loadingText = document.createElement('span');
        loadingText.className = 'loading-text';
        loadingText.textContent = 'Loading...';
        loadingWrapper.appendChild(loadingText);
    }

    document.body.appendChild(loadingWrapper);
}

/**
 * Removes the non-blocking spinner from the bottom right of the screen
 */
export function removeNonBlockingSpinner() {
    const loadingWrapper = document.querySelector('.non-blocking-spinner-wrapper');
    if (loadingWrapper) {
        document.body.removeChild(loadingWrapper);
    }
}
