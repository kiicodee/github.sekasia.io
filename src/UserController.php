<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Follow;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function follow(Request $request, string $username) {
        $follower = Auth::id();
        $following = User::where('username', $username)->first();

        if (!$following) {
            return response()->json([
                'message' => 'User not found'
            ],404);
        }

        if($follower === $following['id']) {
            return response()->json([
                'message'=> 'Not allowed to follow own user account',
            ],422);
        }

        $status = 'requested';
        $exitingFollow = Follow::where([
            'follower_id' => $follower,
            'following_id' => $following['id'],
            ])->first();

        if($exitingFollow) {
            $status = $exitingFollow->is_accepted ? 'following' : 'requested';
            return response()->json([
                'message' => 'You are already followed',
                'status' => $status
            ], 422);
        }

        $following = Follow::create([
            'follower_id' => $follower,
            'following_id' => $following['id'],
            'is_accepted' => false,
        ]);

        return response()->json([
            'message' => 'Follow success',
            'status' => 'requested'
        ],200);
    }

    public function unfollow(Request $request, string $username) {
        $follower = Auth::id();
        $following = User::where('username', $username)->first();


        if (!$following) {
            return response()->json([
                'message' => 'User not found'
            ],404);
        }

        $notFollow = Follow::where([
            'follower_id' => $follower,
            'following_id' => $following['id'],
        ])->first();
        
        if (!$notFollow) {
            return response()->json([
                'message'=> 'You are not following the user',
            ],422);
        } else {
            $notFollow->delete();
            return response()->json([
            ],204);
        }
    }

    public function following(Request $request, string $username) {
        $follower = Auth::id();
        $following = User::where('username', $username)->first();

        if (!$following) {
            return response()->json([
                'message'=> 'User not found',
            ]);
        }
        
        $followings = Follow::where('follower_id', $follower)->pluck('following_id');

        $allFollowing = User::whereIn('id', $followings)->get();

        return response()->json([
            'following'=> $allFollowing,
        ]);
        
    }

    public function accept(Request $request, string $username) {
        $follower = Auth::id();
        $following = User::where('username', $username)->first();

        if (!$following) {
            return response()->json([
                'message'=> 'User not found',
            ]);
        }

        $followings = Follow::where([
            'follower_id' => $following['id'],
            'following_id' => $follower,
        ])->first();

        if(!$followings) {
            return response()->json([
                'message'=> 'User not following you',
            ]);
        }
        if($followings->is_accepted == 1) {
            return response()->json([
                'message'=> 'Follow request already accepted',
            ]);
        }

        $followings->update(['is_accepted' => 1]);
        return response()->json([
            'message'=> 'Follow request accepted',
        ]);
    }

    public function follower(Request $request , string $username) {
        $following = User::where('username', $username)->first();
        
        if (!$following) {
            return response()->json([
                'message'=> 'User not found',
            ]);
        }

        $followers = Follow::where([
            'following_id' => $following['id'],
        ])->get('follower_id');

            
        $allfollower = User::whereIn('id', $followers)->get();
        return response()->json([
            'followers'=> $allfollower,
        ]);   

    }

    public function user(Request $request) {
        $user = Auth::user();

        return response()->json([
            'user'=> $user,
        ]);
    }

    public function userDetail(Request $request, string $username) {
        $user = Auth::user();
        $users = User::where('username', $username)->first();

        if (!$users) {
            return response()->json([
                'message'=> 'User not found',
            ]);
        }

        $followingStatus = 'not-following';
        
        if ($user->id != $users->id) {
            $isFollowing = Follow::where([
                'follower_id' => $user->id,
                'following_id' => $users->id
            ])->first();
        
            if ($isFollowing) {
                if ($isFollowing->is_accepted == 1) {
                    $followingStatus = 'following';
                } elseif ($isFollowing->is_accepted == 0) {
                    $followingStatus = 'requested';
                }
            }
        }
            
        $followers = Follow::where('following_id', $users['id'])->count();
        $following = Follow::where('follower_id' , $users['id'])->count();

        if ($user->id === $users->id || $followingStatus === 'following') {
            $posts = Post::where('user_id', $users->id)->with('post_attachments')->get();
        } else {
            $posts = [];
        }
       
        $users->is_your_account = $user->id === $users->id;
        $users->following_status = $followingStatus;
        $users->posts_count = count($posts);
        $users->followers_count = $followers;
        $users->following = $following;
        $users->posts = $posts;
         

    
        return response()->json($users,200);
    }

}
