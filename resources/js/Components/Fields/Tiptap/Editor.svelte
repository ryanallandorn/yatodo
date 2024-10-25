<script>
    import { onMount, createEventDispatcher } from 'svelte';
    import { writable } from 'svelte/store';
    import { Editor } from '@tiptap/core';
    import StarterKit from '@tiptap/starter-kit';
    import Highlight from '@tiptap/extension-highlight';
    import TaskItem from '@tiptap/extension-task-item';
    import TaskList from '@tiptap/extension-task-list';
    import CharacterCount from '@tiptap/extension-character-count';
    import Mention from '@tiptap/extension-mention';
    import * as Y from 'yjs';

    import { updateField } from '@scripts/fieldHandlers.js';
    import suggestion from '@scripts/suggestion.js';
    import MenuBar from '@components/Fields/Tiptap/MenuBar.svelte';
    import EditorContent from '@components/Fields/Tiptap/EditorContent.svelte'; // Import the custom wrapper

    export let modelValue = '';
    export let itemId;
    export let fieldName;
    export let modelName;
    export let elementName;
    export let autosave = false;
    export let collaborative = false;

    const editorData = writable(modelValue);
    const currentUser = writable(
        JSON.parse(localStorage.getItem('currentUser')) || {
            name: getRandomName(),
            color: getRandomColor(),
        }
    );
    const provider = writable(null);
    const status = writable('connecting');
    const room = writable(getRandomRoom());

    const dispatch = createEventDispatcher();
    let editor;

    const sharedExtensions = [
        StarterKit.configure({ history: false }),
        Highlight,
        TaskList,
        TaskItem,
        CharacterCount.configure({ limit: 10000 }),
        Mention.configure({
            HTMLAttributes: { class: 'mention' },
            suggestion,
        }),
    ];

    async function loadCollaborationExtensions() {
        const { TiptapCollabProvider } = await import('@hocuspocus/provider');
        const { default: Collaboration } = await import('@tiptap/extension-collaboration');
        const { default: CollaborationCursor } = await import('@tiptap/extension-collaboration-cursor');
        const ydoc = new Y.Doc();

        const collabProvider = new TiptapCollabProvider({
            appId: '7j9y6m10',
            name: $room,
            document: ydoc,
        });

        collabProvider.on('status', event => {
            status.set(event.status);
        });

        provider.set(collabProvider);

        return [
            Collaboration.configure({ document: ydoc }),
            CollaborationCursor.configure({
                provider: collabProvider,
                user: $currentUser,
            }),
        ];
    }

    async function initializeEditor() {
        const extensions = [...sharedExtensions];
        if (collaborative) {
            const collaborationExtensions = await loadCollaborationExtensions();
            extensions.push(...collaborationExtensions);
        }

        editor = new Editor({
            extensions,
            content: modelValue,
            element: document.createElement('div'),
            onUpdate: ({ editor }) => {
                updateValue(editor.getHTML());
            },
        });

        localStorage.setItem('currentUser', JSON.stringify($currentUser));
    }

    function setName() {
        const name = (window.prompt('Name') || '').trim().substring(0, 32);
        if (name) {
            updateCurrentUser({ name });
        }
    }

    function updateCurrentUser(attributes) {
        const newUser = { ...$currentUser, ...attributes };
        currentUser.set(newUser);
        editor.chain().focus().updateUser(newUser).run();
        localStorage.setItem('currentUser', JSON.stringify(newUser));
    }

    async function updateValue(newVal) {
        dispatch('update', { modelValue: newVal });
        if (autosave) {
            try {
                await updateField(elementName, itemId, fieldName, newVal);
                console.log(`Successfully updated ${fieldName} for item ${itemId}`);
            } catch (error) {
                console.error(`Error updating ${fieldName}:`, error);
            }
        }
    }

    onMount(() => {
        initializeEditor();
        return () => {
            editor?.destroy();
            if (collaborative) provider?.value?.destroy();
        };
    });

    $: if (editor && modelValue !== editor.getHTML()) {
        editor.commands.setContent(modelValue);
    }

    function getRandomElement(list) {
        return list[Math.floor(Math.random() * list.length)];
    }

    function getRandomColor() {
        return getRandomElement([
            '#958DF1', '#F98181', '#FBBC88', '#FAF594',
            '#70CFF8', '#94FADB', '#B9F18D',
        ]);
    }

    function getRandomName() {
        return getRandomElement([
            'Lea Thompson', 'Cyndi Lauper', 'Tom Cruise', 'Madonna',
            'Jerry Hall', 'Joan Collins', 'Winona Ryder', 'Christina Applegate',
            'Alyssa Milano', 'Molly Ringwald', 'Ally Sheedy', 'Debbie Harry',
            'Olivia Newton-John', 'Elton John', 'Michael J. Fox', 'Axl Rose',
            'Emilio Estevez', 'Ralph Macchio', 'Rob Lowe', 'Jennifer Grey',
            'Mickey Rourke', 'John Cusack', 'Matthew Broderick', 'Justine Bateman',
            'Lisa Bonet',
        ]);
    }

    function getRandomRoom() {
        const rooms = [10, 11, 12].map(number => `rooms.${number}`);
        return getRandomElement(rooms);
    }
</script>

<div class="editor border-thin">
    <MenuBar class="editor__header border-b-thin" {editor} />
    <EditorContent {editor} />
</div>

<style>
.mention {
    border: 1px solid #000;
    border-radius: 0.4rem;
    padding: 0.1rem 0.3rem;
    box-decoration-break: clone;
}
</style>
