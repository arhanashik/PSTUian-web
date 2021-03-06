<?php

// ----------- v1 ----------//
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'root');
define('DB_NAME', 'pstuian_dev.db');

// ----------- main tables -----------//
define('SLIDER_TABLE', 'slider');
define('FACULTY_TABLE', 'faculty');
define('BATCH_TABLE', 'batch');
define('TEACHER_TABLE', 'teacher');
define('COURSE_TABLE', 'course');
define('EMPLOYEE_TABLE', 'employee');
define('STUDENT_TABLE', 'student');
define('DONATION_TABLE', 'donation');
define('INFO_TABLE', 'info');
define('ADMIN_TABLE', 'admin');
define('AUTH_TABLE', 'auth');
define('CONFIG_TABLE', 'config');
define('USER_QUERY_TABLE', 'user_query');
define('USER_QUERY_REPLY_TABLE', 'user_query_reply');
define('DEVICE_TABLE', 'device');
define('LOG_TABLE', 'log');
define('NOTIFICATION_TABLE', 'notification');
define('PASSWORD_RESET_TABLE', 'password_reset');
define('BLOOD_DONATION_TABLE', 'blood_donation');
define('BLOOD_DONATION_REQUEST_TABLE', 'blood_donation_request');
define('CHECK_IN_TABLE', 'check_in');
define('CHECK_IN_LOCATION_TABLE', 'check_in_location');
define('VERIFICATION_TABLE', 'verification');

define('BASE_URL', 'http://192.168.1.103:8888/PSTUian-web/');
define('BASE_EMAIL_VERIFICATION_URL', 'http://192.168.1.103:8888/PSTUian-web/api/mobile/v1/auth.php?call=emailVarification');

// Change the second parameter to suit your needs
// Here 4 means, how many times it should go up
define('ROOT_DIR', dirname(__FILE__, 3));
define('UPLOAD_PATH', 'uploads/');
define('FILE_PATH',  UPLOAD_PATH . 'files/');
define('SLIDER_PATH', UPLOAD_PATH . 'slider/');
define('FACULTY_ICON_PATH', UPLOAD_PATH . 'faculty_icon/');
define('AVATAR_PATH',  UPLOAD_PATH . 'avatar/');

define('PAGE_LIMIT',  20);

define('ALLOWED_EXTENTION',  array("png", "jpg", "jpeg"));
define('ALLOWED_ANIM_EXTENTION',  array("json"));
define('MAX_SIZE',  2000000);

define('FCM_PUSH_URL',  'https://fcm.googleapis.com/fcm/send');
define('FCM_SERVER_KEY', 'AAAA4YeC81c:APA91bG2Q427M3TltRfUIesa8tlBUdROrvTKIbOHSFRTx5dVdBzUz8BZ2XQ9GZQQVrTy-501fua81i2_S_nRS8APXNAevdwFzQDtsHy-km4UscozSgNV3zYHxeroyOcKalCi2ajLfOKd');

define('FCM_CLICK_ACTION', array(
    "open_chat" => "com.workfort.pstuian.action.OPEN_CHAT",
    "open_post" => "com.workfort.pstuian.action.OPEN_POST",
    "open_notifications" => "com.workfort.pstuian.action.OPEN_NOTIFICATIONS",
    "open_blood_donation_request" => "com.workfort.pstuian.action.OPEN_BLOOD_DONATION_REQUEST",
));

define('MESSAGE_TYPE', array(
    "text" => 1,
    "sticker" => 2,
    "image" => 3,
    "audio" => 4,
    "video" => 5
));

define('MESSAGE_STATUS', array(
    "failed" => -1, //message sending failed
    "default" => 0, //server had the message but no action taken yet
    "sent" => 1, //message sent to receiver
    "received" => 2, //receiver received the message
    "seen" => 3 //receiver saw the message
));

define('SUCCESS', 200);
define('ERROR_REQUIRED_PARAMETES_MISSING', 1000);
define('ERROR_ACCOUNT_ALREADY_EXISTS', 1001);
define('ERROR_ACCOUNT_DOES_NOT_EXIST', 1002);
define('ERROR_FAILED_TO_AUTHENTICATE', 1003);
define('ERROR_FAILED_TO_REGISTER', 1004);
define('ERROR_FAILED_TO_UPDATE', 1005);

// ----------- v1 ----------//