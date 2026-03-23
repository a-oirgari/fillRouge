<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use App\Http\Requests\SendMessageRequest;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $conversations = Message::where('sender_id', $user->id)
            ->orWhere('receiver_id', $user->id)
            ->with(['sender', 'receiver'])
            ->latest('sent_at')
            ->get()
            ->groupBy(function ($msg) use ($user) {
                return $msg->sender_id === $user->id
                    ? $msg->receiver_id
                    : $msg->sender_id;
            })
            ->map(fn($msgs) => $msgs->first());

        return view('messages.index', compact('conversations'));
    }

    public function conversation(User $contact)
    {
        $user = Auth::user();

        $messages = Message::where(function ($q) use ($user, $contact) {
            $q->where('sender_id', $user->id)->where('receiver_id', $contact->id);
        })->orWhere(function ($q) use ($user, $contact) {
            $q->where('sender_id', $contact->id)->where('receiver_id', $user->id);
        })->orderBy('sent_at')->get();

        // Marquer comme lus
        Message::where('sender_id', $contact->id)
            ->where('receiver_id', $user->id)
            ->where('read', false)
            ->update(['read' => true]);

        return view('messages.conversation', compact('messages', 'contact'));
    }

    public function send(SendMessageRequest $request, User $contact)
    {
        // ✅ Utiliser input() ou validated() — jamais $request->content
        // car 'content' est une propriété protected dans la classe Request de base
        $message = Message::create([
            'sender_id'   => Auth::id(),
            'receiver_id' => $contact->id,
            'content'     => $request->input('content'),
            'sent_at'     => now(),
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => $message->load('sender'),
                'status'  => 'sent',
            ]);
        }

        return back();
    }

    public function getMessages(User $contact)
    {
        $user = Auth::user();

        $messages = Message::where(function ($q) use ($user, $contact) {
            $q->where('sender_id', $user->id)->where('receiver_id', $contact->id);
        })->orWhere(function ($q) use ($user, $contact) {
            $q->where('sender_id', $contact->id)->where('receiver_id', $user->id);
        })->orderBy('sent_at')
          ->with('sender')
          ->get();

        return response()->json($messages);
    }
}