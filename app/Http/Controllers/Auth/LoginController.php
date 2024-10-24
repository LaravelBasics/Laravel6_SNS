<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\User;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    // protected $maxAttempts = 5;
    // protected $decayMinutes = 1;

    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }


    public function redirectToProvider(string $provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback(Request $request, string $provider)
{
    // コールバックのログ出力
    Log::info('Google callback URL hit');

    // プロバイダーからのユーザー情報の取得
    try {
        $providerUser = Socialite::driver($provider)->stateless()->user();
    } catch (\Exception $e) {
        Log::error('Error fetching provider user', ['error' => $e->getMessage()]);
        return redirect()->route('login');
    }

    Log::info('Google callback received', [
        'provider_user' => $providerUser,
        'request_data' => $request->all()
    ]);

    $user = User::where('email', $providerUser->getEmail())->first();

    if ($user) {
        $this->guard()->login($user, true);
        return $this->sendLoginResponse($request);
    }
    
    // ユーザーが見つからない場合の処理
    Log::info('User not found for email', ['email' => $providerUser->getEmail()]);
    return redirect()->route('register.{provider}', [
        'provider' => $provider,
        'email' => $providerUser->getEmail(),
        'token' => $providerUser->token,
    ]);
}

    // public function login(Request $request)
    // {
    //     /**
    //      * 1. バリデーション(形式のチェック)
    //     **/
    //     $this->validateLogin($request);

    //     /**
    //      * 2. ログイン試行回数を超過していればロックアウトをレスポンス
    //     **/
    //     // If the class is using the ThrottlesLogins trait, we can automatically throttle
    //     // the login attempts for this application. We'll key this by the username and
    //     // the IP address of the client making these requests into this application.
    //     if (method_exists($this, 'hasTooManyLoginAttempts') &&
    //         $this->hasTooManyLoginAttempts($request)) {
    //         $this->fireLockoutEvent($request);

    //         return $this->sendLockoutResponse($request);
    //     }

    //     /**
    //      * 3. ログイン試行して認証OKであればログイン成功をレスポンス(トップページにリダイレクト)
    //     **/
    //     if ($this->attemptLogin($request)) {
    //         return $this->sendLoginResponse($request);
    //     }

    //     /**
    //      * 4. 認証NGであればログイン試行回数を1増やしてログイン画面をレスポンス
    //     **/
    //     // If the login attempt was unsuccessful we will increment the number of attempts
    //     // to login and redirect the user back to the login form. Of course, when this
    //     // user surpasses their maximum number of attempts they will get locked out.
    //     $this->incrementLoginAttempts($request);

    //     return $this->sendFailedLoginResponse($request);
    // }
}
