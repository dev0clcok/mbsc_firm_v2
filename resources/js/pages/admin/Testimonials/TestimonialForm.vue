<template>
    <div class="space-y-6 p-4">
        <div class="flex items-center justify-between">
            <h1 class="text-3xl font-bold">
                {{ mode === 'edit' ? t('testimonials.edit.heading') : t('testimonials.create.heading') }}
            </h1>
            <Link href="/admin/testimonials" class="text-muted-foreground hover:text-foreground">
                {{ t('testimonials.back_to_list') }}
            </Link>
        </div>

        <form @submit.prevent="emit('submit')" class="space-y-6">
            <div class="rounded-lg border border-border bg-card p-6">
                <h2 class="mb-4 text-xl font-semibold">{{ t('testimonials.form.details') }}</h2>

                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-medium">
                            {{ t('testimonials.form.name') }} <span class="text-destructive">*</span>
                        </label>
                        <input
                            v-model="name"
                            type="text"
                            required
                            class="w-full rounded-md border border-input bg-background px-3 py-2"
                            :placeholder="t('testimonials.form.name_placeholder')"
                        />
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium">{{ t('testimonials.form.position') }}</label>
                        <input
                            v-model="position"
                            type="text"
                            class="w-full rounded-md border border-input bg-background px-3 py-2"
                            :placeholder="t('testimonials.form.position_placeholder')"
                        />
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium">{{ t('testimonials.form.company') }}</label>
                        <input
                            v-model="company"
                            type="text"
                            class="w-full rounded-md border border-input bg-background px-3 py-2"
                            :placeholder="t('testimonials.form.company_placeholder')"
                        />
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium">{{ t('testimonials.form.rating') }}</label>
                        <select
                            v-model.number="rating"
                            class="w-full rounded-md border border-input bg-background px-3 py-2"
                        >
                            <option v-for="i in 5" :key="i" :value="i">{{ i }} {{ t('testimonials.form.stars') }}</option>
                        </select>
                    </div>
                </div>

                <div class="mt-4">
                    <label class="mb-2 block text-sm font-medium">
                        {{ t('testimonials.form.text') }} <span class="text-destructive">*</span>
                    </label>
                    <textarea
                        v-model="text"
                        rows="4"
                        required
                        class="w-full rounded-md border border-input bg-background px-3 py-2"
                        :placeholder="t('testimonials.form.text_placeholder')"
                    />
                </div>

                <div class="mt-4">
                    <label class="mb-2 block text-sm font-medium">{{ t('testimonials.form.avatar') }}</label>
                    <div v-if="previewUrl" class="mb-3">
                        <img :src="previewUrl" :alt="t('testimonials.form.avatar_preview')" class="h-20 w-20 rounded-full object-cover" />
                    </div>
                    <div class="flex flex-wrap items-center gap-3">
                        <input
                            ref="fileInputRef"
                            type="file"
                            accept="image/*"
                            class="block w-full max-w-xs text-sm text-muted-foreground file:mr-4 file:rounded-md file:border-0 file:bg-primary file:px-4 file:py-2 file:text-sm file:font-medium file:text-primary-foreground hover:file:bg-primary/90"
                            @change="onAvatarChange"
                        />
                        <Button v-if="previewUrl" type="button" variant="secondary" size="sm" @click="clearAvatar">
                            {{ t('testimonials.form.clear_avatar') }}
                        </Button>
                    </div>
                    <p class="mt-1.5 text-xs text-muted-foreground">{{ t('testimonials.form.avatar_help') }}</p>
                    <div v-if="mode === 'edit' && existingAvatarUrl" class="mt-3 flex items-center gap-2">
                        <input v-model="removeAvatar" type="checkbox" id="remove_avatar" class="rounded border-input" />
                        <label for="remove_avatar" class="text-sm font-medium">{{ t('testimonials.form.remove_avatar') }}</label>
                    </div>
                </div>

                <div class="mt-6 grid gap-4 md:grid-cols-2">
                    <div class="flex items-center gap-2">
                        <input v-model="isActive" type="checkbox" id="is_active" class="rounded border-input" />
                        <label for="is_active" class="text-sm font-medium">{{ t('testimonials.form.active') }}</label>
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium">{{ t('testimonials.form.sort_order') }}</label>
                        <input v-model.number="sortOrder" type="number" class="w-full rounded-md border border-input bg-background px-3 py-2" />
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-4">
                <Button as-child variant="secondary">
                    <Link href="/admin/testimonials">{{ t('common.cancel') }}</Link>
                </Button>
                <Button type="submit" :loading="processing">
                    <span v-if="mode === 'edit'">{{ t('testimonials.edit.save') }}</span>
                    <span v-else>{{ t('testimonials.create.save') }}</span>
                </Button>
            </div>
        </form>
    </div>
</template>

<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { computed, onBeforeUnmount, ref, watch } from 'vue';
import { useI18n } from 'vue-i18n';
import { Button } from '@/components/ui/button';

const { t } = useI18n();

const props = defineProps<{
    mode: 'create' | 'edit';
    name: string;
    position: string;
    company: string;
    text: string;
    rating: number;
    avatar: File | null;
    removeAvatar: boolean;
    existingAvatarUrl: string | null;
    sortOrder: number;
    isActive: boolean;
    processing: boolean;
}>();

const emit = defineEmits<{
    (e: 'submit'): void;
    (e: 'update:name', value: string): void;
    (e: 'update:position', value: string): void;
    (e: 'update:company', value: string): void;
    (e: 'update:text', value: string): void;
    (e: 'update:rating', value: number): void;
    (e: 'update:avatar', value: File | null): void;
    (e: 'update:removeAvatar', value: boolean): void;
    (e: 'update:sortOrder', value: number): void;
    (e: 'update:isActive', value: boolean): void;
}>();

const fileInputRef = ref<HTMLInputElement | null>(null);
const objectUrlRef = ref<string | null>(null);

const name = computed({ get: () => props.name, set: (v) => emit('update:name', v) });
const position = computed({ get: () => props.position, set: (v) => emit('update:position', v) });
const company = computed({ get: () => props.company, set: (v) => emit('update:company', v) });
const text = computed({ get: () => props.text, set: (v) => emit('update:text', v) });
const rating = computed({ get: () => props.rating, set: (v) => emit('update:rating', v) });
const avatar = computed({ get: () => props.avatar, set: (v) => emit('update:avatar', v) });
const removeAvatar = computed({ get: () => props.removeAvatar, set: (v) => emit('update:removeAvatar', v) });
const sortOrder = computed({ get: () => props.sortOrder, set: (v) => emit('update:sortOrder', v) });
const isActive = computed({ get: () => props.isActive, set: (v) => emit('update:isActive', v) });

watch(
    () => avatar.value,
    (file) => {
        if (objectUrlRef.value) {
            URL.revokeObjectURL(objectUrlRef.value);
            objectUrlRef.value = null;
        }
        if (file) objectUrlRef.value = URL.createObjectURL(file);
    }
);

const previewUrl = computed(() => {
    if (objectUrlRef.value) return objectUrlRef.value;
    if (removeAvatar.value) return null;
    return props.existingAvatarUrl;
});

onBeforeUnmount(() => {
    if (objectUrlRef.value) URL.revokeObjectURL(objectUrlRef.value);
});

const onAvatarChange = (e: Event) => {
    const target = e.target as HTMLInputElement;
    avatar.value = target.files?.[0] || null;
};

const clearAvatar = () => {
    avatar.value = null;
    if (fileInputRef.value) fileInputRef.value.value = '';
};
</script>
