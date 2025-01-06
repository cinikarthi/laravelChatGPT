<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use App\Services\ChatGPTService;

class ChatController extends Controller
{
    protected $chatGPTService;

    public function __construct(ChatGPTService $chatGPTService)
    {
        $this->chatGPTService = $chatGPTService;
    }

    public function sendMessage(Request $request)
    {
        $messages = $request->input('messages');

        // Validate the input
        $request->validate([
            'messages' => 'required|array',
        ]);
        $token = substr(bin2hex(random_bytes(10)), 0, 20);
        $currentDate = date('Y-m-d');
        Message::create([
            'token' => $token,
            'message' => $messages[0]['content'],
            'entry_date' => $currentDate
        ]);

        $response = $this->chatGPTService->sendMessage($messages);

        return response()->json($response);
    }

    public function chatHistory(Request $request)
    {
        $query = Message::selectRaw("GROUP_CONCAT(`message` SEPARATOR '||') as chat_messages,  DATE_FORMAT(`entry_date`, '%d/%m/%Y') as entry_date") 
            ->groupBy('entry_date')
            ->orderBy('entry_date', 'DESC')
            ->get();

            $data = [];

            foreach ($query as $val) {
                $data[] = [
                    'entry_date' => $val->entry_date,
                    'chat' => $val->chat_messages,
                ];
            }
            
            return response()->json($data);

        
    }
}
