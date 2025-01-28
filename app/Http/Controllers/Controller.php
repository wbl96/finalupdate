<?php

namespace App\Http\Controllers;

use Google\Auth\Credentials\ServiceAccountCredentials;
use Google\Auth\HttpHandler\HttpHandlerFactory;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function pushFCMNotifaication($user_token, $title, $body)
    {
        // try {
        //     $credential = new ServiceAccountCredentials(
        //         "https://www.googleapis.com/auth/firebase.messaging",
        //         json_decode(file_get_contents(base_path('wbl-web-firebase-adminsdk-lptzi-426e141d98.json')), true)
        //     );

        //     $token = $credential->fetchAuthToken(HttpHandlerFactory::build());

        //     $ch = curl_init("https://fcm.googleapis.com/v1/projects/wbl-web/messages:send");
        //     curl_setopt($ch, CURLOPT_HTTPHEADER, [
        //         'Content-Type: application/json',
        //         'Authorization: Bearer ' . $token['access_token']
        //     ]);

        //     curl_setopt($ch, CURLOPT_POSTFIELDS, '{
        //         "message": {
        //             "token": ' . $user_token . ',
        //             "notification": {
        //                 "title": ' . $title . ',
        //                 "body": ' . $body . '
        //             },
        //             "webpush": {
        //                 "fcm_options": {
        //                 "link": "https://google.com"
        //             }
        //           }
        //         }
        //       }');

        //     curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "post");

        //     $response = curl_exec($ch);

        //     curl_close($ch);
        //     Log::channel('fcm')->info($response);
        //     // dd($response);
        // } catch (\Throwable $th) {
        //     Log::channel('fcm')->error($th->getMessage());
        // }
    }
}
