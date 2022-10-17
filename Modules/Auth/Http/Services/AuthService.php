<?php
namespace Modules\Auth\Http\Services;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AuthService
{
    public function register(Request $request)
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
        return [
            'user' => $user,
            'token' => $token,
        ];
    }
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            return [
                'token' => Auth::user()->createToken('auth_token')->plainTextToken,
                'name' => Auth::user()->name,
            ];
        } else {
            return false;
        }
    }

    public static function logout()
    {
        $tokens = Auth::user()
            ->tokens
            ->pluck('id');

        DB::table('personal_access_tokens')
            ->whereIn('id', $tokens)
            ->delete();

        return ['logout' => 'ok'];
    }
}
