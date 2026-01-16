import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

export function usePermissions() {
    const page = usePage<any>();

    const permissions = computed<string[]>(() => page.props.auth?.permissions || []);
    const isSuperAdmin = computed<boolean>(() => Boolean(page.props.auth?.is_super_admin));

    const can = (slug: string) => isSuperAdmin.value || permissions.value.includes(slug);
    const canAny = (slugs: string[]) => isSuperAdmin.value || slugs.some((s) => permissions.value.includes(s));

    return { permissions, isSuperAdmin, can, canAny };
}

