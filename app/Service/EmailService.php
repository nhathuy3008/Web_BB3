<?php

namespace App\Service;

use Illuminate\Support\Facades\Mail;

class EmailService
{
    public function sendVerificationEmail($to, $token, $fullName)
{
    $verificationLink = url('api/accounts/confirm/' . $token); // Tạo liên kết xác nhận

    try {
        Mail::send('emails.confirmation', [
            'name' => $fullName,
            'link' => $verificationLink
        ], function ($message) use ($to) {
            $message->to($to)
                    ->subject('Xác Nhận Tài Khoản');
        });
    } catch (\Exception $e) {
        \Log::error('Error sending email: ' . $e->getMessage());
        throw new \Exception('Không thể gửi email xác nhận.');
    }
}
}