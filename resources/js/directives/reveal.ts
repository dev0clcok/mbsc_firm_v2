import type { Directive } from 'vue';

let observer: IntersectionObserver | null = null;

const getObserver = () =>
    (observer ??= new IntersectionObserver(
        (entries) => {
            for (const entry of entries) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer?.unobserve(entry.target);
                }
            }
        },
        { rootMargin: '0px 0px -10% 0px' },
    ));

/**
 * Fades an element in the first time it scrolls into view.
 *
 * Elements already on screen when the page loads are left untouched, and
 * nothing happens for visitors who prefer reduced motion, so the markup is
 * fully visible by default (including when server-rendered or without JS).
 */
export const vReveal: Directive<HTMLElement> = {
    mounted(el) {
        if (!('IntersectionObserver' in window) || window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            return;
        }

        if (el.getBoundingClientRect().top < window.innerHeight) {
            return;
        }

        el.classList.add('reveal');
        getObserver().observe(el);
    },
    unmounted(el) {
        observer?.unobserve(el);
    },
    getSSRProps: () => ({}),
};
