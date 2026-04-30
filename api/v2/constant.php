<?php

define('DB_HOST', 'localhost');
define('DB_USER', 'pstuianc_dev');
define('DB_PASS', 'qGPKUPvsCabp');
define('DB_NAME', 'pstuianc_dev.db');

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

define('BASE_URL', 'https://dev.pstuian.com');
define('BASE_CV_URL', 'https://dev.pstuian.com');
define('BASE_EMAIL_VERIFICATION_URL', 'https://dev.pstuian.com/api/mobile/v1/auth.php?call=emailVarification');

// Change the second parameter to suit your needs
// Here 4 means, how many times it should go up
define('ROOT_DIR', dirname(__FILE__, 4));
define('UPLOAD_PATH', 'uploads/');
define('FILE_PATH',  UPLOAD_PATH . 'files/');
define('SLIDER_PATH', UPLOAD_PATH . 'slider/');
define('FACULTY_ICON_PATH', UPLOAD_PATH . 'faculty_icon/');
define('AVATAR_PATH',  UPLOAD_PATH . 'avatar/');
define('STUDENT_IMAGE_PATH',  UPLOAD_PATH . 'student/');
define('TEACHER_IMAGE_PATH',  UPLOAD_PATH . 'teacher/');
define('CV_PATH',  UPLOAD_PATH . 'cv/');

define('PAGE_LIMIT',  20);

define('ALLOWED_EXTENTION',  array("png","jpg","jpeg"));
define('ALLOWED_ANIM_EXTENTION',  array("json"));
define('MAX_SIZE',  2000000);

define('FCM_PUSH_URL',  'https://fcm.googleapis.com/fcm/send');
define('FCM_SERVER_KEY', 'FCM-SERVER-KEY');

define('FCM_CLICK_ACTION', array(
    "open_chat" => "com.workfort.pstuian.action.OPEN_CHAT",
    "open_post" => "com.workfort.pstuian.action.OPEN_POST",
    "open_friend_request" => "com.workfort.pstuian.action.OPEN_FRIEND_REQUEST",
    "open_notifications" => "com.workfort.pstuian.action.OPEN_NOTIFICATIONS",
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

define('SUCCESS', 'S00000');
define('MISSING_PARAM', 'S00001');
define('INVALID_PARAM', 'S00002');
define('READ_FAILED', 'S00003'); // failed to get data from db
define('WRITE_FAILED', 'S00004'); // failed to store data to db
define('AUTH_FAILED', 'S00005');
define('VALIDATION_FAILED', 'S00006');
define('UNKONWN_ERROR', 'S11111');

define('USER_ID_INVALID', 'SU000'); // user without valid user_id
define('USER_NOT_FOUND', 'SU001');
define('USER_REGISTRATION_FAILED', 'SU002');
define('USER_BLOCKLISTED', 'SU003');
define('USER_ALREADY_EXIST', 'SU004');