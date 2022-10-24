<?php

namespace Modules\Auth\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Auth\Http\Requests\LoginRequest;
use Modules\Auth\Http\Requests\RegisterRequest;
use Modules\Auth\Http\Services\AuthService;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * @OA\Post(
     *      path="/api/auth/register",
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
        $responce = AuthService::register($request);

        return $this->success($responce);
    }

    /**
     * @OA\Post(
     * path="/api/auth/login",
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
     */
    public function login(LoginRequest $request)
    {
        $responce = AuthService::login($request);

        if ($responce)
            return $this->success($responce);
        else
            return $this->error('Unauthorized', 403);
    }

    /**
     * @OA\Post(
     * path="/api/auth/logout",
     *   tags={"Auth"},
     *   summary="Logout",
     *   operationId="logout",
     *
     *   @OA\Response(
     *      response=201,
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
     */
    public function logout()
    {
        $responce = AuthService::logout();

        return $this->success($responce);
    }
}
