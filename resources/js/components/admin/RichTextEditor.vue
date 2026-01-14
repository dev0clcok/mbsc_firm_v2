<template>
    <div class="rich-text-editor">
        <div class="border border-input rounded-md">
            <EditorContent :editor="editor" class="prose max-w-none p-4 min-h-[300px]" />
        </div>
    </div>
</template>

<script setup lang="ts">
import { useEditor, EditorContent } from '@tiptap/vue-3';
import StarterKit from '@tiptap/starter-kit';
import Placeholder from '@tiptap/extension-placeholder';
import { watch, onBeforeUnmount } from 'vue';

interface Props {
    modelValue: string;
    placeholder?: string;
}

const props = withDefaults(defineProps<Props>(), {
    placeholder: 'Start typing...',
});

const emit = defineEmits<{
    'update:modelValue': [value: string];
}>();

const editor = useEditor({
    extensions: [
        StarterKit,
        Placeholder.configure({
            placeholder: props.placeholder,
        }),
    ],
    content: props.modelValue,
    onUpdate: ({ editor }) => {
        emit('update:modelValue', editor.getHTML());
    },
});

watch(() => props.modelValue, (value) => {
    const isSame = editor.value?.getHTML() === value;
    if (!isSame) {
        editor.value?.commands.setContent(value || '');
    }
});

onBeforeUnmount(() => {
    editor.value?.destroy();
});
</script>

<style>
.rich-text-editor .ProseMirror {
    outline: none;
    min-height: 300px;
}

.rich-text-editor .ProseMirror p.is-editor-empty:first-child::before {
    content: attr(data-placeholder);
    float: left;
    color: #9ca3af;
    pointer-events: none;
    height: 0;
}

.rich-text-editor .ProseMirror h1 {
    font-size: 2em;
    font-weight: bold;
    margin-top: 0.67em;
    margin-bottom: 0.67em;
}

.rich-text-editor .ProseMirror h2 {
    font-size: 1.5em;
    font-weight: bold;
    margin-top: 0.83em;
    margin-bottom: 0.83em;
}

.rich-text-editor .ProseMirror h3 {
    font-size: 1.17em;
    font-weight: bold;
    margin-top: 1em;
    margin-bottom: 1em;
}

.rich-text-editor .ProseMirror ul,
.rich-text-editor .ProseMirror ol {
    padding-left: 2em;
    margin: 1em 0;
}

.rich-text-editor .ProseMirror ul {
    list-style-type: disc;
}

.rich-text-editor .ProseMirror ol {
    list-style-type: decimal;
}

.rich-text-editor .ProseMirror table {
    border-collapse: collapse;
    margin: 1em 0;
    width: 100%;
}

.rich-text-editor .ProseMirror table td,
.rich-text-editor .ProseMirror table th {
    border: 1px solid #e5e7eb;
    padding: 0.5em;
}

.rich-text-editor .ProseMirror table th {
    background-color: #f3f4f6;
    font-weight: bold;
}
</style>
