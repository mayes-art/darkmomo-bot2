<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ChatGPTService
{
    const CHAT_API_ENDPOINT = '/chat/completions';

    private $apiUrl;

    public function __construct()
    {
        $this->apiUrl = env('OPEN_AI_API_URL', null) . self::CHAT_API_ENDPOINT;
    }

    public function answer(string $context)
    {
//        $context = '你會講中文嗎?';

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('OPEN_AI_API_KEY'),
            'Content-Type' => 'application/json'
        ])->post($this->apiUrl, [
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                [
                    'role' => 'user',
                    'content' => $context,
                ]
            ],
        ]);

        $json = $response->json();

        return $json['choices'][0]['message']['content'];
    }
}
