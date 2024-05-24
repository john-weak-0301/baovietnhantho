<?php

namespace Core\Media;

class Helper
{
    /**
     * Returns a filtered list of supported audio formats.
     *
     * @return array Supported audio formats.
     */
    public static function getAudioExtensions(): array
    {
        return apply_filters('audio_extensions', ['mp3', 'ogg', 'flac', 'm4a', 'wav']);
    }

    /**
     * Returns a filtered list of supported video formats.
     *
     * @return array List of supported video formats.
     */
    public static function getVideoExtensions(): array
    {
        return apply_filters('video_extensions', ['mp4', 'm4v', 'webm', 'ogv', 'flv']);
    }

    /**
     * Convert number of bytes largest unit bytes will fit into.
     *
     * @param  int  $bytes  Number of bytes. Note max integer size for integers.
     * @param  int  $decimals  Optional. Precision of number of decimal places.
     * @return string
     */
    public static function sizeFormat(int $bytes, int $decimals = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        /** @noinspection TypeUnsafeComparisonInspection */
        if ($bytes == 0) {
            return number_format(0, $decimals).' '.$units[1];
        }

        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }

        return number_format($bytes, $decimals).' '.$units[$i];
    }

    /**
     * Determines the maximum upload size allowed in php.ini.
     *
     * @return int Allowed upload size.
     */
    public static function getMaxUploadSize(): int
    {
        $uBytes = static::convertHrToBytes(ini_get('upload_max_filesize'));

        $pBytes = static::convertHrToBytes(ini_get('post_max_size'));

        return apply_filters('upload_size_limit', min($uBytes, $pBytes), $uBytes, $pBytes);
    }

    /**
     * Converts a shorthand byte value to an integer byte value.
     *
     * @param  string  $value  A (PHP ini) byte value, either shorthand or ordinary.
     * @return int An integer byte value.
     *
     * @link https://secure.php.net/manual/en/function.ini-get.php
     * @link https://secure.php.net/manual/en/faq.using.php#faq.using.shorthandbytes
     */
    public static function convertHrToBytes($value): int
    {
        $value = strtolower(trim($value));
        $bytes = (int) $value;

        if (false !== strpos($value, 'g')) {
            $bytes *= 1024 ** 3;
        } elseif (false !== strpos($value, 'm')) {
            $bytes *= 1024 ** 2;
        } elseif (false !== strpos($value, 'k')) {
            $bytes *= 1024;
        }

        // Deal with large (float) values which run into the maximum integer size.
        return min($bytes, PHP_INT_MAX);
    }
}
