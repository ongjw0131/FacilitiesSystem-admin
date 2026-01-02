<?php

namespace App\Services;

/**
 * Output Encoding Service
 * 
 * Provides contextual output encoding for different contexts:
 * - HTML context
 * - JavaScript context
 * - URL context
 * - CSS context
 * - JSON context
 */
class OutputEncodingService
{
    /**
     * Encode for HTML context
     * Already done by Blade {{ }}, but provided for manual use
     */
    public function encodeHtml(string $data): string
    {
        return htmlspecialchars($data, ENT_QUOTES | ENT_HTML5, 'UTF-8', true);
    }

    /**
     * Encode for HTML attribute context
     * More strict than general HTML encoding
     */
    public function encodeHtmlAttribute(string $data): string
    {
        return htmlspecialchars($data, ENT_QUOTES | ENT_HTML5, 'UTF-8', true);
    }

    /**
     * Encode for JavaScript context
     * CRITICAL: Use when embedding data in <script> tags or inline JS
     */
    public function encodeJavaScript(string $data): string
    {
        // Escape characters that have special meaning in JavaScript
        $encoded = addslashes($data);
        
        // Additional encoding for problematic characters
        $encoded = str_replace([
            "\r", "\n", "\t", 
            '</', '<script', '</script',
            '<!--', '-->'
        ], [
            '\r', '\n', '\t',
            '<\/', '<\script', '<\/script',
            '<\!--', '--\>'
        ], $encoded);
        
        return $encoded;
    }

    /**
     * Encode for JavaScript string literal
     * For use inside JavaScript strings
     */
    public function encodeJavaScriptString(string $data): string
    {
        // JSON encode provides proper JavaScript string escaping
        return json_encode($data, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
    }

    /**
     * Encode for URL/Query parameter context
     */
    public function encodeUrl(string $data): string
    {
        return rawurlencode($data);
    }

    /**
     * Encode for URL path component
     */
    public function encodeUrlPath(string $data): string
    {
        return implode('/', array_map('rawurlencode', explode('/', $data)));
    }

    /**
     * Encode for CSS context
     * CRITICAL: Use when embedding user data in CSS
     */
    public function encodeCss(string $data): string
    {
        // Encode for CSS string or property value
        $encoded = '';
        $length = mb_strlen($data, 'UTF-8');
        
        for ($i = 0; $i < $length; $i++) {
            $char = mb_substr($data, $i, 1, 'UTF-8');
            $ord = $this->utf8Ord($char);
            
            // Escape special CSS characters
            if ($ord < 32 || $ord > 126 || in_array($char, ['\\', '"', "'", '/', '<', '>', '&'])) {
                $encoded .= '\\' . sprintf('%X', $ord) . ' ';
            } else {
                $encoded .= $char;
            }
        }
        
        return $encoded;
    }

    /**
     * Encode for JSON context
     */
    public function encodeJson($data): string
    {
        return json_encode($data, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
    }

    /**
     * Encode for XML context
     */
    public function encodeXml(string $data): string
    {
        return htmlspecialchars($data, ENT_XML1 | ENT_QUOTES, 'UTF-8', true);
    }

    /**
     * Encode for data attribute context
     * For use in HTML5 data-* attributes
     */
    public function encodeDataAttribute(string $data): string
    {
        return htmlspecialchars($data, ENT_QUOTES | ENT_HTML5, 'UTF-8', true);
    }

    /**
     * Safe JSON for inline script
     * Combines JSON encoding with script safety
     */
    public function safeJsonForScript($data): string
    {
        $json = $this->encodeJson($data);
        
        // Prevent </script> tag injection
        $json = str_replace(['</', '</script'], ['<\/', '<\/script'], $json);
        
        return $json;
    }

    /**
     * Encode for rich text/WYSIWYG content
     * Allows safe HTML tags while encoding dangerous content
     */
    public function encodeRichText(string $html, array $allowedTags = ['p', 'br', 'strong', 'em', 'u', 'a', 'ul', 'ol', 'li']): string
    {
        // Use HTMLPurifier or similar for production
        // This is a simplified version
        
        // Strip all tags except allowed ones
        $allowed = '<' . implode('><', $allowedTags) . '>';
        $cleaned = strip_tags($html, $allowed);
        
        // Additional sanitization for allowed tags
        $cleaned = preg_replace('/<a[^>]*href=["\']?javascript:/i', '<a href="', $cleaned);
        $cleaned = preg_replace('/on\w+\s*=/i', '', $cleaned); // Remove event handlers
        
        return $cleaned;
    }

    /**
     * Helper: Get UTF-8 character code
     */
    private function utf8Ord(string $char): int
    {
        $bytes = unpack('C*', $char);
        $code = 0;
        
        if (count($bytes) == 1) {
            $code = $bytes[1];
        } elseif (count($bytes) == 2) {
            $code = (($bytes[1] & 0x1F) << 6) | ($bytes[2] & 0x3F);
        } elseif (count($bytes) == 3) {
            $code = (($bytes[1] & 0x0F) << 12) | (($bytes[2] & 0x3F) << 6) | ($bytes[3] & 0x3F);
        } elseif (count($bytes) == 4) {
            $code = (($bytes[1] & 0x07) << 18) | (($bytes[2] & 0x3F) << 12) | (($bytes[3] & 0x3F) << 6) | ($bytes[4] & 0x3F);
        }
        
        return $code;
    }

    /**
     * Create safe inline style attribute
     */
    public function safeInlineStyle(array $styles): string
    {
        $safe = [];
        
        // Whitelist of safe CSS properties
        $allowedProperties = [
            'color', 'background-color', 'border-color',
            'font-size', 'font-weight', 'text-align',
            'padding', 'margin', 'width', 'height',
            'display', 'position', 'top', 'left', 'right', 'bottom'
        ];
        
        foreach ($styles as $property => $value) {
            if (in_array($property, $allowedProperties)) {
                // Encode the value
                $encodedValue = $this->encodeCss($value);
                $safe[] = $property . ': ' . $encodedValue;
            }
        }
        
        return implode('; ', $safe);
    }
}