// assets/scripts/fields.js

import { getElementConfig } from '@/globalConfig';
import { ref } from 'vue';
import { uiFeedbackToasts } from './ui-feedack';
import  { getElementConfigData } from '@/assets/scripts/utilitites';
// import elementsConfig from '@/config/elements';

import { useNotify } from "@/composables/useNotify";
const { notifyError, notifySuccess } = useNotify();

/**
 * Check if a given string is a valid ISO-8601 date.
 * @param {string} value - The value to check.
 * @returns {boolean} - True if it's a valid date, false otherwise.
 */
function isValidDate(value) {
    // Use a regular expression to validate ISO-8601 date format
    // This regex checks for dates in the format "YYYY-MM-DD" and "YYYY-MM-DDTHH:mm:ss.sssZ"
    const iso8601Regex = /^(\d{4}-\d{2}-\d{2}(T\d{2}:\d{2}:\d{2}(\.\d{3}Z)?)?)?$/;
    return iso8601Regex.test(value);
}






/**
 * 
 * @param {*} modelName 
 * @param {*} itemId 
 * @param {*} fieldName 
 * @param {*} newValue 
 * @returns 
 */
export async function updateField (elementName, itemId, fieldName, newValue)
{

    // MAKE 'PRE-SAVE' FUNCTION CALLS HERE, TYPES OF PRE-SAVE CALLS
    // 1. VALIDATION
    // 2. SANITIZATION
    // 3. FORMATTING
    // 4. CONVERSIONS
    // 5. OTHERS

    try {


        console.log(elementName);
        console.log(itemId);
        console.log(fieldName);
        console.log(newValue);
    
        
        // get field type and sanitize, i.e. ":Expected ISO-8601 DateTime."
        if(isValidDate(newValue)) {
            newValue = new Date(newValue).toISOString();
        }
    
        const requestBody = JSON.stringify({
            id: itemId,
            [fieldName]: newValue
        });

        const apiHost = import.meta.env.VITE_API_HOST || 'http://127.0.0.1:3000';
        //const modelName = elementsConfig[props.elementName]['db_model'];
        //const modelApiEndpoint = elementsConfig[props.elementName]['slug'];
    
        const elementConfigData = getElementConfigData(elementName);

        // // const { modelName } = elementConfigData;
        // console.log(elementConfigData);

        const response = await fetch(`${apiHost}/put/${elementName}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
            },
            body: requestBody
        });

        if (!response.ok) {
            throw new Error('Failed to update');
        }

        const responseData = await response.json();
        // Handle successful update, e.g., show a notification
        //return responseData;
        //console.log(responseData);
        //uiFeedbackToasts(responseData);
        notifySuccess(responseData); // actionPathIndex

        return {
            statusCode: 200,
            statusType: 'success',
            statusMessage: 'Record created successfully!',
            data: responseData,
        };



    } catch (error) {
        console.error('Error updating field:', error);
        notifyError('Oops! An error occurred during submission');
        // Handle error, e.g., show an error message
        //throw error; // Rethrow the error for further handling in the calling component

        return {
            statusCode: 500,
            statusType: 'error',
            statusMessage: 'Oops! Something went wrong.',
            data: {},
        };


    }
}


/**
 * Format date to YYYY-MM-DD in UTC
 * @param {string|Date} date - The date to format
 * @returns {string} - The formatted date string
 */
export function formatDate(date) {
    if (!date) return '';
    const d = new Date(date);
    const year = d.getUTCFullYear();
    const month = `0${d.getUTCMonth() + 1}`.slice(-2);
    const day = `0${d.getUTCDate()}`.slice(-2);
    return `${year}-${month}-${day}`;
}


/**
 * 
 * @param {*} context 
 * @param {*} functionName 
 * @param {*} args 
 * @returns 
 */
export function callFunctionFromString(context, functionName, args) {
    const fn = context[functionName];
    if (typeof fn !== 'function') {
        console.warn(`Function ${functionName} is not found or is not a function`);
        return;
    }
    fn.apply(context, args);
}