import { router } from '@inertiajs/vue3';
import { onBeforeUnmount, onMounted } from 'vue';

/**
 * Warns before leaving a form that has unsaved changes, both for in-app
 * navigation and for closing or reloading the tab.
 *
 * Pass a getter so the latest state is read each time, and the message to
 * show in the confirmation.
 */
export function useUnsavedWarning(isDirty: () => boolean, message: string) {
    let submitting = false;
    let removeListener: (() => void) | null = null;

    const onBeforeUnload = (event: BeforeUnloadEvent) => {
        if (isDirty() && !submitting) {
            event.preventDefault();
        }
    };

    onMounted(() => {
        window.addEventListener('beforeunload', onBeforeUnload);

        removeListener = router.on('before', (event) => {
            // Saving the form itself is not "leaving".
            if (event.detail.visit.method !== 'get') {
                submitting = true;
                return;
            }

            if (isDirty() && !submitting && !window.confirm(message)) {
                event.preventDefault();
            }
        });
    });

    const stopFinish = router.on('finish', () => {
        submitting = false;
    });

    onBeforeUnmount(() => {
        window.removeEventListener('beforeunload', onBeforeUnload);
        removeListener?.();
        stopFinish();
    });
}
