<?php

namespace App\Support;

use enshrined\svgSanitize\data\AttributeInterface;
use enshrined\svgSanitize\data\TagInterface;
use enshrined\svgSanitize\Sanitizer;

/**
 * Cleans the inline SVG icons that staff paste into the admin. The markup is
 * printed straight into public pages, so only plain drawing elements and
 * presentation attributes are kept; scripts, event handlers, embedded
 * documents and links are removed.
 */
class SvgIcon implements AttributeInterface, TagInterface
{
    public static function clean(?string $svg): ?string
    {
        $svg = trim((string) $svg);

        if ($svg === '') {
            return null;
        }

        $sanitizer = new Sanitizer;
        $sanitizer->setAllowedTags(new self);
        $sanitizer->setAllowedAttrs(new self);
        $sanitizer->removeRemoteReferences(true);
        $sanitizer->removeXMLTag(true);
        $sanitizer->minify(true);

        $clean = $sanitizer->sanitize($svg);

        if (! is_string($clean) || ! str_contains($clean, '<svg')) {
            return null;
        }

        return trim($clean);
    }

    /**
     * @return array<int, string>
     */
    public static function getTags()
    {
        return ['svg', 'g', 'path', 'circle', 'ellipse', 'line', 'polyline', 'polygon', 'rect', 'title'];
    }

    /**
     * @return array<int, string>
     */
    public static function getAttributes()
    {
        return [
            'xmlns', 'viewBox', 'width', 'height', 'fill', 'fill-rule', 'clip-rule', 'fill-opacity', 'opacity',
            'stroke', 'stroke-width', 'stroke-linecap', 'stroke-linejoin', 'stroke-miterlimit',
            'stroke-dasharray', 'stroke-dashoffset', 'stroke-opacity', 'transform',
            'd', 'cx', 'cy', 'r', 'rx', 'ry', 'x', 'y', 'x1', 'y1', 'x2', 'y2', 'points',
            'aria-hidden', 'role', 'focusable',
        ];
    }
}
