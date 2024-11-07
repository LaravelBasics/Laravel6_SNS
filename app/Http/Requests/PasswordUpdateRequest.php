<?php
// app/Http/Requests/PasswordUpdateRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class PasswordUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true; // 通常は認可ロジックを記述しますが、ここでは簡略化しています
    }

    public function rules()
    {
        Log::info("aaaaaaaaaccccccc");
        return [
            'email' => 'required|email', // メールアドレスの必須と形式チェック
            'password' => 'required|min:8|confirmed', // パスワードの必須、最小8文字、確認フィールドとの一致確認
        ];
    }
}
