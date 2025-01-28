<?php
namespace App\Http\Controllers\Api\V1\Suppliers;

use App\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        // attempt to log in user
        if (!Auth::guard('supplier')->attempt($request->only('email', 'password'))) {
            return ApiResponseClass::sendResponse(null, trans('auth.failed'), false, 404);
        }
        // Get the authenticated user
        $user = Auth::guard('supplier')->user();
        // Create an API token for the user
        $token = $user->createToken('Suppliers API Token')->plainTextToken;

        return ApiResponseClass::sendResponse($token);
    }

    public function logout(Request $request){
        // delete api token for current user
        $request->user()->tokens()->delete();
        // return with success
        return ApiResponseClass::sendResponse(null, trans('auth.logged out'));
    }
}
