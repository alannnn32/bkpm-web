<?php

class Logger
{
    // Lokasi file log: si-akademik/storage/logs/app.log
    private static function file(): string
    {
        return __DIR__ . '/../../storage/logs/app.log';
    }

    public static function info(string $message): void
    {
        self::write('INFO', $message);
    }

    public static function error(string $message): void
    {
        self::write('ERROR', $message);
    }

    private static function write(string $level, string $message): void
    {
        $file = self::file();
        $dir = dirname($file);

        // Buat folder storage/logs kalau belum ada
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        // Satu kejadian = satu baris
        $message = str_replace(["\r", "\n"], ' ', $message);

        $waktu = (new DateTime('now', new DateTimeZone('Asia/Jakarta')))
            ->format('Y-m-d H:i:s');

        // Tipe 3 = tambahkan teks ke akhir file
        error_log("{$waktu} - [{$level}] {$message}" . PHP_EOL, 3, $file);
    }
}