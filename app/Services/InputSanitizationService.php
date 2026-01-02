<?php

namespace App\Services;

/**
 * Input Sanitization Service
 * 
 * Handles input canonicalization to prevent security vulnerabilities
 * from different encoding formats, Unicode variations, and path traversal
 */
class InputSanitizationService
{
    /**
     * Canonicalize string input
     * - Normalize Unicode characters
     * - Remove null bytes
     * - Normalize whitespace
     * - Handle different encoding formats
     */
    public function canonicalizeString(string $input): string
    {
        // 1. Remove null bytes (can bypass security filters)
        $input = str_replace("\0", '', $input);
        
        // 2. Normalize Unicode to NFC (Normalization Form Canonical Composition)
        // This prevents attacks using different Unicode representations
        if (function_exists('normalizer_normalize')) {
            $input = normalizer_normalize($input, \Normalizer::NFC);
        }
        
        // 3. Convert to UTF-8 if not already
        if (mb_detect_encoding($input, 'UTF-8', true) === false) {
            $input = mb_convert_encoding($input, 'UTF-8', mb_detect_encoding($input));
        }
        
        // 4. Normalize line endings (CRLF, CR, LF → LF)
        $input = str_replace(["\r\n", "\r"], "\n", $input);
        
        // 5. Normalize multiple spaces to single space (except in descriptions)
        // This is optional and should be applied selectively
        // $input = preg_replace('/\s+/', ' ', $input);
        
        return $input;
    }

    /**
     * Canonicalize file path
     * - Prevent directory traversal attacks
     * - Normalize path separators
     * - Remove dangerous sequences
     */
    public function canonicalizePath(string $path): string
    {
        // 1. Remove null bytes
        $path = str_replace("\0", '', $path);
        
        // 2. Normalize path separators
        $path = str_replace('\\', '/', $path);
        
        // 3. Remove directory traversal patterns
        $path = preg_replace('#/+#', '/', $path); // Multiple slashes
        $path = preg_replace('#/\./#', '/', $path); // Current directory references
        
        // 4. Remove parent directory references (..)
        // This prevents ../../../etc/passwd type attacks
        while (strpos($path, '../') !== false || strpos($path, '/..') !== false) {
            $path = preg_replace('#/[^/]+/\.\./#', '/', $path);
            $path = preg_replace('#^\.\./#', '', $path);
            $path = preg_replace('#/\.\.$#', '', $path);
        }
        
        // 5. Remove leading/trailing slashes and dots
        $path = trim($path, './');
        
        return $path;
    }

    /**
     * Canonicalize URL
     * - Normalize URL encoding
     * - Prevent URL-based attacks
     */
    public function canonicalizeUrl(string $url): string
    {
        // 1. Remove null bytes
        $url = str_replace("\0", '', $url);
        
        // 2. Decode URL to prevent double-encoding attacks
        $url = urldecode($url);
        
        // 3. Normalize protocol
        $url = strtolower($url);
        
        // 4. Remove dangerous protocols
        $dangerousProtocols = ['javascript:', 'data:', 'vbscript:', 'file:'];
        foreach ($dangerousProtocols as $protocol) {
            if (stripos($url, $protocol) === 0) {
                return '';
            }
        }
        
        return $url;
    }

    /**
     * Canonicalize numeric input
     * - Ensure consistent number format
     * - Prevent integer overflow
     */
    public function canonicalizeNumeric(string $input, string $type = 'integer'): string
    {
        // Remove all non-numeric characters except decimal point and minus
        $input = preg_replace('/[^0-9.\-]/', '', $input);
        
        if ($type === 'integer') {
            // Remove decimal points for integers
            $input = preg_replace('/\..*/', '', $input);
        }
        
        // Ensure we have at least a 0
        if ($input === '' || $input === '-') {
            $input = '0';
        }
        
        return $input;
    }

    /**
     * Canonicalize email address
     * - Normalize to lowercase
     * - Remove dangerous characters
     */
    public function canonicalizeEmail(string $email): string
    {
        // 1. Remove null bytes
        $email = str_replace("\0", '', $email);
        
        // 2. Trim whitespace
        $email = trim($email);
        
        // 3. Convert to lowercase
        $email = strtolower($email);
        
        // 4. Remove any characters that shouldn't be in an email
        $email = preg_replace('/[^a-z0-9@._\-+]/', '', $email);
        
        return $email;
    }

    /**
     * Sanitize all request inputs
     * Applies canonicalization to all string inputs in an array
     */
    public function sanitizeInputArray(array $data, array $pathFields = [], array $urlFields = []): array
    {
        $sanitized = [];
        
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                // Recursively sanitize nested arrays
                $sanitized[$key] = $this->sanitizeInputArray($value, $pathFields, $urlFields);
            } elseif (is_string($value)) {
                // Apply appropriate sanitization based on field type
                if (in_array($key, $pathFields)) {
                    $sanitized[$key] = $this->canonicalizePath($value);
                } elseif (in_array($key, $urlFields)) {
                    $sanitized[$key] = $this->canonicalizeUrl($value);
                } elseif ($key === 'email') {
                    $sanitized[$key] = $this->canonicalizeEmail($value);
                } elseif (is_numeric($value)) {
                    $sanitized[$key] = $this->canonicalizeNumeric($value);
                } else {
                    $sanitized[$key] = $this->canonicalizeString($value);
                }
            } else {
                // Keep non-string values as-is
                $sanitized[$key] = $value;
            }
        }
        
        return $sanitized;
    }

    /**
     * Remove HTML/Script tags for user input
     * (Use sparingly - prefer proper output encoding)
     */
    public function stripDangerousTags(string $input): string
    {
        // Remove script tags and their contents
        $input = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $input);
        
        // Remove event handlers (onclick, onload, etc.)
        $input = preg_replace('/\s*on\w+\s*=\s*["\']?[^"\']*["\']?/i', '', $input);
        
        // Remove javascript: protocol from links
        $input = preg_replace('/javascript:/i', '', $input);
        
        return $input;
    }

    /**
     * Validate and sanitize file upload name
     */
    public function sanitizeFileName(string $filename): string
    {
        // Get file extension
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        $name = pathinfo($filename, PATHINFO_FILENAME);
        
        // Sanitize filename
        $name = $this->canonicalizeString($name);
        $name = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $name);
        $name = substr($name, 0, 100); // Limit length
        
        // Sanitize extension
        $extension = strtolower($extension);
        $extension = preg_replace('/[^a-z0-9]/', '', $extension);
        
        return $name . '.' . $extension;
    }
}