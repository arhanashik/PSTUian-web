<?php

// ----------- v1 ----------//
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'root');
define('DB_NAME', 'pstuian.db');

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

define('BASE_URL', 'http://192.168.1.101:8888/PSTUian-web');

// Change the second parameter to suit your needs
// Here 4 means, how many times it should go up
define('ROOT_DIR', dirname(__FILE__, 4));
define('UPLOAD_PATH', '/uploads/');
define('FILE_PATH',  UPLOAD_PATH . 'files/');
define('ANIM_PATH', UPLOAD_PATH . 'anim/');
define('AVATAR_PATH',  UPLOAD_PATH . 'avatar/');

define('PAGE_LIMIT',  20);

define('ALLOWED_EXTENTION',  array("png","jpg","jpeg"));
define('ALLOWED_ANIM_EXTENTION',  array("json"));
define('MAX_SIZE',  2000000);

define('FCM_PUSH_URL',  'https://fcm.googleapis.com/fcm/send');
define('FCM_SERVER_KEY', 'AAAApDaCgzY:APA91bEL7YWGlYp3wcr7MrcyTrzQ5n7g9G7TB5DPvzIALNncnkfiPL0m7FjFp0kM0KfW3iG5mp5N1OePsGND__6Q89_06g_yp54ZkPQj8c-UIuS6UF5YP6AxFXF7Gm0V1bkZ_SPy6m0-');

define('FCM_CLICK_ACTION', array(
    "open_chat" => "com.workfort.fakebook.action.OPEN_CHAT",
    "open_post" => "com.workfort.fakebook.action.OPEN_POST",
    "open_friend_request" => "com.workfort.fakebook.action.OPEN_FRIEND_REQUEST",
    "open_notifications" => "com.workfort.fakebook.action.OPEN_NOTIFICATIONS",
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