<?php

require_once __DIR__ . '/auth_validator.php';

$uid = FirebaseAuthValidator::validate();

echo json_encode([
    'success' => true,
    'uid' => $uid,
    'message' => 'Authentication successful'
]);