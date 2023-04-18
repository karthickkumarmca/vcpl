<?php

namespace App\Helpers;

use App;

/**
 * Sending push notification for the android and ios devices
 *
 * @author Sivabalan
 */
class PushNotification {

    /**
     * Send the push notification to the devices
     *
     * @param  int        $messageType   message type 1=notification message, 2=data message
     * @param  int        $deviceType   whethere ios or android 1=android 2=ios, 3=web
     * @param  int        $appType      app type 1=customer app, 2=employee app, 3=fuel portal app
     * @param  string     $message      notification message
     * @param  array      $deviceTokens device tokens
     * @param  array      $data         notification background data
     *
     * @return array|void               notification success/failure response
     */
    public static function sendPushNotification(int $deviceType, array $deviceTokens, array $data) {
        try {
            //app('log')->channel('request')->info("data ========> " .json_encode($data));
            if ($deviceType === 1) { //Android
                return self::sendAndroidNotification($data);
            }
            else { //iOS
                self::sendIosPushNotification($deviceTokens, $data);
            }
        }
        catch (Exception $e) {

        }
    }

    /**
     * Sends the push notification to the android devices
     *
     * @param  int    $messageType   message type 1=notification message, 2=data message
     * @param  int    $appType       app type 1=customer app, 2=employee app
     * @param  string $message       notification message
     * @param  array  $deviceTokens  device tokens
     * @param  array  $data          notification background data
     *
     * @return array                 notification success/failure response
     */
    public static function sendAndroidNotification(array $data) {
        //if ($messageType === 1) { //notification message
            /*$data = [
                "registration_ids" => $deviceTokens,
                "notification"     => [
                    "title" => env("PUSH_NOTIFICATION_TITLE"),
                    "body"  => "Good evening",
                ],
                'data'             => $data ?: (Object)$data,
            ];*/
        /*}
        else { //data message
            $data = [
                "registration_ids" => $deviceTokens,
                'data'             => (Object)$data,
            ];
        }*/

        $ch = \curl_init("https://fcm.googleapis.com/fcm/send");
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type:application/json',
            'Authorization:key=' . env('FCM_AUTHORIZATION_KEY', 'AAAA-jiBhqM:APA91bG3Th1RZL7Zp5ltISmGF3lkDbyfWNzr-mmqvof0HH_ePC8xhlGHVupy1TlexZj3Xw4I00royQuPLhOGT-pcrmN3bv0OZ3nFdSdNx9izTjiRYp3nXcepUzQAvpSWUtlDa2zLQdk-')
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $response = curl_exec($ch);
        curl_close($ch);
        return json_decode($response, true);
    }

    /**
     * Sends the push notification to the ios devices
     *
     * @param  array  $deviceTokens device tokens
     * @param  array  $data         notification data
     *
     * @return void
     */
    public static function sendIosPushNotification(array $deviceTokens, array $data) {
        set_error_handler(function ($errNumber, $errorMessage) {
            app('log')->info($errorMessage);

            return true;
        });

        $ctx = stream_context_create();
        if (App::environment() !== "prod") {
            stream_context_set_option($ctx, 'ssl', 'local_cert', __DIR__ . '/../../' . 'apns-dev-cert.pem');
        }
        else {
            stream_context_set_option($ctx, 'ssl', 'local_cert', __DIR__ . '/../../' . 'apns-cert.pem');
        }
        stream_context_set_option($ctx, 'ssl', 'passphrase', env('APNS_SERVER_PASSWORD', ''));

        // Open a connection to the APNS server
        $fp = stream_socket_client(env('APNS_SERVER_URL', 'ssl://gateway.push.apple.com:2195'), $err, $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);

        if ($fp) {
            // Create the payload body
            foreach ($deviceTokens AS $token) {
                if (ctype_xdigit($token)) {
                    $payload = json_encode($data);
                    // Build the binary notification
                    $msg = chr(0) . pack('n', 32) . pack('H*', $token) . pack('n', strlen($payload)) . $payload;
                    // Send it to the server
                    $result = fwrite($fp, $msg, strlen($msg));

                    if ($token === "8405F987414781CE44C3BCBFB26CC46D47FCA2B339EDB53CD0CA1D2304475F09") {
                        app('log')->channel('notification')->info($payload . " " . $token);
                    }
                }
            }

            // Close the connection to the server
            fclose($fp);
        }

        restore_error_handler();
    }
}
