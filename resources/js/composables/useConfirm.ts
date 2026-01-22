import { reactive } from 'vue';

export type ConfirmVariant = 'default' | 'destructive' | 'secondary';

export type ConfirmOptions = {
    title: string;
    description: string;
    details?: string;
    confirmText?: string;
    cancelText?: string;
    confirmVariant?: ConfirmVariant;
};

type ConfirmState = {
    open: boolean;
    loading: boolean;
    options: Required<Pick<ConfirmOptions, 'title' | 'description'>> &
        Omit<ConfirmOptions, 'title' | 'description'>;
    resolver: null | ((v: boolean) => void);
};

const state = reactive<ConfirmState>({
    open: false,
    loading: false,
    options: {
        title: 'Confirm',
        description: 'Are you sure?',
        details: undefined,
        confirmText: 'Confirm',
        cancelText: 'Cancel',
        confirmVariant: 'destructive',
    },
    resolver: null,
});

export function useConfirm() {
    const confirm = (options: ConfirmOptions) => {
        // If a dialog is already open, close it and resolve as cancelled.
        if (state.open && state.resolver) {
            state.resolver(false);
        }

        state.options = {
            title: options.title,
            description: options.description,
            details: options.details,
            confirmText: options.confirmText ?? 'Confirm',
            cancelText: options.cancelText ?? 'Cancel',
            confirmVariant: options.confirmVariant ?? 'destructive',
        };

        state.loading = false;
        state.open = true;

        return new Promise<boolean>((resolve) => {
            state.resolver = resolve;
        });
    };

    const onConfirm = () => {
        state.open = false;
        state.resolver?.(true);
        state.resolver = null;
    };

    const onClose = () => {
        state.open = false;
        state.resolver?.(false);
        state.resolver = null;
    };

    return {
        state,
        confirm,
        onConfirm,
        onClose,
    };
}

