<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /*
	 * Register new user
	*/
	public function signup(Request $request) {
        $data = $request->all();
		$validator = Validator::make($data, [
            'full_name' => 'required|string|max:100',
			'username' => 'required|string|unique:users,username|max:100',
			'email' => 'required|email|unique:users,email',
			'password' => 'required|min:6|confirmed',
			'user_type' => 'required|in:admin,customer',
		]);

        if ($validator->fails()) {
            return response()->json(['status' =>'Validation-Error', 'message'=>$validator->messages()], 422);
        }
		$data['password'] = Hash::make($data['password']);

		if($user =User::create($data)) {
            return response()->json([
                'status' => 'Success',
                'data' => $user->only(['full_name','username','user_type', 'email']),
            ], 200);
		}
        return response()->json(['status' =>'Error', 'message'=>'Failed to Create'], 404);

	}


    function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
			'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' =>'Validation-Error', 'message'=>$validator->messages()], 422);
        }
		$user = User::where('username', $request->username)->first()??null;
		if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json(['status' =>'Error', 'message'=>'The provided credentials are incorrect.'], 404);
		}

		return response()->json([
            'status' => 'Success',
			'data' => $user->only(['full_name','username','user_type', 'email']),
			'access_token' => $user->createToken($request->username)->plainTextToken
		], 200);

    }

    /*
	 * Revoke token; only remove token that is used to perform logout (i.e. will not revoke all tokens)
	*/
	public function logout(Request $request) {

		// Revoke the token that was used to authenticate the current request
		$request->user()->currentAccessToken()->delete();
		return response()->json(['status'=>'Success', 'message'=>'Logged Out Successfully!!'], 200);
	}


	/*
	 * Get authenticated user details
	*/
	public function getAuthenticatedUser(Request $request) {
        return response()->json(['status'=>'Success', 'user'=>$request->user()->only(['full_name','username','user_type', 'email'])], 200);
	}

}
