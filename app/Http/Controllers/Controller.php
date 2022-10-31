<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="ITLive.uz",
 *      description="ITLive.uz OpenApi",
 *      @OA\Contact(
 *          email="muhammadali8767@gmail.com"
 *      ),
 *     @OA\License(
 *         name="Apache 2.0",
 *         url="https://www.apache.org/licenses/LICENSE-2.0.html"
 *     )
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function success($responce)
    {
        return response()->json(['success' => true,'code' => 201, 'responce' => $responce]);
    }

    public function error($message, $code = 404)
    {
        return response()->json(['success' => false,'code' => $code, 'message' => $message], $code);
    }
}
