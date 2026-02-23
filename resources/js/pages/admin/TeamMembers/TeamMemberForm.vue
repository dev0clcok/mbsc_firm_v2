<template>
    <div class="space-y-6 p-4">
        <div class="flex items-center justify-between">
            <h1 class="text-3xl font-bold">
                {{ mode === 'edit' ? t('team_members.edit.heading') : t('team_members.create.heading') }}
            </h1>
            <Link href="/admin/team-members" class="text-muted-foreground hover:text-foreground">
                {{ t('team_members.back_to_list') }}
            </Link>
        </div>

        <form @submit.prevent="emit('submit')" class="space-y-6">
            <div class="rounded-lg border border-border bg-card p-6">
                <h2 class="mb-4 text-xl font-semibold">{{ t('team_members.form.details') }}</h2>

                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-medium">
                            {{ t('team_members.form.name') }} <span class="text-destructive">*</span>
                        </label>
                        <input
                            v-model="name"
                            type="text"
                            required
                            class="w-full rounded-md border border-input bg-background px-3 py-2"
                            :placeholder="t('team_members.form.name_placeholder')"
                        />
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium">{{ t('team_members.form.position') }}</label>
                        <input
                            v-model="position"
                            type="text"
                            class="w-full rounded-md border border-input bg-background px-3 py-2"
                            :placeholder="t('team_members.form.position_placeholder')"
                        />
                    </div>

                    <div class="md:col-span-2">
                        <label class="mb-2 block text-sm font-medium">{{ t('team_members.form.specialization') }}</label>
                        <input
                            v-model="specialization"
                            type="text"
                            class="w-full rounded-md border border-input bg-background px-3 py-2"
                            :placeholder="t('team_members.form.specialization_placeholder')"
                        />
                    </div>

                    <div class="md:col-span-2">
                        <h3 class="mb-3 text-sm font-semibold">{{ t('team_members.form.social_links') }}</h3>
                        <div class="space-y-3">
                            <div
                                v-for="(link, idx) in socialLinks"
                                :key="idx"
                                class="flex flex-wrap items-center gap-3 rounded-md border border-border bg-muted/30 p-3"
                            >
                                <select
                                    :value="link.platform"
                                    @change="updateSocialLink(idx, 'platform', ($event.target as HTMLSelectElement).value)"
                                    class="w-36 rounded-md border border-input bg-background px-3 py-2 text-sm"
                                >
                                    <option value="">{{ t('team_members.form.select_platform') }}</option>
                                    <option
                                        v-for="(label, key) in platformOptions"
                                        :key="key"
                                        :value="key"
                                    >
                                        {{ label }}
                                    </option>
                                </select>
                                <input
                                    :value="link.url"
                                    @input="updateSocialLink(idx, 'url', ($event.target as HTMLInputElement).value)"
                                    :type="link.platform === 'email' ? 'email' : 'url'"
                                    class="min-w-0 flex-1 rounded-md border border-input bg-background px-3 py-2 text-sm"
                                    :placeholder="link.platform === 'email' ? 'member@example.com' : 'https://...'"
                                />
                                <Button type="button" variant="secondary" size="sm" class="shrink-0" @click="removeSocialLink(idx)">
                                    {{ t('team_members.form.remove') }}
                                </Button>
                            </div>
                            <Button type="button" variant="secondary" @click="addSocialLink">
                                {{ t('team_members.form.add_social_link') }}
                            </Button>
                        </div>
                    </div>

                    <div class="md:col-span-2">
                        <label class="mb-2 block text-sm font-medium">{{ t('team_members.form.image') }}</label>
                        <div v-if="previewUrl" class="mb-3">
                            <img :src="previewUrl" :alt="t('team_members.form.image_preview')" class="h-32 w-32 rounded-lg object-cover" />
                        </div>
                        <div class="flex flex-wrap items-center gap-3">
                            <input
                                ref="fileInputRef"
                                type="file"
                                accept="image/*"
                                class="block w-full max-w-xs text-sm text-muted-foreground file:mr-4 file:rounded-md file:border-0 file:bg-primary file:px-4 file:py-2 file:text-sm file:font-medium file:text-primary-foreground hover:file:bg-primary/90"
                                @change="onImageChange"
                            />
                            <Button v-if="previewUrl" type="button" variant="secondary" size="sm" @click="clearImage">
                                {{ t('team_members.form.clear_image') }}
                            </Button>
                        </div>
                        <p class="mt-1.5 text-xs text-muted-foreground">{{ t('team_members.form.image_help') }}</p>
                        <div v-if="mode === 'edit' && existingImageUrl" class="mt-3 flex items-center gap-2">
                            <input v-model="removeImage" type="checkbox" id="remove_image" class="rounded border-input" />
                            <label for="remove_image" class="text-sm font-medium">{{ t('team_members.form.remove_image') }}</label>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <input v-model="isActive" type="checkbox" id="is_active" class="rounded border-input" />
                        <label for="is_active" class="text-sm font-medium">{{ t('team_members.form.active') }}</label>
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium">{{ t('team_members.form.sort_order') }}</label>
                        <input v-model.number="sortOrder" type="number" class="w-full rounded-md border border-input bg-background px-3 py-2" />
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-4">
                <Button as-child variant="secondary">
                    <Link href="/admin/team-members">{{ t('common.cancel') }}</Link>
                </Button>
                <Button type="submit" :loading="processing">
                    <span v-if="mode === 'edit'">{{ t('team_members.edit.save') }}</span>
                    <span v-else>{{ t('team_members.create.save') }}</span>
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

