import {
    BlockQuote,
    Bold,
    ClassicEditor,
    Essentials,
    Heading,
    Italic,
    Link,
    List,
    Paragraph,
    Undo,
} from 'ckeditor5';
import 'ckeditor5/ckeditor5.css';
import '../css/rich-editor.css';

function getLivewireComponent(element) {
    const root = element.closest('[wire\\:id]');

    if (!root || !window.Livewire) {
        return null;
    }

    return Livewire.find(root.getAttribute('wire:id'));
}

async function initEditor(textarea) {
    if (textarea.dataset.richEditorReady === 'true' || textarea.ckEditorInstance) {
        return;
    }

    textarea.dataset.richEditorReady = 'pending';

    try {
        const editor = await ClassicEditor.create(textarea, {
            licenseKey: 'GPL',
            plugins: [
                Essentials,
                Paragraph,
                Bold,
                Italic,
                Heading,
                List,
                Link,
                BlockQuote,
                Undo,
            ],
            toolbar: [
                'heading',
                '|',
                'bold',
                'italic',
                'link',
                'bulletedList',
                'numberedList',
                '|',
                'blockQuote',
                '|',
                'undo',
                'redo',
            ],
            heading: {
                options: [
                    { model: 'paragraph', title: 'Paragraf', class: 'ck-heading_paragraph' },
                    { model: 'heading2', view: 'h2', title: 'Judul 2', class: 'ck-heading_heading2' },
                    { model: 'heading3', view: 'h3', title: 'Judul 3', class: 'ck-heading_heading3' },
                ],
            },
        });

        textarea.dataset.richEditorReady = 'true';
        textarea.ckEditorInstance = editor;
    } catch (error) {
        textarea.dataset.richEditorReady = 'false';
        throw error;
    }
}

function destroyEditor(textarea) {
    const editor = textarea.ckEditorInstance;

    if (!editor) {
        textarea.dataset.richEditorReady = 'false';
        return;
    }

    editor.destroy()
        .catch(() => {})
        .finally(() => {
            textarea.dataset.richEditorReady = 'false';
            textarea.ckEditorInstance = null;
        });
}

function initAll(root) {
    const scope = root || document;

    scope.querySelectorAll('[data-rich-editor]:not([data-rich-editor-ready="true"])').forEach((textarea) => {
        if (textarea.dataset.richEditorReady === 'pending') {
            return;
        }

        initEditor(textarea).catch((error) => {
            console.error(error);
        });
    });
}

function destroyAll(root) {
    const scope = root || document;

    scope.querySelectorAll('[data-rich-editor]').forEach(destroyEditor);
}

function syncAll(form) {
    const scope = form || document;

    scope.querySelectorAll('[data-rich-editor][data-rich-editor-ready="true"]').forEach((textarea) => {
        const editor = textarea.ckEditorInstance;
        const model = textarea.dataset.model;
        const component = getLivewireComponent(textarea);

        if (editor && component && model) {
            component.set(model, editor.getData(), false);
        }
    });
}

document.addEventListener('submit', (event) => {
    const form = event.target;

    if (form?.querySelector?.('[data-rich-editor]')) {
        syncAll(form);
    }
}, true);

function registerMorphHook() {
    if (!window.Livewire?.hook) {
        return;
    }

    Livewire.hook('morph.updated', ({ el }) => {
        if (el.querySelector?.('[data-rich-editor]:not([data-rich-editor-ready="true"])')) {
            setTimeout(() => initAll(el), 50);
        }
    });
}

document.addEventListener('livewire:init', registerMorphHook);

if (window.Livewire?.hook) {
    registerMorphHook();
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => initAll());
} else {
    initAll();
}

document.addEventListener('livewire:navigated', () => {
    setTimeout(() => initAll(), 150);
});

window.RichEditor = {
    initAll,
    destroyAll,
    syncAll,
};
