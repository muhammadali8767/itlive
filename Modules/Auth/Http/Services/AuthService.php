<?php
namespace Modules\Auth\Http\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthService
{
    public function login()
    {
    }

    public static function logout()
    {
        $tokens = Auth::user()
            ->tokens
            ->pluck('id');

        DB::table('personal_access_tokens')
            ->whereIn('id', $tokens)
            ->delete();
    }
}
