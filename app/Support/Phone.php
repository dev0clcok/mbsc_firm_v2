<?php

namespace App\Support;

/**
 * Phone numbers as visitors type them: with Bengali or ASCII digits, with
 * or without the country code, with spaces and hyphens.
 */
class Phone
{
    private const BENGALI_DIGITS = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];

    /** Bengali digits become 0-9; everything else is left as typed. */
    public static function toAscii(?string $value): ?string
    {
        return $value === null ? null : str_replace(self::BENGALI_DIGITS, range(0, 9), $value);
    }

    /**
     * A Bangladeshi mobile number with or without +880, or an international
     * number written with its country code.
     */
    public static function isValid(string $value): bool
    {
        $compact = preg_replace('/[\s\-().]+/', '', self::toAscii($value));

        return (bool) preg_match('/^(?:\+?880|0)1[3-9]\d{8}$/', $compact)
            || (bool) preg_match('/^(?:\+|00)[1-9]\d{7,14}$/', $compact);
    }

    /**
     * Digits only, with Bangladesh's country code folded into the leading
     * zero, so 01700000002, 8801700000002 and +880 1700-000002 are the
     * same value. Used for searching; the number as typed is kept as well.
     */
    public static function normalize(?string $value): ?string
    {
        $digits = preg_replace('/\D+/', '', (string) self::toAscii($value));

        if ($digits === '') {
            return null;
        }

        if (str_starts_with($digits, '00')) {
            $digits = substr($digits, 2);
        }

        return str_starts_with($digits, '880') ? '0'.substr($digits, 3) : $digits;
    }
}
