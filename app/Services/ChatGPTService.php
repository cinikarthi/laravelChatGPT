<?php
namespace App\Services;

use GuzzleHttp\Client;

class ChatGPTService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'verify' => false,
            'base_uri' => config('services.openai.url', 'https://api.openai.com/v1/chat/completions'),
        ]);
    }

    public function sendMessage(array $messages)
    {
        try {
            $response = $this->client->post('https://api.openai.com/v1/chat/completions', [
                'headers' => [
                    'Authorization' =>  'Bearer ' . env('OPENAI_API_KEY'),
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'model'    => 'gpt-4o-mini', // or 'gpt-3.5-turbo' gpt-4
                    'messages' => $messages,
                ],
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
}
