// resources/js/toasts.js

import { Toast } from 'bootstrap';

export function showToast(message, type, delay = 5000, position = 'bottom-end') {
    const positionClasses = {
        'top-start': 'top-0 start-0',
        'top-center': 'top-0 start-50 translate-middle-x',
        'top-end': 'top-0 end-0',
        'middle-start': 'top-50 start-0 translate-middle-y',
        'middle-center': 'top-50 start-50 translate-middle',
        'middle-end': 'top-50 end-0 translate-middle-y',
        'bottom-start': 'bottom-0 start-0',
        'bottom-center': 'bottom-0 start-50 translate-middle-x',
        'bottom-end': 'bottom-0 end-0'
    };

    const positionClass = positionClasses[position] || 'bottom-0 end-0';
    const toastId = 'toast-' + Date.now();
    const progressBarId = 'progress-' + toastId;
    const toastHTML = `
        <div id="${toastId}" class="toast align-items-center bg-${type} text-white border-0 mb-3" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="progress" style="height: 5px;">
                <div id="${progressBarId}" class="progress-bar bg-light" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
        </div>
    `;

    let toastContainer = document.getElementById('toast-container');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.id = 'toast-container';
        document.body.appendChild(toastContainer);
    }

    toastContainer.className = `toast-container position-fixed p-3 ${positionClass}`;
    toastContainer.insertAdjacentHTML('beforeend', toastHTML);

    const toastElement = document.getElementById(toastId);
    const toast = new Toast(toastElement, { delay: delay });

    // Set the progress bar animation
    const progressBarElement = document.getElementById(progressBarId);
    progressBarElement.style.width = '100%';
    progressBarElement.style.transition = `width ${delay}ms linear`;
    setTimeout(() => {
        progressBarElement.style.width = '0%';
    }, 100);

    toast.show();
}


/**
 * 
 */
export function handleCallbackMessages (response)
{

    console.log(response);

    // if(
    //     !response.data
    // ){
    //     //showToast('Error', 'danger', 5000, 'bottom-center');
    // }

    if(response.data && response.data.message){
        showToast(response.data.message ?? 'N/A', 'warning', 5000, 'bottom-center');
        return;
    } 

    if(response.data && response.data.messages){
        response.data.messages.forEach((message, index) => {
            showToast(message.text ?? 'Success', message.type, 5000, 'bottom-center');
        })
        return;
    } 

    if(response.messages){
        response.messages.forEach((message, index) => {
            showToast(message.text ?? 'Success', message.type, 5000, 'bottom-center');
        })
        return;
    } 

}