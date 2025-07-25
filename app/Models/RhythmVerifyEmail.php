<?php

namespace App\Models;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class RhythmVerifyEmail extends VerifyEmail
{

    public function toMail($notifiable)
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject('请验证您的邮箱地址') // 邮件主题
            ->greeting('欢迎加入我们！') // 问候语
            ->line('请点击下方按钮验证您的邮箱地址，完成注册流程。') // 第一行内容
            ->line('验证后您将获得完整的系统使用权限。') // 第二行内容（introLines）
            ->action('验证我的邮箱', $verificationUrl) // 按钮文本（$actionText）
            ->line('如果您没有注册过我们的系统，请忽略此邮件。'); // 结尾内容（outroLines）
    }
}
