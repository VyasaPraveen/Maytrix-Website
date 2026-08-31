<?php

declare(strict_types=1);

namespace App\Support;

/**
 * Allowlist-based HTML sanitizer for admin rich-text content.
 *
 * Rich-text fields (e.g. Page / Post body_html) are rendered UNescaped on the
 * public site, so anything an editor pastes is cleaned here before it is stored:
 * dangerous tags are dropped, unknown tags are unwrapped (their text is kept),
 * every on* / disallowed attribute is stripped, and href/src URLs are limited to
 * safe schemes. This is defence-in-depth — the public CSP already blocks inline
 * script execution — but it keeps stored markup clean and predictable.
 */
final class HtmlSanitizer
{
    /** tag => list of attributes allowed on it (plus the global rules below). */
    private const ALLOWED = [
        'p' => [], 'br' => [], 'hr' => [],
        'h2' => [], 'h3' => [], 'h4' => [],
        'strong' => [], 'b' => [], 'em' => [], 'i' => [], 'u' => [], 's' => [],
        'a' => ['href', 'title', 'target', 'rel'],
        'ul' => [], 'ol' => [], 'li' => [],
        'blockquote' => [], 'code' => [], 'pre' => [],
        'span' => [], 'div' => [],
        'img' => ['src', 'alt', 'title', 'width', 'height'],
        'table' => [], 'thead' => [], 'tbody' => [], 'tr' => [],
        'th' => ['colspan', 'rowspan'], 'td' => ['colspan', 'rowspan'],
        'figure' => [], 'figcaption' => [],
    ];

    /** Tags removed entirely, together with their contents. */
    private const DROP = [
        'script', 'style', 'iframe', 'object', 'embed', 'form', 'input', 'button',
        'textarea', 'select', 'option', 'link', 'meta', 'base', 'noscript',
        'svg', 'math', 'title', 'head',
    ];

    public static function clean(?string $html): string
    {
        $html = trim((string) $html);
        if ($html === '') {
            return '';
        }
        if (!class_exists(\DOMDocument::class)) {
            return self::fallbackStrip($html);
        }

        $dom = new \DOMDocument('1.0', 'UTF-8');
        $previous = libxml_use_internal_errors(true);
        // Force UTF-8 and wrap so we can serialise just the inner content.
        $wrapped = '<?xml encoding="UTF-8"?><div>' . $html . '</div>';
        $loaded = $dom->loadHTML($wrapped, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();
        libxml_use_internal_errors($previous);
        if (!$loaded) {
            return self::fallbackStrip($html);
        }

        // The wrapper div is the first <div> in document order.
        $root = $dom->getElementsByTagName('div')->item(0);
        if (!$root) {
            return '';
        }
        self::cleanNode($root, $dom);

        $out = '';
        foreach (iterator_to_array($root->childNodes) as $child) {
            $out .= $dom->saveHTML($child);
        }
        return trim($out);
    }

    /**
     * Used only when the DOM extension is unavailable. strip_tags keeps
     * attributes on allowed tags, so we additionally scrub on*= handlers and
     * javascript:/vbscript: URIs.
     */
    private static function fallbackStrip(string $html): string
    {
        $html = strip_tags($html, '<p><br><h2><h3><h4><strong><b><em><i><u><ul><ol><li><a><blockquote><code><pre>');
        $html = preg_replace('/\son[a-z]+\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)/i', '', $html) ?? $html;
        $html = preg_replace('/(href|src)\s*=\s*("|\')?\s*(javascript|vbscript|data)\s*:[^"\'>\s]*("|\')?/i', '', $html) ?? $html;
        return trim($html);
    }

    private static function cleanNode(\DOMNode $node, \DOMDocument $dom): void
    {
        foreach (iterator_to_array($node->childNodes) as $child) {
            if ($child->nodeType === XML_COMMENT_NODE) {
                $child->parentNode->removeChild($child);
                continue;
            }
            if ($child->nodeType !== XML_ELEMENT_NODE) {
                continue; // keep text nodes as-is
            }
            /** @var \DOMElement $child */
            $tag = strtolower($child->nodeName);

            if (in_array($tag, self::DROP, true)) {
                $child->parentNode->removeChild($child);
                continue;
            }
            if (!isset(self::ALLOWED[$tag])) {
                // Unknown tag: keep its (cleaned) children, drop the wrapper.
                self::cleanNode($child, $dom);
                while ($child->firstChild) {
                    $node->insertBefore($child->firstChild, $child);
                }
                $node->removeChild($child);
                continue;
            }
            self::cleanAttributes($child, $tag);
            self::cleanNode($child, $dom);
        }
    }

    private static function cleanAttributes(\DOMElement $el, string $tag): void
    {
        $allowed = self::ALLOWED[$tag];
        foreach (iterator_to_array($el->attributes) as $attr) {
            $name = strtolower($attr->nodeName);
            if (str_starts_with($name, 'on') || !in_array($name, $allowed, true)) {
                $el->removeAttribute($attr->nodeName);
                continue;
            }
            if (($name === 'href' || $name === 'src') && !self::safeUrl((string) $attr->nodeValue, $tag === 'img')) {
                $el->removeAttribute($attr->nodeName);
            }
        }
        if ($tag === 'a' && strtolower($el->getAttribute('target')) === '_blank') {
            $el->setAttribute('rel', 'noopener noreferrer');
        }
    }

    private static function safeUrl(string $url, bool $isImage): bool
    {
        $url = trim($url);
        if ($url === '') {
            return false;
        }
        // Relative paths / anchors / query strings are fine.
        if (preg_match('#^(/|\#|\?|\./|\.\./)#', $url)) {
            return true;
        }
        if ($isImage && preg_match('#^data:image/(png|jpe?g|gif|webp|svg\+xml);#i', $url)) {
            return true;
        }
        if (preg_match('#^([a-z][a-z0-9+.\-]*):#i', $url, $m)) {
            return in_array(strtolower($m[1]), ['http', 'https', 'mailto', 'tel'], true);
        }
        // No scheme at all → treat as a relative path (e.g. "about").
        return true;
    }
}
