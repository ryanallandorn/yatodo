// assets/scripts/forms.js

import { ref } from 'vue';
import { uiFeedbackToasts } from './ui-feedack';

/**
 * 
 * @param {*} form 
 * @param {*} csrf 
 * @param {*} fetchPath 
 * @param {*} redirectTo 
 */
export const submitForm = async (form, csrf, fetchPath, redirectTo) => {

    // console.log(form);
    // console.log(csrf);
    // console.log(fetchPath);
    // console.log(redirectTo);

    try {

        // Guard clauses
        if (typeof form !== 'object') {
            console.error('Form must be an object');
            throw new Error('Form must be an object');
        }

        if (typeof csrf !== 'string') {
            console.error('CSRF must be a string');
            throw new Error('CSRF must be a string');
        }

        if (typeof fetchPath !== 'string') { // fetchPath is a string   
            console.error('fetchPath must be a string');
            throw new Error('fetchPath must be a string');
        }

        const response = await fetch(fetchPath, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'csrf-token': csrf,
            },
            body: JSON.stringify(form.value),
        });

        const data = await response.json();
        uiFeedbackToasts(data);

        if (response.ok && redirectTo) {
            window.location.href = redirectTo;
        }

    } catch (error) {
        console.error(error);
    }
};



