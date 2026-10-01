import { computed } from 'vue';
import { useI18n } from 'vue-i18n';

const BENGALI_DIGITS = '০১২৩৪৫৬৭৮৯';

/**
 * Number display for the active admin language: Bengali digits in Bengali,
 * ASCII digits in English. Only for numbers shown to the reader, never for
 * values that are data (phone numbers, slugs, ids, form inputs).
 */
export function useLocaleFormat() {
    const { locale } = useI18n();

    const numberLocale = computed(() => (locale.value === 'bn' ? 'bn-BD' : 'en'));

    const formatNumber = (value: number | null | undefined) =>
        value === null || value === undefined ? '' : new Intl.NumberFormat(numberLocale.value).format(value);

    // For text the server already formatted, such as a date, where only the digits need changing.
    const localizeDigits = (value: string | number | null | undefined) => {
        const text = value === null || value === undefined ? '' : String(value);
        return locale.value === 'bn' ? text.replace(/[0-9]/g, (digit) => BENGALI_DIGITS[Number(digit)]) : text;
    };

    return { numberLocale, formatNumber, localizeDigits };
}
