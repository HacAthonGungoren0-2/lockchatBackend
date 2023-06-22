<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Friendship;
use App\Http\Controllers\API\BaseController as BaseController;

class UserController extends BaseController
{
    public function searchUsers(Request $request)
    {
        $searchTerm = $request->input('term');
        if (!$searchTerm) {
            return response()->json([
                $request->input('term'),
                'status' => 'error',
                'message' => 'Please enter a search term.',
            ], 422);
        }
        
        $users = User::where('name', 'like', '%' . $searchTerm . '%')->get();

        if ($users->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'No users found.',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Users found successfully.',
            'users' => $users,
        ], 200);
    }

/*     public function addFriend(Request $request)
    {
        $userId = $request->input('user_id');
        $friendId = $request->input('friend_id');

        // Öncelikle, kullanıcıların mevcut olduğunu doğrulayın
        $user = User::find($userId);
        $friend = User::find($friendId);

        if (!$user || !$friend) {
            return response()->json([
                'status' => 'error',
                'message' => 'One or both users not found.',
            ], 404);
        }

        // Arkadaşlık ilişkisini kontrol edin
        $existingFriendship = Friendship::where('user_id', $userId)
            ->where('friend_id', $friendId)
            ->first();

        if ($existingFriendship) {
            return response()->json([
                'status' => 'error',
                'message' => 'Friendship already exists.',
            ], 400);
        }

        // Arkadaşlık ilişkisini oluşturun
        Friendship::create([
            'user_id' => $userId,
            'friend_id' => $friendId,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Friend added successfully.',
        ], 200);
    } */

    public function addFriend(Request $request)
{
    $userId = $request->input('user_id');
    $friendId = $request->input('friend_id');

    // Öncelikle, kullanıcıların mevcut olduğunu doğrulayın
    $user = User::find($userId);
    $friend = User::find($friendId);

    if (!$user || !$friend) {
        return response()->json([
            'status' => 'error',
            'message' => 'One or both users not found.',
        ], 404);
    }

    // Arkadaşlık ilişkisini kontrol edin
    $existingFriendship = Friendship::where('user_id', $userId)
        ->where('friend_id', $friendId)
        ->orWhere(function ($query) use ($userId, $friendId) {
            $query->where('user_id', $friendId)
                ->where('friend_id', $userId);
        })
        ->first();

    if ($existingFriendship) {
        return response()->json([
            'status' => 'error',
            'message' => 'Friendship already exists.',
        ], 400);
    }

    // Arkadaşlık ilişkisini oluşturun
    Friendship::create([
        'user_id' => $userId,
        'friend_id' => $friendId,
    ]);

    Friendship::create([
        'user_id' => $friendId,
        'friend_id' => $userId,
    ]);

    return response()->json([
        'status' => 'success',
        'message' => 'Friend added successfully.',
    ], 200);
}




    public function getFriends($userId)
    {
        $friends = Friendship::where('user_id', $userId)->pluck('friend_id')->toArray();
    
        $friendUsers = User::whereIn('id', $friends)->get();
    
        return response()->json([
            'status' => 'success',
            'message' => 'User\'s friends retrieved successfully.',
            'friends' => $friendUsers,
        ], 200);
    }
    

}
