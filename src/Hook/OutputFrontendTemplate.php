<?php

namespace Dud\ContaoRSupBundle\Hook;

class OutputFrontendTemplate
{
    public function replace(string $buffer, string $template): string
    {
        // Fast exit: avoid DOM parsing if no relevant pattern exists
        if (strpos($buffer, '(R)') === false &&
            strpos($buffer, '(r)') === false &&
            strpos($buffer, '®') === false) {
            return $buffer;
        }

        // Suppress libxml warnings for malformed HTML fragments
        libxml_use_internal_errors(true);

        $dom = new \DOMDocument('1.0', 'UTF-8');

        // Load HTML with encoding hint to preserve UTF-8 characters
        $dom->loadHTML('<?xml encoding="UTF-8">' . $buffer, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

        $xpath = new \DOMXPath($dom);

        foreach ($xpath->query('//text()') as $textNode) {

            // Skip script and style elements to avoid breaking JS/CSS
            if ($textNode->parentNode && in_array($textNode->parentNode->nodeName, ['script','style'])) {
                continue;
            }

            $original = $textNode->nodeValue;

            // Normalize (R) and (r) to ®
            $text = preg_replace('/\(r\)/i', '®', $original);

            // If no replacement needed, skip processing
            if (strpos($text, '®') === false) {
                if ($text !== $original) {
                    $textNode->nodeValue = $text;
                }
                continue;
            }

            // Build fragment with <sup>®</sup> elements
            $fragment = $dom->createDocumentFragment();
            $parts = explode('®', $text);
            $lastIndex = count($parts) - 1;

            foreach ($parts as $i => $part) {
                if ($part !== '') {
                    $fragment->appendChild($dom->createTextNode($part));
                }

                if ($i < $lastIndex) {
                    $sup = $dom->createElement('sup', '®');
                    $fragment->appendChild($sup);
                }
            }

            // Replace original text node with new fragment
            $textNode->parentNode->replaceChild($fragment, $textNode);
        }

        // Return processed HTML
        return $dom->saveHTML();
    }
}
