import { ref } from 'vue';

/**
 * 
 * @param {*} responseData 
 */
export const uiFeedbackToasts = (responseData) => {

    try {

        // Guard clauses
        if (typeof responseData !== 'object') {
            console.error('Response data must be an object');
            throw new Error('Response data must be an object');
        }

        const statusMessage = responseData.statusMessage ?? 'Error';
        const statusType = responseData.statusType ?? 'error';
    
        const toastLookupByStatus = {
            'success': useNuxtApp().$toast.success,
            'error': useNuxtApp().$toast.error,
            'info': useNuxtApp().$toast.info,
            'warning': useNuxtApp().$toast.warning,
        };
    
        const toastFunction = toastLookupByStatus[statusType];
    
        if (toastFunction) {
            toastFunction(statusMessage, {
                type: statusType,
                position: 'bottom-right',
            });
        }

    }   catch (error) {
        console.error(error);
    }


};