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
			]);
		}
	}

	public function logout()
	{
		auth()->logout();

		return response()->json(['message' => __('Logout') . ' ' . __('Success')]);
	}
	// public function login(Request $request)
	// {
	// 	$username = $request->username;
	// 	$password = $request->password;
	// 	if(Auth::attempt(['username' => $username, 'password' => $password]))
	// 	{
	// 		Auth::user()->update([
	// 			'api_token' => Hash::make(Str::random(60)),
	// 		]);

	// 		return response()->json([
	// 			'success' => true,
	// 			'data' => ['api_token' => Auth::user()->api_token]
	// 		],200);
	// 	}
	// 	else
	// 	{
	// 		return response()->json([
	// 			'success' => false,
	// 			'data' => ['message' => __('username or password not correct')]
	// 		],400);
	// 	}
	// }	

	// public function logout(Request $request)
	// {
	// 	// check token
	// 	$token = $request->api_token;
	// 	$user = User::where('IsDelete', 0)
	// 	->where('api_token', $token)
	// 	->update([
	// 		'api_token' => '',
	// 	]);
	// 	if ($user) {
	// 		return response()->json([
	// 			'success' => true,
	// 			'data' => ['message'=>__('Logout').' '.__('Success')]
	// 		],200);
	// 	}
	// 	else
	// 	{
	// 		return response()->json([
	// 			'success' => false,
	// 			'data' => ['message'=>__('Logout').' '.__('Fail')]
	// 		],400);
	// 	}
	// }
}
