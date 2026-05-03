<?php

namespace App\Libraries;

/**
 * FonnteService
 * Wrapper untuk API Fonnte (https://fonnte.com)
 * 
 * Pastikan sudah set di .env:
 * FONNTE_TOKEN = token_anda_disini
 */
class FonnteService
{
    protected string $apiKey;
    protected string $apiUrl = 'https://api.fonnte.com/send';

    public function __construct()
    {
        $this->apiKey = env('FONNTE_TOKEN', '');
    }

    /**
     * Kirim pesan WA ke satu nomor
     * 
     * @param  string $phone   Nomor tujuan (08xxx atau 628xxx)
     * @param  string $message Isi pesan
     * @return array  ['success' => bool, 'message' => string]
     */
    public function send(string $phone, string $message): array
    {
        if (empty($this->apiKey)) {
            return [
                'success' => false,
                'message' => 'FONNTE_TOKEN belum diset di file .env',
            ];
        }

        $phone = $this->normalizePhone($phone);

        if (empty($phone)) {
            return [
                'success' => false,
                'message' => 'Nomor telepon tidak valid',
            ];
        }

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL            => $this->apiUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => http_build_query([
                'target'  => $phone,
                'message' => $message,
            ]),
            CURLOPT_HTTPHEADER     => [
                'Authorization: ' . $this->apiKey,
            ],
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_SSL_VERIFYPEER => true,
        ]);

        $response  = curl_exec($ch);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($curlError) {
            return [
                'success' => false,
                'message' => 'cURL error: ' . $curlError,
            ];
        }

        $decoded = json_decode($response, true) ?? [];

        // Fonnte return {"status": true} kalau berhasil
        $success = isset($decoded['status']) && $decoded['status'] === true;

        return [
            'success' => $success,
            'message' => $decoded['reason'] ?? ($success ? 'Pesan terkirim' : 'Gagal mengirim pesan'),
        ];
    }

    /**
     * Normalisasi nomor HP ke format internasional
     * 08xxx  => 628xxx
     * 628xxx => tetap
     */
    protected function normalizePhone(string $phone): string
    {
        // Hapus semua karakter selain angka
        $phone = preg_replace('/\D/', '', $phone);

        if (empty($phone)) return '';

        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }

        return $phone;
    }
}