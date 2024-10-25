// resources/js/Scripts/suggestion.js

import { onMount, getContext } from 'svelte';
import tippy from 'tippy.js';
import MentionList from '@components/UI/MentionList.svelte';

// API host and endpoint configuration
const apiHost = import.meta.env.VITE_API_HOST || 'http://127.0.0.1:3000';
const modelApiEndpoint = 'users';

// Helper function to dynamically mount Svelte components
function mountSvelteComponent(Component, props, target) {
    const component = new Component({
        target,
        props,
    });

    return component;
}

export default {
    items: async ({ query }) => {
        if (!query) return [];

        try {
            const response = await fetch(`${apiHost}/${modelApiEndpoint}?query=${encodeURIComponent(query)}`);
            const data = await response.json();
            return data.map(user => ({
                id: user.id,
                label: `${user.firstName} ${user.lastName}`.trim(),
            }));
        } catch (error) {
            console.error('Error fetching users:', error);
            return [];
        }
    },

    render: () => {
        let component;
        let popup;

        return {
            onStart: props => {
                const container = document.createElement('div');
                component = mountSvelteComponent(MentionList, { items: props.items }, container);

                if (!props.clientRect) {
                    return;
                }

                popup = tippy('body', {
                    getReferenceClientRect: props.clientRect,
                    appendTo: () => document.body,
                    content: container,
                    showOnCreate: true,
                interactive: true,
                    trigger: 'manual',
                    placement: 'bottom-start',
                });
            },

            onUpdate(props) {
                component.$set({ items: props.items });

                if (!props.clientRect) {
                    return;
                }

                popup[0].setProps({
                    getReferenceClientRect: props.clientRect,
                });
            },

            onKeyDown(props) {
                if (props.event.key === 'Escape') {
                    popup[0].hide();
                    return true;
                }

                return false;
            },

            onExit() {
                popup[0].destroy();
                component.$destroy();
            },
        };
    },
};
