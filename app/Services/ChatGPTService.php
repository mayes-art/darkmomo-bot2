<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ChatGPTService
{
    const CHAT_API_ENDPOINT = '/chat/completions';

    private $apiUrl;

    private $conversation;

    public function __construct()
    {
        $this->apiUrl = env('OPEN_AI_API_URL', null) . self::CHAT_API_ENDPOINT;
        $this->conversation = '';
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

    public function setDavinciSay(string $text)
    {
        $this->conversation .= "\n\nHuman: {$text} \nAI:";
    }

    /**
     * @response_param finish_reason (length: 還有下文/ stop: 停止)
     */
    public function textDavinci()
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('OPEN_AI_API_KEY'),
            'Content-Type' => 'application/json'
        ])->post('https://api.openai.com/v1/completions', [
            "model"             => 'text-davinci-003',
            "prompt"            => $this->conversation,
            "temperature"       => 0.9,
            "max_tokens"        => 150,
            "top_p"             => 1,
            "frequency_penalty" => 0.0,
            "presence_penalty"  => 0.6,
            "stop"              => [" Human:", " AI:"],
        ]);

        $json = $response->json();
        $finishReason = trim($json['choices'][0]['finish_reason']);
        $context = trim($json['choices'][0]['text']);

        if ('length' == $finishReason) {
            $this->conversation .= "{$context} \n\nHuman: 繼續 \nAI:";
        }

        return [
            'status'  => $finishReason,
            'context' => $context,
        ];
    }
}
