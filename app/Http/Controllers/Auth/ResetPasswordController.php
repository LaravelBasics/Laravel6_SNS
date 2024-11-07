<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class ResetPasswordController extends Controller
{
    // public $token;
    // public $email;
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    // パスワード再設定画面の表示
    public function showResetForm(Request $request)
{
    $token = $request->route('token');
    // $email = $request->email;
    $email = $request->email;// email を正しく取得する
    // $email = $request->input('email');

    // $email = $request->route('email'); 
    // log::debug('email ='. $email);
    log::debug('Request data: ' . print_r($request->all(), true));

    return view('auth.passwords.reset', compact('token', 'email'));
}


    // パスワード再設定の処理
    public function update(PasswordUpdateRequest $request)
    {
        $request->validate([
            'password' => 'required|string|confirmed|min:8',
        ]);

        // パスワードの再設定ロジックを記述する
        // ...
    }
}
