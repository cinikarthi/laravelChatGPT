<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use OpenAI\Client as OpenAIClient;
use App\Models\ChatMessage;

class ChatGptController extends Controller
{
    private $openAI;

    public function __construct(OpenAIClient $openAI)
    {
        $this->openAI = $openAI;
    }

    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
            'conversation_id' => 'nullable|integer',
        ]);

        // Retrieve or create a conversation
        $conversationId = $request->input('conversation_id') ?? time();

        // Save user message
        ChatMessage::create([
            'conversation_id' => $conversationId,
            'role' => 'user',
            'content' => $request->input('message'),
        ]);

        // Get previous messages for context
        $messages = ChatMessage::where('conversation_id', $conversationId)
            ->orderBy('created_at')
            ->get(['role', 'content'])
            ->toArray();

        // Call OpenAI API
        $response = $this->openAI->chat()->create([
            'model' => 'gpt-4',
            'messages' => $messages,
        ]);

        $botMessage = $response['choices'][0]['message']['content'];

        // Save bot response
        ChatMessage::create([
            'conversation_id' => $conversationId,
            'role' => 'assistant',
            'content' => $botMessage,
        ]);

        return response()->json([
            'conversation_id' => $conversationId,
            'message' => $botMessage,
        ]);
    }
}
