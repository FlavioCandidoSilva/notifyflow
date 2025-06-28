<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class WhatsAppService
{
    protected $wppSecret;
    protected $wppUrl;
    protected $wppSesion;

    public function __construct()
    {
        $this->wppSecret = env('WPP_SECRET');
        $this->wppUrl = env('WPP_BASE_URL');
        $this->wppSesion = env('WPP_SESSION');
    }

    public function sendMessage(string $phone, string $message): bool|string
    {
        $url = "{$this->wppUrl}/api/{$this->wppSesion}/send-message";
        $response = Http::post($url, compact('phone', 'message'));

        return $response->successful()
            ? true
            : $response->body();
    }

    public function authenticate(): string
    {

        $formatUrl = $this->wppUrl . "/api/{$this->wppSesion}/{$this->wppSecret}/generate-token";

        $response = Http::withHeaders([
            "accept" => "*/*",
        ])->post($formatUrl);

        if($response->successful()){
            return $response->json('token');
        }else{
            throw new \Exception("Failed to authenticate with WhatsApp API: " . $response->body());
        }
    }
}