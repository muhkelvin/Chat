<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function index()
    {
        return view('chat');
    }

    // Metode untuk mendapatkan pesan dalam format JSON
    public function getMessages()
    {
        $messages = Message::with('user')->get();
        return response()->json($messages);
    }

    // Metode untuk menyimpan pesan
    public function store(Request $request)
    {
        $message = new Message();
        $message->user_id = Auth::id();
        $message->message = $request->message;
        $message->save();

        return response()->json($message);
    }
}
