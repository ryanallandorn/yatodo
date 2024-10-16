// resources/js/sidebar.js

(() => {
    'use strict'


    /**
     * 
     */
    const getStoredSidebarState = () => localStorage.getItem('sidebarState');
    const setStoredSidebarState = sidebarState => localStorage.setItem('sidebarState', sidebarState);
    const btnSidebarToggle = document.getElementById('btn-sidebar-toggle');


    /**
     * 
     */
    const getPreferredSidebarState = () => {
        const storedSidebarState = getStoredSidebarState()
        if (storedSidebarState) {
            return storedSidebarState
        }
        return window.matchMedia('(prefers-color-scheme: collapsed)').matches ? 'collapsed' : 'expanded'
    }


    /**
     * 
     */
    const setSidebarState = sidebarState => {
        if (sidebarState === 'expanded') {
            document.body.classList.add('sidebar-expanded');
            document.body.classList.remove('sidebar-collapsed');
        } else {
            document.body.classList.add('sidebar-collapsed');
            document.body.classList.remove('sidebar-expanded');
        }
        setStoredSidebarState(sidebarState);
    }


    /**
     * 
     */
    const switchSidebarState = () => {
        const currentState = getPreferredSidebarState();
        const newState = currentState === 'expanded' ? 'collapsed' : 'expanded';
        setSidebarState(newState);
    }


    /**
     * 
     */
    const initializeSidebarState = () => {
        if (btnSidebarToggle) { 
            setSidebarState(getPreferredSidebarState());
        }
    };


    /**
     * 
     */
    initializeSidebarState();


    /**
     * 
     */
    window.addEventListener('DOMContentLoaded', () => {
        alert('hi');
        if (btnSidebarToggle) {

            btnSidebarToggle.addEventListener('click', switchSidebarState);
        }
    })


})()
