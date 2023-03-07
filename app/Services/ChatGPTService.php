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

        return trim($json['choices'][0]['message']['content']);
    }

    public function textDavinci(string $context)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('OPEN_AI_API_KEY'),
            'Content-Type' => 'application/json'
        ])->post('https://api.openai.com/v1/completions', [
            "model"             => 'text-davinci-003',
            "prompt"            => "\n\nHuman: {$context} \nAI:",
            "temperature"       => 0.9,
            "max_tokens"        => 150,
            "top_p"             => 1,
            "frequency_penalty" => 0.0,
            "presence_penalty"  => 0.6,
            "stop"              => [" Human:", " AI:"],
        ]);

        $json = $response->json();

        return trim($json['choices'][0]['text']);
    }
}
