import { showToast, handleCallbackMessages } from './Scripts/toasts';
import { addNonBlockingSpinner, removeNonBlockingSpinner } from './loading';

document.addEventListener('DOMContentLoaded', function () {
    document.addEventListener('click', function (e) {
        //e.preventDefault();

        const target = e.target;

        if (target.classList.contains('field-editable')) {
            const fieldId = target.getAttribute('data-field-id');
            const mode = target.getAttribute('data-mode') || 'inline';
            const type = target.getAttribute('data-type') || 'text';
            const originalValue = target.textContent.trim();

            if (mode === 'inline') {
                handleInlineEdit(target, fieldId, type, originalValue);
            } else if (mode === 'popover') {
                handlePopoverEdit(target, fieldId, type, originalValue);
            }
        }
    });

    const handleInlineEdit = (element, fieldId, type, originalValue) => {
        const input = document.createElement('input');
        const cancelBtn = document.createElement('button');
        const saveBtn = document.createElement('button');

        input.type = type;
        input.value = originalValue;
        input.classList.add('form-control');

        cancelBtn.textContent = 'Cancel';
        cancelBtn.classList.add('btn', 'btn-secondary', 'btn-sm');
        cancelBtn.addEventListener('click', () => {
            element.textContent = originalValue;
            input.remove();
            cancelBtn.remove();
            saveBtn.remove();
        });

        saveBtn.textContent = 'Save';
        saveBtn.classList.add('btn', 'btn-primary', 'btn-sm');
        saveBtn.addEventListener('click', () => {
            addNonBlockingSpinner();
            const newValue = input.value;
            updateField(fieldId, newValue)
                .then(success => {
                    if (success) {
                        element.textContent = newValue;
                    } else {
                        element.textContent = originalValue; // Restore original value on failure
                    }
                    input.remove();
                    cancelBtn.remove();
                    saveBtn.remove();
                })
                .catch(error => console.error('Update failed:', error));
        });

        element.innerHTML = '';
        element.appendChild(input);
        element.appendChild(cancelBtn);
        element.appendChild(saveBtn);
        input.focus();
    };

    const handlePopoverEdit = (element, fieldId, type, originalValue) => {
        const existingPopover = bootstrap.Popover.getInstance(element);
        if (existingPopover) {
            existingPopover.dispose();
        }

        const popoverId = `popover-${fieldId}-${Math.random().toString(36).substr(2, 9)}`;

        element.setAttribute('data-bs-toggle', 'popover');
        element.setAttribute('data-bs-content', `
            <div id="${popoverId}" class="popover-content">
                <input type="${type}" value="${originalValue}" class="form-control" />
                <button class="btn btn-secondary btn-sm popover-cancel">Cancel</button>
                <button class="btn btn-primary btn-sm popover-save">Save</button>
            </div>
        `);

        const popover = new bootstrap.Popover(element, {
            html: true,
            sanitize: false
        });
        popover.show();

        const popoverContent = document.querySelector(`#${popoverId}`);

        const handlePopoverClick = async (e) => {
            if (e.target.classList.contains('popover-save')) {
                const newValue = popoverContent.querySelector('input').value;
                try {
                    const success = await updateField(fieldId, newValue);
                    if (success) {
                        element.textContent = newValue;
                    } else {
                        element.textContent = originalValue; // Restore original value on failure
                    }
                } catch (error) {
                    console.error('Update failed:', error);
                    element.textContent = originalValue; // Restore original value on failure
                } finally {
                    popover.hide();
                }
            } else if (e.target.classList.contains('popover-cancel')) {
                popover.hide();
            }
        };

        const cleanupPopover = () => {
            if (popoverContent) {
                popoverContent.removeEventListener('click', handlePopoverClick);
            }
            if (popover._element) {
                popover.dispose();
            }
        };

        popoverContent.addEventListener('click', handlePopoverClick);
        element.addEventListener('hidden.bs.popover', cleanupPopover);
    };

    const updateField = (fieldId, newValue) => {
        addNonBlockingSpinner(false);
        return fetch('/element-field-values/update/' + fieldId, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ value: newValue })
        })
        .then(response => {
            // Parse the response JSON regardless of the status code
            return response.json().then(data => {
                if (!response.ok) {
                    // This handles non-2xx status codes (e.g., 422 for validation errors)
                    if (data.errors) {
                        const errorMessages = Object.values(data.errors).flat().join('\n');
                        showToast(`Validation failed: ${errorMessages}`, 'danger', 5000, 'bottom-center');
                    } else {
                        showToast(data.message || 'Update failed. Please try again.', 'danger', 5000, 'bottom-center');
                    }
                    return false; // Indicate failure
                }
                showToast('Field updated successfully!', 'success', 5000, 'bottom-center');
                return true; // Indicate success
            });
        })
        .catch(error => {
            console.error('Update failed:', error);
            showToast(`An error occurred while updating: ${error.message}. Please try again.`, 'danger', 5000, 'bottom-center');
            return false; // Indicate failure
        })
        .finally(() => {
            removeNonBlockingSpinner(false);
        });
    };

});
