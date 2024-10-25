// ui/src/assets/scripts/formHandlers.js


import { useNotify } from "@/composables/useNotify";
const { notifyError, notifySuccess } = useNotify();
const backendHost = import.meta.env.VITE_BACKEND_HOST || 'http://127.0.0.1:3011';


// ACTION PATH MAPPING INDEX

const actionPathIndex = {
    '/auth/login' : {
        'messages' : {
            'success' : {
            }
        }
    }
}

/**
 * Submit Form Handler
 * @param {Object} form
 * @param {String} actionPath
 * @param {String} redirectTo
 */
export const submitForm = async (formObject, formEvent, formData, parameters, actionPath, redirectTo) => {
    try {

        const csrfToken = await fetchCsrfToken();
        if (!csrfToken) throw new Error('No CSRF token');

        // Ensure form is an HTMLFormElement
        const formSelector = formEvent.target;
        if (!(formSelector instanceof HTMLFormElement)) {
            throw new Error('Provided form is not an HTMLFormElement');
        }

         // Create FormData from form element
        if(!formData){
            const formDataValues = new FormData(formSelector);
            // Convert FormData to JSON object
            const formDataObject = {};
            formDataValues.forEach((value, key) => {
                formDataObject[key] = value;
            });  
            formData = {
                ...formDataObject,
                ...formObject.value,
            }
        }

        // Add reactive form object values
        const requestBody = {
            ...formData,
            parameters: { ...parameters }
        };


 
        // Form sanitization and validation
        const response = await fetch(actionPath, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'csrf-token': csrfToken,
            },
            body: JSON.stringify(requestBody),
        });

        if (response.ok) {
            notifySuccess('Success!'); // actionPathIndex
            const data = await response.json();
            if(redirectTo){
                window.location.href = redirectTo; // Redirect on success
            }
            /// CALLBACKS

        } else {
            // notifyError('Login failed'); // actionPathIndex
            throw new Error(`Error: ${response.status} - ${response.statusText}`);
        }
    } catch (error) {
        notifyError('Oops! An error occurred during submission');
        console.error('Error in submitForm:', error);
    }
};

/**
 * Fetch CSRF Token
 * @returns {String|null}
 */
export const fetchCsrfToken = async () => {
    try {
        const response = await fetch(`${backendHost}/csrf`, {
            method: 'GET',
            credentials: 'include',
        });

        if (response.ok) {
            const data = await response.json();
            return data.token;
        } else {
            throw new Error('Failed to fetch CSRF token');
        }
    } catch (error) {
        console.error('Error fetching CSRF token:', error);
        notifyError('Failed to fetch CSRF token');
        return null;
    }
};

/**
 * 
 * @param {*} token 
 * @returns 
 */
export const verifyJwt = async (token) => {
    try {
        const response = await fetch(`${backendHost}/verify-jwt`, {
            method: 'GET',
            credentials: 'include', // Send cookie along with the request
        });
        const data = await response.json();
        // console.log('verifyJwt ', data);
        return data.valid;
    } catch (error) {
        console.error('Error verifying token:', error);
        return false;
    }
};

/**
 * Focus the first visible field within a specified parent element
 * @param {string} parentSelector - The selector for the parent element
 * @param {string} [fieldSelector] - The selector for a specific field to focus on (optional)
 */
export function focusField (parentSelector, fieldSelector) {
    const parentElement = document.querySelector(parentSelector);
    if (!parentElement) {
        console.warn(`Parent element with selector "${parentSelector}" not found.`);
        return;
    }

    let field;
    if (fieldSelector) {
        field = parentElement.querySelector(fieldSelector);
        if (field && field.offsetParent === null) {
            console.warn(`Field with selector "${fieldSelector}" is not visible.`);
            return;
        }
    } else {
        const fields = parentElement.querySelectorAll('input, select, textarea, button');
        field = Array.from(fields).find(el => el.offsetParent !== null);
        if (!field) {
            console.warn('No visible fields found to focus on.');
            return;
        }
    }

    field.focus();
    // Select all content if the field is an input or textarea
    if (field.tagName.toLowerCase() === 'input' || field.tagName.toLowerCase() === 'textarea') {
        field.select();
    }


}