import type { SiteSettings } from '@/types';
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

/** Contact details, links and the service list shared with every page. */
export function useSite() {
    const page = usePage();

    return computed(() => page.props.site as SiteSettings);
}
