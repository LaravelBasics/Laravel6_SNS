<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class ArticleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|max:50',
            'body' => 'required|max:500',
            'tags' => 'nullable|json|regex:/^(?!.*\s).+$/u|regex:/^(?!.*\/).*$/u',
        ];
    }

    public function attributes()
    {
        return [
            'title' => 'タイトル',
            'body' => '本文',
            'tags' => 'タグ',
        ];
    }

    public function passedValidation()
    {
        Log::info("受け取ったタグ:", ['tags' => $this->tags]);

        // デコードされたタグを確認
        $decodedTags = json_decode($this->tags);
        Log::info("デコードされたタグ:", ['decodedTags' => $decodedTags]);
    
        if (is_array($decodedTags)) {
            $this->tags = collect($decodedTags)
                ->slice(0, 5)
                ->map(function ($requestTag) {
                    // $requestTag がオブジェクトであるかチェック
                    return is_object($requestTag) ? $requestTag->text : $requestTag;
                });
        } else {
            // Log::error("タグデータが配列ではありません。");
            Log::error("タグデータが配列ではありません。", ['tags' => $this->tags]);
        // デフォルト値を設定するなど、必要に応じて処理を追加
        $this->tags = collect(); // 空のコレクションにする
        }
    }
}
