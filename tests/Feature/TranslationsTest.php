<?php

use Illuminate\Support\Arr;

/**
 * Bengali values that are meant to read the same as the English ones:
 * names, the language's own name, and code samples.
 */
const SAME_IN_BOTH_LANGUAGES = [
    'app.name',
    'language.en',
    'language.bn',
    'services.form.icon_svg_placeholder',
];

/** Bengali values allowed to contain an ASCII digit. Keep this empty unless a value truly needs one. */
const ASCII_DIGITS_ALLOWED = [];

function adminMessages(string $locale): array
{
    $path = resource_path("js/i18n/locales/{$locale}.json");
    $messages = json_decode(file_get_contents($path), true);

    expect($messages)->toBeArray("{$locale}.json is not valid JSON");

    return Arr::dot($messages);
}

function failWithList(string $heading, array $keys): void
{
    expect($keys)->toBeEmpty($heading.":\n  ".implode("\n  ", $keys)."\n");
}

test('the English and Bengali admin translations have the same keys', function () {
    $en = adminMessages('en');
    $bn = adminMessages('bn');

    // One list, so a single run reports the gaps on both sides.
    failWithList('Translation keys that are not in both files', [
        ...array_map(fn ($key) => "{$key} (in en.json, missing from bn.json)", array_keys(array_diff_key($en, $bn))),
        ...array_map(fn ($key) => "{$key} (in bn.json, missing from en.json)", array_keys(array_diff_key($bn, $en))),
    ]);
});

test('every Bengali admin string is translated', function () {
    $en = adminMessages('en');
    $bn = adminMessages('bn');

    $untranslated = [];
    foreach ($bn as $key => $value) {
        if (in_array($key, SAME_IN_BOTH_LANGUAGES, true)) {
            continue;
        }
        if (! is_string($value) || trim($value) === '') {
            $untranslated[] = "{$key} (empty)";
        } elseif (array_key_exists($key, $en) && $value === $en[$key]) {
            $untranslated[] = "{$key} = \"{$value}\"";
        }
    }

    failWithList('Bengali values that are empty or identical to the English value', $untranslated);
});

test('Bengali admin strings use Bengali digits', function () {
    $withAsciiDigits = [];
    foreach (adminMessages('bn') as $key => $value) {
        if (in_array($key, ASCII_DIGITS_ALLOWED, true)) {
            continue;
        }
        if (is_string($value) && preg_match('/[0-9]/', $value)) {
            $withAsciiDigits[] = "{$key} = \"{$value}\"";
        }
    }

    failWithList('Bengali values that contain an ASCII digit (use ০-৯)', $withAsciiDigits);
});
