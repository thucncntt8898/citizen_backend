<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    /**
     * @param LoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {
        $input = $request->only('username', 'password');

        if (!$token = JWTAuth::attempt($input)) {
            return response()->json([
                'success' => false,
                'message' => 'Sai tên đăng nhập hoặc mật khẩu!',
            ], 401);
        }

        return response()->json([
            'success' => true,
            'data' => $this->createNewToken($token)->original,
        ]);
    }

    /**
     * @param $token
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token){
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user()
        ]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh() {
        return $this->createNewToken(auth()->refresh());
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        try {
            auth()->logout();

            return response()->json([
                'success' => true,
                'message' => 'Đăng xuất thành công!'
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Đã có lỗi xảy ra!'
            ], 500);
        }
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUser()
    {
        try {
            return response()->json([
                'success' => true,
                'data' => User::with(['province', 'district', 'ward', 'hamlet'])->where('id', Auth::user()->id)->get()
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Đã có lỗi xảy ra!'
            ], 500);
        }
    }
}
