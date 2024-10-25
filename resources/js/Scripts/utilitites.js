// ui/src/assets/scripts/utilitites.js

import { getElementConfig } from '@/globalConfig';


/**
 * 
 * @param {*} elementName 
 * @returns 
 */
export const getElementConfigData = (elementName) => { // Function to validate and retrieve properties
    const elementConfig = getElementConfig();

    // Ensure the element exists in the config
    if (!(elementName in elementConfig)) {
    throw new Error(`Element '${elementName}' does not exist in the config.`);
    }

    const elementCfg = elementConfig[elementName];

    // Validate the 'db_model' field
    if (typeof elementCfg.db_model !== 'string') {
    throw new Error(`'db_model' for '${elementName}' must be a string.`);
    }

    // Validate the 'slug' field
    if (typeof elementCfg.slug !== 'string') {
    throw new Error(`'slug' for '${elementName}' must be a string.`);
    }

    // Retrieve the values after validation
    const modelName = elementCfg.db_model;
    const modelApiEndpoint = elementCfg.slug;

    // Return the values or use them as needed
    return { modelName, modelApiEndpoint };
};


export function debounce(func, wait, immediate) {
    let timeout;
    return function executedFunction(...args) {
        const context = this;
        const later = function () {
            timeout = null;
            if (!immediate) func.apply(context, args);
        };
        const callNow = immediate && !timeout;
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
        if (callNow) func.apply(context, args);
    };
}