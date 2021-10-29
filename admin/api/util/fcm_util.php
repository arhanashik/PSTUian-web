<?php
require_once dirname(__FILE__) . '/../constant.php';

class FcmUtil {
    public function createPushNotification($title, $body, $clickAction) {
        // return array(
        //     'message' 	    => $body,
        //     'title'		    => $title,
        //     'subtitle'	    => '',
        //     'tickerText'	=> '',
        //     'vibrate'	    => 1,
        //     'sound'		    => 1,
        //     'largeIcon'	    => 'large_icon',
        //     'smallIcon'	    => 'small_icon',
        //     "click_action"  => $clickAction
        // );
        return array (
            "title"                 => $title,
            "body"                  => $body,
            // "android_channel_id" => $channelId,
            "click_action"          => $clickAction
        );
    }

    public function createPushData($type, $title, $message) {
        return array (
            "type" => $type,
            "title" => $title,
            "message" => $message
        );
    }

    public function sendPush($fcm_tokes, $notification, $data) {
        $fields = array (
            'registration_ids' => $fcm_tokes,
            'notification' => $notification,
            'data' => $data
        );
    
        //header includes Content type and api key
        $headers = array(
            'Content-Type:application/json',
            'Authorization:key=' . FCM_SERVER_KEY
        );
                    
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, FCM_PUSH_URL);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        // if ($result === FALSE) {
        //     die('FCM Send Error: ' . curl_error($ch));
        // }
        curl_close($ch);

        return $result;
    }
}