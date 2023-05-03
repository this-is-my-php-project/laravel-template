<?php

use Illuminate\Support\Facades\Config;

if (!function_exists('getAESKey')) {
    /**
     * @return string
     */
    function getAESKey(): array
    {
        return [
            'key' => Config::get('app.aes.key'),
            'iv' => Config::get('app.aes.iv'),
            'cipher' => Config::get('app.cipher'),
            'options' => 0,
        ];
    }
}

if (!function_exists('decryptData')) {
    /**
     * @param $encryptedData
     * @return string|int
     */
    function decryptData(string $encryptedData): string
    {
        return openssl_decrypt(
            $encryptedData,
            getAESKey()['cipher'],
            getAESKey()['key'],
            getAESKey()['options'],
            getAESKey()['iv']
        );
    }
}

if (!function_exists('encryptData')) {
    /**
     * @param $data
     * @return string
     */
    function encryptData(string $data): string
    {
        return openssl_encrypt(
            $data,
            getAESKey()['cipher'],
            getAESKey()['key'],
            getAESKey()['options'],
            getAESKey()['iv']
        );
    }
}

if (!function_exists('generateOTPCode')) {
    /**
     * @return string
     */
    function generateOTPCode(string|int $length = 4): string
    {
        $length = $length === 4 ? 4 : 6;
        $number = random_int(
            min: 000_000,
            max: 999_999,
        );

        return str_pad(
            string: strval($number),
            length: 6,
            pad_string: '0',
            pad_type: STR_PAD_LEFT,
        );
    }
}
