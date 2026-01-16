import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

export function usePermissions() {
    const page = usePage<any>();

    const permissions = computed<string[]>(() => page.props.auth?.permissions || []);

    const can = (slug: string) => permissions.value.includes(slug);
    const canAny = (slugs: string[]) => slugs.some((s) => permissions.value.includes(s));

    return { permissions, can, canAny };
}

