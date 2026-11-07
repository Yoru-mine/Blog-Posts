<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ImgbbService
{
    private const ENDPOINT = 'https://api.imgbb.com/1/upload';

    public function upload($file): ?string
    {
        if (! $file) {
            return null;
        }

        try {
            $response = Http::timeout(10)
                ->connectTimeout(5)
                ->asMultipart()
                ->post(self::ENDPOINT, [
                    'key' => config('services.imgbb.key'),
                    'image' => base64_encode(file_get_contents($file->getRealPath())),
                ]);

            if ($response->failed() || ! isset($response->json()['data']['url'])) {
                throw new \Exception('ImgBB API returned error: ' . $response->body());
            }

            return $response->json()['data']['url'];
        } catch (\Exception $e) {
            Log::error('ImgBB upload error: ' . $e->getMessage());
            throw $e;
        }
    }

    public function delete(?string $url): void
    {
        if (! $url) {
            return;
        }

        Log::info("Картинка {$url} должна быть удалена (требуется delete_url).");
    }
}
