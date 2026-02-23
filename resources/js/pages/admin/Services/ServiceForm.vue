<template>
    <div class="space-y-6 p-4">
        <div class="flex items-center justify-between">
            <h1 class="text-3xl font-bold">
                {{ mode === 'edit' ? t('services.edit.heading') : t('services.create.heading') }}
            </h1>
            <Link href="/admin/services" class="text-muted-foreground hover:text-foreground">
                {{ t('services.back_to_list') }}
            </Link>
        </div>

        <form @submit.prevent="emit('submit')" class="space-y-6">
            <div class="grid gap-6">
                <div class="space-y-6">
                    <div class="rounded-lg border border-border bg-card p-6">
                        <h2 class="mb-4 text-xl font-semibold">{{ t('services.form.details') }}</h2>

                        <div class="grid gap-4 md:grid-cols-2">
                            <div>
                                <label class="mb-2 block text-sm font-medium">
                                    {{ t('services.form.title') }} <span class="text-destructive">*</span>
                                </label>
                                <input
                                    v-model="title"
                                    type="text"
                                    required
                                    class="w-full rounded-md border border-input bg-background px-3 py-2"
                                    :placeholder="t('services.form.title_placeholder')"
                                />
                            </div>

                            <div>
                                <label class="mb-2 block text-sm font-medium">
                                    {{ t('services.form.slug') }} <span class="text-destructive">*</span>
                                </label>
                                <input
                                    v-model="slug"
                                    type="text"
                                    required
                                    class="w-full rounded-md border border-input bg-background px-3 py-2 font-mono"
                                    :placeholder="t('services.form.slug_placeholder')"
                                />
                                <p class="mt-1 text-xs text-muted-foreground">
                                    {{ t('services.form.slug_help') }}
                                </p>
                            </div>
                        </div>

                        <div class="mt-4">
                            <label class="mb-2 block text-sm font-medium">{{ t('services.form.short_description') }}</label>
                            <textarea
                                v-model="shortDescription"
                                rows="3"
                                class="w-full rounded-md border border-input bg-background px-3 py-2"
                                :placeholder="t('services.form.short_description_placeholder')"
                            />
                        </div>

                        <div class="mt-4">
                            <label class="mb-2 block text-sm font-medium">{{ t('services.form.description') }}</label>
                            <textarea
                                v-model="description"
                                rows="6"
                                class="w-full rounded-md border border-input bg-background px-3 py-2"
                                :placeholder="t('services.form.description_placeholder')"
                            />
                        </div>
                    </div>

                    <div class="rounded-lg border border-border bg-card p-6">
                        <h2 class="mb-4 text-xl font-semibold">{{ t('services.form.features') }}</h2>

                        <div class="space-y-3">
                            <div v-for="(f, idx) in features" :key="idx" class="flex items-center gap-3">
                                <input
                                    :value="f"
                                    @input="updateFeature(idx, ($event.target as HTMLInputElement).value)"
                                    type="text"
                                    class="w-full rounded-md border border-input bg-background px-3 py-2"
                                    :placeholder="t('services.form.feature_placeholder')"
                                />
                                <Button type="button" variant="secondary" class="shrink-0" @click="removeFeature(idx)">
                                    {{ t('services.form.remove') }}
                                </Button>
                            </div>

                            <Button type="button" variant="secondary" @click="addFeature">
                                {{ t('services.form.add_feature') }}
                            </Button>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="rounded-lg border border-border bg-card p-6">
                        <h2 class="mb-4 text-xl font-semibold">{{ t('services.form.media') }}</h2>

                        <div class="space-y-4">
                            <div>
                                <label class="mb-2 block text-sm font-medium">{{ t('services.form.image') }}</label>

                                <!-- Preview: existing image or selected file -->
                                <div
                                    v-if="previewUrl"
                                    class="mb-3 overflow-hidden rounded-lg border border-border bg-muted/30"
                                >
                                    <img
                                        :src="previewUrl"
                                        :alt="t('services.form.image_preview')"
                                        class="h-48 w-full object-cover"
                                    />
                                </div>

                                <div class="flex flex-wrap items-center gap-3">
                                    <input
                                        ref="fileInputRef"
                                        type="file"
                                        accept="image/*"
                                        class="block w-full max-w-xs text-sm text-muted-foreground file:mr-4 file:rounded-md file:border-0 file:bg-primary file:px-4 file:py-2 file:text-sm file:font-medium file:text-primary-foreground hover:file:bg-primary/90"
                                        @change="onImageChange"
                                    />
                                    <Button
                                        v-if="previewUrl"
                                        type="button"
                                        variant="secondary"
                                        size="sm"
                                        @click="clearImage"
                                    >
                                        {{ t('services.form.clear_image') }}
                                    </Button>
                                </div>

                                <p class="mt-1.5 text-xs text-muted-foreground">
                                    {{ t('services.form.image_help') }}
                                </p>

                                <!-- Remove current image (edit mode only) -->
                                <div v-if="mode === 'edit' && existingImageUrl" class="mt-3 flex items-center gap-2">
                                    <input
                                        v-model="removeImage"
                                        type="checkbox"
                                        id="remove_image"
                                        class="rounded border-input"
                                    />
                                    <label for="remove_image" class="text-sm font-medium">
                                        {{ t('services.form.remove_image') }}
                                    </label>
                                </div>
                            </div>

                            <div>
                                <label class="mb-2 block text-sm font-medium">{{ t('services.form.icon_svg') }}</label>
                                <textarea
                                    v-model="iconSvg"
                                    rows="6"
                                    class="w-full rounded-md border border-input bg-background px-3 py-2 font-mono text-xs"
                                    :placeholder="t('services.form.icon_svg_placeholder')"
                                />
                                <p class="mt-1 text-xs text-muted-foreground">
                                    {{ t('services.form.icon_svg_help') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-lg border border-border bg-card p-6">
                        <h2 class="mb-4 text-xl font-semibold">{{ t('services.form.settings') }}</h2>

                        <div class="grid gap-6 md:grid-cols-3">
                            <div class="flex items-center gap-2">
                                <input v-model="isActive" type="checkbox" id="is_active" class="rounded border-input" />
                                <label for="is_active" class="text-sm font-medium">{{ t('services.form.active') }}</label>
                            </div>

                            <div>
                                <label class="mb-2 block text-sm font-medium">{{ t('services.form.sort_order') }}</label>
                                <input
                                    v-model.number="sortOrder"
                                    type="number"
                                    class="w-full rounded-md border border-input bg-background px-3 py-2"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-4">
                <Button as-child variant="secondary">
                    <Link href="/admin/services">{{ t('common.cancel') }}</Link>
                </Button>
                <Button type="submit" :loading="processing">
                    <span v-if="mode === 'edit'">{{ t('services.edit.save') }}</span>
                    <span v-else>{{ t('services.create.save') }}</span>
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
    slug: string;
    title: string;
    shortDescription: string;
    description: string;
    iconSvg: string;
    image: File | null;
    removeImage: boolean;
    existingImageUrl: string | null;
    features: string[];
    sortOrder: number;
    isActive: boolean;
    processing: boolean;
}>();

const emit = defineEmits<{
    (e: 'submit'): void;
    (e: 'update:slug', value: string): void;
    (e: 'update:title', value: string): void;
    (e: 'update:shortDescription', value: string): void;
    (e: 'update:description', value: string): void;
    (e: 'update:iconSvg', value: string): void;
    (e: 'update:image', value: File | null): void;
    (e: 'update:removeImage', value: boolean): void;
    (e: 'update:features', value: string[]): void;
    (e: 'update:sortOrder', value: number): void;
    (e: 'update:isActive', value: boolean): void;
}>();

const fileInputRef = ref<HTMLInputElement | null>(null);

const slug = computed({
    get: () => props.slug,
    set: (v: string) => emit('update:slug', v),
});
const title = computed({
    get: () => props.title,
    set: (v: string) => emit('update:title', v),
});
const shortDescription = computed({
    get: () => props.shortDescription,
    set: (v: string) => emit('update:shortDescription', v),
});
const description = computed({
    get: () => props.description,
    set: (v: string) => emit('update:description', v),
});
const iconSvg = computed({
    get: () => props.iconSvg,
    set: (v: string) => emit('update:iconSvg', v),
});
const image = computed({
    get: () => props.image,
    set: (v: File | null) => emit('update:image', v),
});
const removeImage = computed({
    get: () => props.removeImage,
    set: (v: boolean) => emit('update:removeImage', v),
});

const objectUrlRef = ref<string | null>(null);

watch(
    () => image.value,
    (file) => {
        if (objectUrlRef.value) {
            URL.revokeObjectURL(objectUrlRef.value);
            objectUrlRef.value = null;
        }
        if (file) {
            objectUrlRef.value = URL.createObjectURL(file);
        }
    }
);

const previewUrl = computed(() => {
    if (objectUrlRef.value) return objectUrlRef.value;
    if (removeImage.value) return null;
    return props.existingImageUrl;
});

onBeforeUnmount(() => {
    if (objectUrlRef.value) {
        URL.revokeObjectURL(objectUrlRef.value);
    }
});

const onImageChange = (e: Event) => {
    const target = e.target as HTMLInputElement;
    const file = target.files?.[0];
    image.value = file || null;
};

const clearImage = () => {
    image.value = null;
    if (fileInputRef.value) {
        fileInputRef.value.value = '';
    }
};

const features = computed({
    get: () => props.features,
    set: (v: string[]) => emit('update:features', v),
});
const sortOrder = computed({
    get: () => props.sortOrder,
    set: (v: number) => emit('update:sortOrder', v),
});
const isActive = computed({
    get: () => props.isActive,
    set: (v: boolean) => emit('update:isActive', v),
});

const addFeature = () => {
    features.value = [...features.value, ''];
};

const updateFeature = (idx: number, value: string) => {
    const next = [...features.value];
    next[idx] = value;
    features.value = next;
};

const removeFeature = (idx: number) => {
    features.value = features.value.filter((_, i) => i !== idx);
};
</script>

