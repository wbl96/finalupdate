<?php

namespace App\Services;

use App\Mail\PasswordResetMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ResetPasswordService
{

    /**
     * resetPassword function
     * is used to send a reset password link to a new admin
     */
    public function resetPassword($email, $route, $table)
    {
        // check if email was sent
        if ($this->sendResetPasswordEmail($email, $route, $table)) {
            // return success message
            return response([
                'success' => 'تم إرسال بريد اعادة ضبط كلمة المرور للموظف',
            ]);
        }
        // return failed message
        return response([
            'error' => 'يوجد خطأ أثناء ارسال الايميل!',
        ]);
    }

    /**
     * sendResetPasswordEmail function
     * is used to generate unique id (token) to make admin to reset password
     */
    public function sendResetPasswordEmail($email, $route, $table)
    {
        // generate token
        $token = Str::uuid();
        // prepare url
        $url = route($route, $token);
        // insert reset password data into database
        DB::table($table)->upsert([
            'email' => $email,
            'token' => $token,
            'created_at' => now(),
        ], ['email' => $email,]);
        // email title
        $emailTitle = trans('global.set password');
        // email data
        $data = [
            'subject' => $emailTitle,
            'header_title' => $emailTitle,
            'btn_title' => $emailTitle,
        ];
        // return true if email was send & false if not send
        return Mail::to($email)->send(new PasswordResetMail($url, $data));
    }
}
