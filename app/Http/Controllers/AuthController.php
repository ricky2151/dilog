<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\LoginUser;
use App\Http\Requests\StoreUser;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('jwt.auth', ['except' => ['login','register']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginUser $request)
    {
        $credentials = $request->validated();
        if (! $token = auth('api')->attempt($credentials)) {
            return response()->json(['authenticate' => false, 'message' => ["authentication" => ['Invalid Email/Password']]], 401);
        }
        return $this->respondWithToken($token);
    }


    /**
     * Register new User and get a JWT via new user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(StoreUser $request){
        
        $data = $request->validated();
        $data["password"] = bcrypt($data["password"]);

        $user = new User;
        $user = $user->create($data);
        $token = auth('api')->fromUser($user);

        return response()->json([
            'error' => false,
            'access_token' => $token,
            'user' => $user
        ]);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth('api')->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth('api')->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function payload(){
        return auth('api')->payload();
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth('api')->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'authenticate' => true,
            'access_token' => $token,
            'user' => auth('api')->user()
        ]);
    }
}