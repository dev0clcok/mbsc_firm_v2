<template>
    <AppLayout>
        <Head title="Services Management" />

        <div class="space-y-6">
            <div class="flex items-center justify-between">
                <h1 class="text-3xl font-bold">Services Management</h1>
                <Link
                    href="/admin/services/create"
                    class="rounded-md bg-primary px-4 py-2 text-primary-foreground hover:bg-primary/90"
                >
                    Add New Service
                </Link>
            </div>

            <div class="rounded-lg border border-border bg-card">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-muted">
                            <tr>
                                <th class="px-6 py-3 text-left text-sm font-semibold">Title</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold">Parent</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold">Status</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold">Sort Order</th>
                                <th class="px-6 py-3 text-right text-sm font-semibold">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border">
                            <template v-for="(group, parentId) in services" :key="parentId">
                                <tr
                                    v-for="service in group"
                                    :key="service.id"
                                    class="hover:bg-muted/50"
                                >
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <span
                                                v-if="service.parent_id"
                                                class="text-muted-foreground"
                                            >
                                                └─
                                            </span>
                                            <span class="font-medium">{{ service.title }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-muted-foreground">
                                        {{ service.parent?.title || '—' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            :class="[
                                                'rounded-full px-2 py-1 text-xs font-medium',
                                                service.is_active
                                                    ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200'
                                                    : 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200',
                                            ]"
                                        >
                                            {{ service.is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-muted-foreground">
                                        {{ service.sort_order }}
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <Link
                                                :href="`/admin/services/${service.id}/edit`"
                                                class="text-primary hover:underline"
                                            >
                                                Edit
                                            </Link>
                                            <button
                                                @click="deleteService(service)"
                                                class="text-destructive hover:underline"
                                            >
                                                Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';

interface Props {
    services: Record<string | number, Array<{
        id: number;
        title: string;
        parent_id: number | null;
        is_active: boolean;
        sort_order: number;
        parent?: {
            title: string;
        } | null;
    }>>;
}

defineProps<Props>();

const deleteService = (service: any) => {
    if (confirm(`Are you sure you want to delete "${service.title}"?`)) {
        router.delete(`/admin/services/${service.id}`);
    }
};
</script>
