<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('test:email {to}', function ($to) {
    Mail::raw('这是一封测试邮件', function ($message) use ($to) {
        $message->to($to)->subject('Laravel邮件测试');
    });

    $this->info('邮件已发送到: ' . $to);
})->describe('发送测试邮件');
