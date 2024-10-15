// resources/js/fields.js


/**
 * 
 */
const showDropdown = (input) => {
    const dropdown = input.parentElement.querySelector('#typeahead-dropdown');
    const cancelButton = input.parentElement.querySelector('#cancel-button');
    dropdown.style.display = 'block';
    cancelButton.style.display = 'inline-block';
}


/**
 * 
 */
const hideDropdown = (input) => {
    const dropdown = input.parentElement.querySelector('#typeahead-dropdown');
    const cancelButton = input.parentElement.querySelector('#cancel-button');
    dropdown.style.display = 'none';
    cancelButton.style.display = 'none';
}

const handleBlur = (event) => {
    const input = event.target;
    const dropdown = input.parentElement.querySelector('#typeahead-dropdown');

    if (!dropdown.contains(event.relatedTarget)) {
        hideDropdown(input);
    }
}

/**
 * Fetch typeahead results from the API and display them in the dropdown.
 */
const fetchTypeaheadResults = (input) => {
    const query = input.value.trim();
    const source = input.getAttribute('data-source');
    const filters = JSON.parse(input.getAttribute('data-filters'));
    const returnValue = input.getAttribute('data-return-value') || 'id';
    const dropdownInner = input.parentElement.querySelector('#dropdown-inner');
    const loadingSpinner = input.parentElement.querySelector('#loading-spinner');

    if (!(query.length >= 2)) {
        hideDropdown(input);
        return;
    }

    if (loadingSpinner) loadingSpinner.style.display = 'block';

    const searchFields = filters['f_searchFields'] ? filters['f_searchFields'].join(',') : '';
    const searchOperator = filters['f_searchOperator'] ? filters['f_searchOperator'] : 'AND';
    
    // Include the filterExcludeIds in the query string
    const filterExcludeIds = filters['f_excludeIds'] ? filters['f_excludeIds'].join(',') : '';
    
    const apiUrl = `${source}?searchFields=${searchFields}&searchOperator=${searchOperator}&searchQuery=${encodeURIComponent(query)}&filterExcludeIds=${filterExcludeIds}`;

    fetch(apiUrl)
        .then(response => response.json())
        .then(data => {
            if (loadingSpinner) loadingSpinner.style.display = 'none';
            if (dropdownInner) dropdownInner.innerHTML = '';

            if (!(data.items && data.items.length > 0)) {
                hideDropdown(input);
                return;
            }

            data.items.forEach(item => {
                const resultItem = document.createElement('div');
                resultItem.classList.add('dropdown-item');
                resultItem.textContent = item.name;
                resultItem.onclick = () => {
                    input.value = item.name;

                    const hiddenField = document.getElementById(input.name.replace('_display', ''));
                    if (hiddenField) {
                        hiddenField.value = item[returnValue];
                    }

                    hideDropdown(input);
                };
                if (dropdownInner) dropdownInner.appendChild(resultItem);
            });
            showDropdown(input);
        })
        .catch(error => {
            console.error('Error fetching typeahead results:', error);
            if (loadingSpinner) loadingSpinner.style.display = 'none';
            hideDropdown(input);
        });
};



document.addEventListener('DOMContentLoaded', function () {
    // Initialize inputs with stored and display values
    const typeaheadInputs = document.querySelectorAll('.field-typeahead');
    typeaheadInputs.forEach(input => {
        const hiddenInputId = input.name.replace('_display', '');
        const hiddenInput = document.getElementById(hiddenInputId);

        if (input.value && hiddenInput) {
            // If there's already a value, make sure it is displayed correctly
            input.value = input.value; // This is the display value, e.g., project name
            hiddenInput.value = hiddenInput.value; // This is the stored value, e.g., project ID
        }
    });

    // Event delegation for input elements with class '.field-typeahead'
    document.addEventListener('focusin', function (event) {
        if (event.target && event.target.classList && event.target.classList.contains('field-typeahead')) {
            //handleTypeahead(event.target);
            fetchTypeaheadResults(event.target);
        }
    });

    document.addEventListener('input', function (event) {
        if (event.target && event.target.classList && event.target.classList.contains('field-typeahead')) {
            fetchTypeaheadResults(event.target);
        }
    });

    document.addEventListener('blur', function (event) {
        if (event.target && event.target.classList && event.target.classList.contains('field-typeahead')) {
            handleBlur(event);
        }
    });




    // Attach a global listener for the cancel button
    document.addEventListener('click', function (event) {
        if (event.target.id === 'cancel-button' || event.target.closest('#cancel-button')) {
            const input = event.target.closest('.form-group').querySelector('.field-typeahead');
            if (input) {
                input.value = '';
                hideDropdown(input);
            }
        }
    });




});


