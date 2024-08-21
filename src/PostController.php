<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReqPost;
use App\Http\Requests\ReqPostsShow;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function store(ReqPost $request) {

        
                $post = Post::create([
                    'caption' => $request->input('caption'),
                    'user_id' => Auth::id(),
                ]);
    
                $paths = []; 
    
                foreach ($request->file('attachments') as $index => $attachment) {
                    $filename = $attachment->store('posts', 'public');
                    $postAttachment = $post->post_attachment()->create(['storage_path' => $filename]);
                    $paths[] = $postAttachment->storage_path;
                }
    
                return response()->json(['message' => 'Create post success',], 201);
            }

            public function delete(Request $request, string $id) {
            $post = Post::find($id);

            if(!$post) {
                return response()->json(['message'=> 'Post not found',],404);
            }

            if($post->user_id == Auth::id()) { 
                $post->delete();

                return response()->json([],204);
            } else {
                return response()->json(['message'=> 'Forbidden access',],403);
            }
        
         }

         public function show(ReqPostsShow $request){
            $page = $request->input('page', 0);
            $size = $request->input('size', 10);

            $page = max(0, $page);
            $size = max(0, $size);

            $posts = Post::with('user', 'post_attachments')
            ->orderBy('created_at','desc')
            ->take($size)
            ->skip($page * $size)
            ->get();

        return response()->json([
            'page' => $page,
            'size' => $size,
            'posts' => $posts,
        ], 200);

     }

}
