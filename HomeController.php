<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Http;

class HomeController extends Controller
{
    public function home(Request $request) {
        $token = Cookie::get("token");

        $userResponse = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->get(env('APP_BACKEND_URL') . 'api/v1/users');

        $postsResponse = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->get(env('APP_BACKEND_URL') . 'api/v1/posts?page=0&size=5');

        // $postsResponse = Http::withHeaders([
        //     'Authorization' => 'Bearer ' . $token,
        //     'Accept' => 'application/json',
        // ])->get(env('APP_BACKEND_URL') . 'api/v1/posts?page=0&size=5');
        
        
        $userData = $userResponse->json();
        $postsData = $postsResponse->json();
        if ($userData) {
            return view('home')
            ->with('user', $userData)
            ->with('posts',$postsData);
        }
         else {
            return redirect('/');
        }


        }

}
