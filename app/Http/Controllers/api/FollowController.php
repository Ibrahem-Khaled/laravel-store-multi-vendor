<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class FollowController extends Controller
{

    public function follow(Request $request, User $user)
    {
        $follower = $request->user();

        if ($follower->id === $user->id) {
            return response()->json(['message' => 'You cannot follow yourself.'], 422);
        }

        // Attach the relationship
        $follower->following()->syncWithoutDetaching($user->id);

        return response()->json([
            'message' => 'Successfully followed the user.',
            'user' => new UserResource($user)
        ]);
    }

    /**
     * Unfollow a user.
     */
    public function unfollow(Request $request, User $user)
    {
        $follower = $request->user();

        // Detach the relationship
        $follower->following()->detach($user->id);

        return response()->json([
            'message' => 'Successfully unfollowed the user.',
            'user' => new UserResource($user)
        ]);
    }

    /**
     * Get the list of users that a specific user is following.
     */
    public function following(User $user)
    {
        $following = $user->following()->paginate(15);
        return UserResource::collection($following);
    }

    /**
     * Get the list of users who follow a specific user.
     */
    public function followers(User $user)
    {
        $followers = $user->followers()->paginate(15);
        return UserResource::collection($followers);
    }
}
