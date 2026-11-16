<?php

class Auth
{
    // Base64URL: Base64 yang aman dipakai di URL dan header
    private static function base64urlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    private static function base64urlDecode(string $data): string|false
    {
        $kurang = strlen($data) % 4;

        if ($kurang) {
            $data .= str_repeat('=', 4 - $kurang);
        }

        return base64_decode(strtr($data, '-_', '+/'), true);
    }

    // Tanda tangan HMAC-SHA256 memakai kunci rahasia
    private static function sign(string $input): string
    {
        return self::base64urlEncode(
            hash_hmac('sha256', $input, JWT_SECRET, true)
        );
    }

    // Membuat token: header.payload.signature
    public static function generateToken(array $payload): string
    {
        $header = self::base64urlEncode(
            json_encode(['alg' => 'HS256', 'typ' => 'JWT'])
        );

        $payload['iat'] = time();                 // waktu dibuat
        $payload['exp'] = time() + JWT_TTL;       // waktu kedaluwarsa

        $payloadEnc = self::base64urlEncode(json_encode($payload));

        return "$header.$payloadEnc." . self::sign("$header.$payloadEnc");
    }

    // Mengembalikan payload kalau token valid, false kalau tidak
    public static function validateToken(string $token): array|false
    {
        $parts = explode('.', $token);

        if (count($parts) !== 3) {
            return false;
        }

        [$header, $payload, $signature] = $parts;

        // 1. Tanda tangan harus cocok (hash_equals aman dari timing attack)
        if (!hash_equals(self::sign("$header.$payload"), $signature)) {
            return false;
        }

        // 2. Algoritma harus HS256
        $headerData = json_decode((string) self::base64urlDecode($header), true);

        if (!is_array($headerData) || ($headerData['alg'] ?? '') !== 'HS256') {
            return false;
        }

        // 3. Payload harus terbaca dan belum kedaluwarsa
        $data = json_decode((string) self::base64urlDecode($payload), true);

        if (!is_array($data) || !isset($data['exp']) || $data['exp'] < time()) {
            return false;
        }

        return $data;
    }

    // Membaca token dari header "Authorization: Bearer <token>"
    private static function bacaToken(): ?string
    {
        $header = $_SERVER['HTTP_AUTHORIZATION']
            ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION']
            ?? '';

        if ($header === '' && function_exists('getallheaders')) {
            foreach (getallheaders() as $nama => $nilai) {
                if (strtolower($nama) === 'authorization') {
                    $header = $nilai;
                    break;
                }
            }
        }

        if (preg_match('/^Bearer\s+(\S+)$/i', trim($header), $cocok)) {
            return $cocok[1];
        }

        return null;
    }

    // Dipanggil di awal endpoint yang dilindungi; mengembalikan payload token
    public static function requireAuth(): array
    {
        $token = self::bacaToken();

        if ($token === null) {
            Response::json(401, false, 'Token tidak ditemukan.');
        }

        $payload = self::validateToken($token);

        if ($payload === false) {
            Response::json(401, false, 'Token tidak valid atau kedaluwarsa.');
        }

        return $payload;
    }
}