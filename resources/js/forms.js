// resources/js/forms.js

// External
import axios from 'axios';

// Internal
import { showToast, handleCallbackMessages } from './Scripts/toasts';
import { addSpinner, removeSpinner } from './Scripts/loading';


/**
 * Handles the UI feedback after form submission.
 */
const doFormUiFeedbackResult = async (form) => {
    addSpinner(form);
    const resultDiv = form.querySelector('.form-result');
    let alertClass;
    let alertContent;
    resultDiv.innerHTML = '';

    try {
        const formData = new FormData(form);
        const response = await axios.post(form.action, formData);
        
        // If the form was successful
        alertClass = 'alert-success';
        alertContent = response.data.message ?? 'Success';
        
        // Optionally: Call a success toast or other UX feature
        showToast('Form submitted successfully!', 'success');

    } catch (error) {
        // If there was an error, let's handle validation messages if they exist
        alertClass = 'alert-danger';
        
        // Check if we have a validation error response from Laravel
        if (error.response && error.response.status === 422) {
            alertContent = '<ul>';
            const errors = error.response.data.errors ?? {};
            for (let key in errors) {
                alertContent += `<li>${errors[key][0]}</li>`;
            }
            alertContent += '</ul>';
        } else {
            // Fallback error message
            alertContent = error.response?.data?.message ?? 'An error occurred.';
        }
        
        // Optionally: Call an error toast or other UX feature
        showToast('There was an error submitting the form.', 'error');
    }

    // Display the result in the UI
    resultDiv.innerHTML = `
        <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
            ${alertContent}
            <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `;
    
    removeSpinner(form);
};


/**
 * Listen for form submissions on forms with the 'ajax-form' class.
 */
document.addEventListener('DOMContentLoaded', function () {
    document.addEventListener('submit', async function (e) {
        if (e.target.classList.contains('ajax-form')) {
            e.preventDefault();

            const form = e.target;
            await doFormUiFeedbackResult(form);
        }
    });
});
