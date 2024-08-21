<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReqRegister;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register (ReqRegister $request) {

        $input = $request->all();
        $input["password"] = bcrypt($input["password"]);
        $user = User::create($input);

        $token = $user->createToken("token")->plainTextToken;

        return response()->json([
            'message' =>'Register success',
            'token' => $token,
            'user' => $user,
        ],201);

    }

    public function login(Request  $request) {
        if(Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
            $user = Auth::user();

            $token = $user->createToken('token')->plainTextToken;

            return response()->json([
                'message'=> 'Login success',
                'token'=> $token,
                'user'=> $user
             ],200);
        } else {
            return response()->json([
                'message'=> 'Wrong password or username',
             ],401);
        }
    }

    public function logout(Request $request) {

        $token = $request->user()->currentAccessToken();

        if($token) {
            $token->delete();
        }

        return response()->json([
            'message'=> 'Logout success'
        ]);
    }
}
