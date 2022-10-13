<?php

namespace Modules\Auth\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Auth\Http\Requests\LoginRequest;
use Modules\Auth\Http\Requests\RegisterRequest;
use Modules\Auth\Http\Services\AuthService;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AuthController extends Controller
{
    /**
     * @OA\Post(
     *      path="/api/register",
     *      operationId="storeUser",
     *      tags={"Auth"},
     *      summary="Store new user",
     *      description="Returns user data",
     *
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                      @OA\Property(
     *                          property="name",
     *                          type = "string",
     *                          description="Name"
     *                      ),
     *                      @OA\Property(
     *                          property="email",
     *                          type = "string",
     *                          description="E-mail"
     *                      ),
     *                      @OA\Property(
     *                          property="password",
     *                          type = "string",
     *                          description="Password"
     *                      ),
     *              )
     *          )
     *      ),
     *
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation"
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     * )
     */

    public function register(RegisterRequest $request)
    {
        $roleUser = Role::firstOrCreate(['name' => 'user']);
        $roleAdmin = Role::firstOrCreate(['name' => 'admin']);

        $user = User::create([
            "name" => $request->name,
            "email" => $request->email,
            "password" => Hash::make($request->password)
        ]);

        $role = (User::count() > 1) ? $roleUser : $roleAdmin;
        $user->assignRole($role);

        $token = $user->createToken('auth_token')->plainTextToken;
        return $this->success([
            'user' => $user,
            'token' => $token,
        ]);
    }

    /**
     * @OA\Post(
     * path="/api/login",
     *   tags={"Auth"},
     *   summary="Login",
     *   operationId="login",
     *
     *   @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                      @OA\Property(
     *                      property="email",
     *                      type = "string"
     *                  ),
     *                      @OA\Property(
     *                      property="password",
     *                      type = "string",
     *                  ),
     *          )
     *      )
     * ),
     *
     *   @OA\Response(
     *      response=200,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *)
     **/

    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            return $this->success([
                'token' => Auth::user()->createToken('auth_token')->plainTextToken,
                'name' => Auth::user()->name,
            ]);
        } else {
            return $this->error('Unauthorized', 403);
        }
    }

    /**
     * @OA
     */
    public function logout()
    {
        AuthService::logout();

        return $this->success([
            'logout' => 'ok',
        ]);
    }
}
