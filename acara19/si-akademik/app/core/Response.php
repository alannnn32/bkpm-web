<?php

class Response
{
    // Satu-satunya tempat yang membentuk response JSON API
    public static function json(
        int $httpCode,
        bool $success,
        string $message,
        $data = null,
        ?array $errors = null
    ): void {
        http_response_code($httpCode);
        header('Content-Type: application/json; charset=utf-8');

        $body = [
            'success' => $success,
            'message' => $message,
            'data'    => $data,
        ];

        // Daftar kesalahan validasi, hanya dikirim kalau ada
        if ($errors !== null) {
            $body['errors'] = $errors;
        }

        $body['timestamp'] = date('c');

        $flags = JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE;

        // Format rapi hanya saat pengembangan
        if (defined('APP_ENV') && APP_ENV === 'development') {
            $flags |= JSON_PRETTY_PRINT;
        }

        echo json_encode($body, $flags);
        exit;
    }
}