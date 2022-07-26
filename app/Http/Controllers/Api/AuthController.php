<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth:api', ['except' => ['login']]);
	}

	public function login(Request $request)
	{
		$credentials = $request->only('username', 'password');
		if (Auth::attempt($credentials)) {
			try {
				if (!$token = JWTAuth::attempt($credentials)) {
					return response()->json(['error' => 'invalid_credentials'], 400);
				}
			} catch (JWTException $e) {
				return response()->json(['error' => 'could_not_create_token'], 500);
			}

			return response()->json([
				'user' => Auth::user(),
				'token' => $token
			], 200);
		} else {
			return response()->json(['error' => __('Login') . ' ' . __('Fail')], 400);
		}
	}

	public function logout()
	{
		auth()->logout();

		return response()->json(['message' => __('Logout') . ' ' . __('Success')]);
	}
}
