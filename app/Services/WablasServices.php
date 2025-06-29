<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WablasServices
{
    protected $token;
    protected $url;

    public function __construct()
    {
        $this->token = env('WABLAS_API_TOKEN');
        $this->url = env('WABLAS_API_URL', 'https://sby.wablas.com/api/v2/send-message');
    }

    public function kirimPesan($nomor, $pesan)
    {
        $response = Http::withHeaders([
            'Authorization' => $this->token,
            'Accept' => 'application/json',
        ])->post($this->url, [
            'data' => [
                [
                    'phone' => $nomor,
                    'message' => $pesan,
                ]
            ]
        ]);

        if (!$response->successful()) {
            throw new \Exception("Wablas error: " . $response->body());
        }

        return $response->json();
    }
}