export interface SocialLink {
    platform: string;
    url: string;
}

const platformOptions: Record<string, string> = {
    facebook: 'Facebook',
    linkedin: 'LinkedIn',
    twitter: 'X (Twitter)',
    instagram: 'Instagram',
    youtube: 'YouTube',
    email: 'Email',
};

const props = defineProps<{
    mode: 'create' | 'edit';
    name: string;
    position: string;
    specialization: string;
    socialLinks: SocialLink[];
    image: File | null;
    removeImage: boolean;
    existingImageUrl: string | null;
    sortOrder: number;
    isActive: boolean;
    processing: boolean;
}>();

const emit = defineEmits<{
    (e: 'submit'): void;
    (e: 'update:name', value: string): void;
    (e: 'update:position', value: string): void;
    (e: 'update:specialization', value: string): void;
    (e: 'update:socialLinks', value: SocialLink[]): void;
    (e: 'update:image', value: File | null): void;
    (e: 'update:removeImage', value: boolean): void;
    (e: 'update:sortOrder', value: number): void;
    (e: 'update:isActive', value: boolean): void;
}>();

const fileInputRef = ref<HTMLInputElement | null>(null);
const objectUrlRef = ref<string | null>(null);

const name = computed({ get: () => props.name, set: (v) => emit('update:name', v) });
const position = computed({ get: () => props.position, set: (v) => emit('update:position', v) });
const specialization = computed({ get: () => props.specialization, set: (v) => emit('update:specialization', v) });
const socialLinks = computed({ get: () => props.socialLinks, set: (v) => emit('update:socialLinks', v) });
const image = computed({ get: () => props.image, set: (v) => emit('update:image', v) });
const removeImage = computed({ get: () => props.removeImage, set: (v) => emit('update:removeImage', v) });
const sortOrder = computed({ get: () => props.sortOrder, set: (v) => emit('update:sortOrder', v) });
const isActive = computed({ get: () => props.isActive, set: (v) => emit('update:isActive', v) });

const addSocialLink = () => {
    socialLinks.value = [...socialLinks.value, { platform: '', url: '' }];
};

const updateSocialLink = (idx: number, field: 'platform' | 'url', value: string) => {
    const next = [...socialLinks.value];
    if (!next[idx]) next[idx] = { platform: '', url: '' };
    next[idx] = { ...next[idx], [field]: value };
    socialLinks.value = next;
};

const removeSocialLink = (idx: number) => {
    socialLinks.value = socialLinks.value.filter((_, i) => i !== idx);
};

watch(
    () => image.value,
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
    if (removeImage.value) return null;
    return props.existingImageUrl;
});

onBeforeUnmount(() => {
    if (objectUrlRef.value) URL.revokeObjectURL(objectUrlRef.value);
});

const onImageChange = (e: Event) => {
    const target = e.target as HTMLInputElement;
    image.value = target.files?.[0] || null;
};

const clearImage = () => {
    image.value = null;
    if (fileInputRef.value) fileInputRef.value.value = '';
};
</script>
