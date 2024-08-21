<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    public function loginview(Request $request) {
        return view("index");
}
    public function login(Request $request) {
        $credentials = $request->only('username', 'password');
        $url = env('APP_BACKEND_URL') . 'api/v1/auth/login';

        $response = Http::post($url,$credentials);

        if ($response->status() == 200 && $response->json() != null) {
            $data = $response->json();
            return redirect('/home')->withCookie('token', $data['token']);
        } 
        if ($response->status() == 401) {
            return redirect('/login')->with('error','Data tidak sesuai');
        }
    }
}
