<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\Message;
use Faker\Provider\Base;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\User;
use App\Models\Friendship;
use Illuminate\Support\Facades\Crypt;

class MessageController extends BaseController
{
    public function store(Request $request)
    {
        $senderId = $request->input('sender_id');
        $receiverId = $request->input('receiver_id');
        $messageText = $request->input('message');

        // Kullanıcılar arasında arkadaşlık ilişkisi kontrolü yapın
        $friendshipExists = Friendship::where('user_id', $senderId)
            ->where('friend_id', $receiverId)
            ->first();

        if (!$friendshipExists) {
            return response()->json([
                'status' => 'error',
                'message' => 'Users are not friends.',
            ], 400);
        }

        // Mesajı şifreleyin
        $encryptedMessage = Crypt::encryptString($messageText);

        $message = Message::create([
            'sender_id' => $senderId,
            'receiver_id' => $receiverId,
            'message' => $encryptedMessage,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Message sent successfully.',
            'data' => $message,
        ], 200);
    }

    public function getMessagesBetweenUsers($senderId, $receiverId)
    {
        $messages = Message::where(function ($query) use ($senderId, $receiverId) {
            $query->where('sender_id', $senderId)
                ->where('receiver_id', $receiverId);
        })->orWhere(function ($query) use ($senderId, $receiverId) {
            $query->where('sender_id', $receiverId)
                ->where('receiver_id', $senderId);
        })->get();

        // Şifreli mesajları çözme
        $decryptedMessages = $messages->map(function ($message) {
            try {
                $decryptedMessage = Crypt::decryptString($message->message);
                $message->message = $decryptedMessage;
            } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
                // Çözme hatası oluştu, mesajı boş bırak
                $message->message = '';
            }
            return $message;
        });

        return response()->json([
            'status' => 'success',
            'message' => 'Messages retrieved successfully.',
            'data' => $decryptedMessages,
        ], 200);
    }

}
