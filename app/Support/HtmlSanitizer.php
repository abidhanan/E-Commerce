<?php

namespace App\Support;

use DOMDocument;
use DOMElement;
use DOMNode;

class HtmlSanitizer
{
    private const DROP_TAGS = [
        'script',
        'style',
        'iframe',
        'object',
        'embed',
        'svg',
        'math',
        'meta',
        'link',
        'form',
        'input',
        'button',
        'textarea',
        'select',
        'option',
    ];

    public static function clean(string $html, array $allowedTags, bool $allowImages = false): string
    {
        if (! class_exists(DOMDocument::class)) {
            return strip_tags($html, self::allowedTagsString($allowedTags));
        }

        $document = new DOMDocument();
        $previous = libxml_use_internal_errors(true);
        $document->loadHTML(
            '<!doctype html><html><body><div id="html-sanitizer-root">'.$html.'</div></body></html>',
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
        );
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        $root = $document->getElementById('html-sanitizer-root');

        if (! $root) {
            return '';
        }

        self::sanitizeChildren($root, array_map('strtolower', $allowedTags), $allowImages);

        $clean = '';
        foreach ($root->childNodes as $child) {
            $clean .= $document->saveHTML($child);
        }

        return trim($clean);
    }

    private static function sanitizeChildren(DOMNode $node, array $allowedTags, bool $allowImages): void
    {
        for ($child = $node->firstChild; $child !== null;) {
            $next = $child->nextSibling;

            if ($child->nodeType === XML_COMMENT_NODE) {
                $node->removeChild($child);
                $child = $next;
                continue;
            }

            if ($child instanceof DOMElement) {
                $tag = strtolower($child->tagName);

                if (in_array($tag, self::DROP_TAGS, true) || ($tag === 'img' && ! $allowImages)) {
                    $node->removeChild($child);
                    $child = $next;
                    continue;
                }

                if (! in_array($tag, $allowedTags, true)) {
                    self::unwrap($child);
                    $child = $next;
                    continue;
                }

                self::sanitizeAttributes($child, $allowImages);
                self::sanitizeChildren($child, $allowedTags, $allowImages);
            }

            $child = $next;
        }
    }

    private static function sanitizeAttributes(DOMElement $element, bool $allowImages): void
    {
        $tag = strtolower($element->tagName);

        foreach (iterator_to_array($element->attributes) as $attribute) {
            $name = strtolower($attribute->name);
            $value = trim($attribute->value);

            $allowed = match ($tag) {
                'a' => $name === 'href' && self::isSafeUrl($value),
                'img' => $allowImages && in_array($name, ['src', 'alt'], true) && ($name !== 'src' || self::isSafeUrl($value)),
                default => false,
            };

            if (! $allowed) {
                $element->removeAttribute($attribute->name);
            }
        }

        if ($tag === 'a') {
            $element->setAttribute('target', '_blank');
            $element->setAttribute('rel', 'noopener noreferrer');
        }
    }

    private static function isSafeUrl(string $url): bool
    {
        return (bool) preg_match('#^https?://#i', $url);
    }

    private static function unwrap(DOMElement $element): void
    {
        $parent = $element->parentNode;

        if (! $parent) {
            return;
        }

        while ($element->firstChild) {
            $parent->insertBefore($element->firstChild, $element);
        }

        $parent->removeChild($element);
    }

    private static function allowedTagsString(array $tags): string
    {
        return implode('', array_map(fn (string $tag) => '<'.$tag.'>', $tags));
    }
}
