// assets/scripts/fieldHandlers.js
// import elementsConfig from '@/config/elements';
const elementsConfig = {}


const apiHost = import.meta.env.VITE_API_HOST || 'http://127.0.0.1:3000';

// Function to ensure model name and API endpoint are retrieved safely
const getElementInfoSafely = (elementName) => {
    if (!elementsConfig[elementName]) {
        throw new Error(`Element '${elementName}' is not configured properly.`);
    }

    const { db_model: modelName, slug: modelApiEndpoint } = elementsConfig[elementName] || {};

    if (!modelName || !modelApiEndpoint) {
        throw new Error(`Model information for '${elementName}' is incomplete.`);
    }

    return { modelName, modelApiEndpoint };
};

// Example function to update a field
export async function updateField(elementName, itemId, fieldName, newValue) {
    try {
        // Validation and sanitization
        if (typeof newValue === 'string') {
            newValue = newValue.trim();
        }

        console.log(itemId);

        const { modelName, modelApiEndpoint } = getElementInfoSafely(elementName);

        const requestBody = JSON.stringify({
            id: itemId,
            [fieldName]: newValue
        });

        const apiRoute = `${apiHost}/put/${modelApiEndpoint}`;
        console.log(apiRoute);

        const response = await fetch(apiRoute, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json' },
            body: requestBody
        });

        if (!response.ok) {
            throw new Error('Failed to update');
        }

        const responseData = await response.json();
        // Handle successful update
        return { statusCode: 200, statusType: 'success', statusMessage: 'Update successful!', data: responseData };

    } catch (error) {
        console.error('Error updating field:', error);
        return { statusCode: 500, statusType: 'error', statusMessage: 'Update failed.', data: {} };
    }
}
