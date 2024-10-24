<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Lang;
use App\Mail\BareMail;

class PasswordResetNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $token;
    public $mail;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(string $token, BareMail $mail) //==========この行を変更
    {
        //         //==========ここから追加==========
        $this->token = $token;
        $this->mail = $mail;
        //         //==========ここまで追加==========
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $url = URL::temporarySignedRoute(
            'password.reset',// パスワードリセットのルート名
            Carbon::now()->addMinutes(config('auth.passwords.users.expire')),//// 有効期限
            [
                'token' => $this->token,// 通知に渡されたトークン
                'email' => $notifiable->email// 通知が送信されるユーザーのメールアドレス
            ]
        );

        return (new MailMessage)
            ->subject(Lang::get('パスワード再設定のお知らせ'))
            ->line(Lang::get('下記のURLからパスワードの再設定を行って下さい。'))
            ->action(Lang::get('パスワード再設定'), $url)
            ->line(Lang::get('このURLの有効期間は :count 分です。', ['count' => config('auth.passwords.users.expire')]))
            ->line(Lang::get('このメールに心あたりがない場合は、第三者がメールアドレスの入力を誤った可能性があります。その場合は、このメールは破棄していただいて結構です。(´;ω;`)'))
            ->line(Lang::get('もし「パスワード再設定」ボタンをクリックできない場合は、以下のURLをコピーしてブラウザに貼り付けてください:'))
            /*->line($url)*/
            ->salutation(Lang::get('宜しくお願いします。'));
    }
    // vendor/laravel/フレームワーク/src/resources/views/notifications/email.blade.php
    // この部分が英語表示actiontextが日本語、
    // <!-- <span class="break-all">[{{ $displayableActionUrl }}]({{ $actionUrl }})</span> -->
    // がリンク
    // lang(
    //     -    "If you’re having trouble clicking the \":actionText\" button, copy and paste the URL below\n".
    //     -   'into your web browser: ',
    //     -  [
    //     -       'actionText' => $actionText
    //     -   ]
    //     - )


    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}


// <?php

// namespace App\Notifications;

// //==========ここから追加==========
// use App\Mail\BareMail;
// //==========ここまで追加==========
// use Illuminate\Bus\Queueable;
// use Illuminate\Contracts\Queue\ShouldQueue;
// use Illuminate\Notifications\Messages\MailMessage;
// use Illuminate\Notifications\Notification;

// class PasswordResetNotification extends Notification
// {
//     use Queueable;

//     //==========ここから追加==========
//     public $token;
//     public $mail;
//     //==========ここまで追加==========

//     /**
//      * Create a new notification instance.
//      *
//      * @return void
//      */
//     public function __construct(string $token, BareMail $mail) //==========この行を変更
//     {
//         //==========ここから追加==========
//         $this->token = $token;
//         $this->mail = $mail;
//         //==========ここまで追加==========
//     }

//     /**
//      * Get the notification's delivery channels.
//      *
//      * @param  mixed  $notifiable
//      * @return array
//      */
//     public function via($notifiable)
//     {
//         return ['mail'];
//     }

//     /**
//      * Get the mail representation of the notification.
//      *
//      * @param  mixed  $notifiable
//      * @return \Illuminate\Notifications\Messages\MailMessage
//      */
//     public function toMail($notifiable)
//     {
//         //==========ここから削除==========
//         return (new MailMessage)
//                     ->line('The introduction to the notification.')
//                     ->action('Notification Action', url('/'))
//                     ->line('Thank you for using our application!');
//         //==========ここまで削除==========
//         //==========ここから追加==========
        // return $this->mail
        //     ->from(config('mail.from.address'), config('mail.from.name'))
        //     ->to($notifiable->email)
        //     ->subject('[memo]パスワード再設定')
        //     ->text('emails.password_reset')
        //     ->with([
        //         'url' => route('password.reset', [
        //             'token' => $this->token,
        //             'email' => $notifiable->email,
        //         ]),
        //         'count' => config(
        //             'auth.passwords.' .
        //             config('auth.defaults.passwords') .
        //             '.expire'
        //         ),
        //     ]);
//         //==========ここまで追加==========
//     }
//     // 略
// }
