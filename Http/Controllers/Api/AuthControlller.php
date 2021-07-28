<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthControlller extends Controller
{
    public function login(Request $request )
    {
        $creds = $request->only(['email','password']);
        if(!$token=auth()->attempt($creds))
        {
            return response()->json([
             'success' => false,
             'message' => 'Invalid login credentials'
             

            ]);
        }
        return response()->json([
            'success' => true,
            'token' => $token,
            'user' => Auth::user(),

        ]);

    }
    public function reg(Request $request)
    {
        $encryptedpass = Hash::make($request ->password);
        $user = new User();
        try{
          $user->name = $request->name;
          $user->email = $request->email;
          $user->password = $encryptedpass; 
          $user ->save();
          return $this -> login ($request);
        }catch (Exception $e)
        {
            return response()->json([
                'success' => false,
                'message' => $e
            ]);

        }
    }
    public function logout(Request $request)
    {

        try{
            JWTAuth::Invalidate(JWTAuth::parseToken($request->token));
            return response()->json{[
                'success' => true,
                'message'=>'Logout success'
            ]};
        }
        catch(Exception $e)
        {
            return response()->json{[
                'success' => false,
                'message'=>$e
            ]};
        }
    }
}
