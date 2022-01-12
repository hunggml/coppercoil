<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Libraries\WarehouseSystem\ImportLibraries;
use App\Libraries\MasterData\MasterMaterialsLibraries;
use App\Libraries\MasterData\MasterWarehouseDetailLibraries;
use App\Libraries\MasterData\MasterWarehouseLibraries;
use App\Models\WarehouseSystem\CommandImport;
use App\Models\WarehouseSystem\ImportDetail;
use App\Models\MasterData\MasterWarehouseDetail;
use App\Models\MasterData\MasterMaterials;
use App\Models\User;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;

class AuthController extends Controller
{

	public function login(Request $request)
	{
		$username = $request->username;
		$password = $request->password;
		if(Auth::attempt(['username' => $username, 'password' => $password]))
		{
			Auth::user()->update([
				'api_token' => Hash::make(Str::random(60)),
			]);

			return response()->json([
				'success' => true,
				'data' => ['api_token' => Auth::user()->api_token]
			],200);
		}
		else
		{
			return response()->json([
				'success' => false,
				'data' => ['message' => __('username or password not correct')]
			],400);
		}
	}	

	public function logout(Request $request)
	{
		// check token
		$token = $request->api_token;
		$user = User::where('IsDelete', 0)
		->where('api_token', $token)
		->update([
			'api_token' => '',
		]);
		if ($user) {
			return response()->json([
				'success' => true,
				'data' => ['message'=>__('Logout').' '.__('Success')]
			],200);
		}
		else
		{
			return response()->json([
				'success' => false,
				'data' => ['message'=>__('Logout').' '.__('Fail')]
			],400);
		}
	}
}
