<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use App\Events\MessageSent;
use App\Http\Requests\SendMessageRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\CallInitiated;
use App\Models\Appointment;

class MessageController extends Controller
{
    public function unreadCount()
    {
        $count = Message::where('receiver_id', Auth::id())
            ->where('read', false)
            ->count();

        return response()->json(['count' => $count]);
    }

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
        })->orderBy('sent_at')->with('sender')->get();

        
        Message::where('sender_id', $contact->id)
            ->where('receiver_id', $user->id)
            ->where('read', false)
            ->update(['read' => true]);

        $lastMessageFromContact = Message::where('sender_id', $contact->id)
            ->where('receiver_id', $user->id)
            ->latest('sent_at')
            ->first();

        return view('messages.conversation', compact('messages', 'contact', 'lastMessageFromContact'));
    }

    public function videoCall(Request $request, User $contact)
    {
        $user = Auth::user();
        
        $ids = [$user->id, $contact->id];
        sort($ids);
        $roomID = 'chat_' . $ids[0] . '_' . $ids[1];

        
        if (!$request->has('join')) {
            broadcast(new CallInitiated($user, $contact->id));
        }

        
        $appointment = null;
        if ($user->isDoctor()) {
            $appointment = Appointment::where('doctor_id', $user->doctor->id)
                ->where('patient_id', $contact->patient->id ?? 0)
                ->whereIn('status', ['accepted', 'pending'])
                ->whereDate('date', today())
                ->first();
        }

        return view('messages.call', compact('contact', 'roomID', 'appointment'));
    }

    public function send(SendMessageRequest $request, User $contact)
    {
        $message = Message::create([
            'sender_id'   => Auth::id(),
            'receiver_id' => $contact->id,
            'content'     => $request->input('content'),
            'sent_at'     => now(),
        ]);

        $message->load('sender');

        
        broadcast(new MessageSent($message));

        if ($request->expectsJson()) {
            return response()->json([
                'message' => $message,
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