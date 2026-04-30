<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';
require_once './constant.php';

use Kreait\Firebase\Factory;
use Kreait\Firebase\Auth;
use Kreait\Firebase\Exception\Auth\FailedToVerifyToken;

final class FirebaseAuthValidator
{
    private static ?Auth $auth = null;
    private static ?string $uid = null;

    /**
     * Automatically validates the request.
     * Call this once after requiring the file.
     */
    public static function validate(): void
    {
        if (self::$uid !== null) {
            return;
        }

        $idToken = $_SERVER['HTTP_X_AUTH_TOKEN'] ?? '';

        if (empty($idToken)) {
            self::unauthorized('Authentication token is missing.');
        }

        try {
            $verifiedToken = self::auth()->verifyIdToken($idToken);
            self::$uid = $verifiedToken->claims()->get('sub');
        } catch (FailedToVerifyToken $e) {
            self::unauthorized('Invalid or expired authentication token.');
        } catch (\Throwable $e) {
            self::unauthorized('Authentication service unavailable.');
        }
    }

    /**
     * Returns the authenticated Firebase UID.
     */
    public static function uid(): string
    {
        self::validate();
        return self::$uid;
    }

    /**
     * Creates the Firebase Auth instance.
     */
    private static function auth(): Auth
    {
        if (self::$auth === null) {
            self::$auth = (new Factory())
                ->withServiceAccount(
                    __DIR__ . '/../private/pstuian-debug-firebase-adminsdk.json'
                )
                ->createAuth();
        }

        return self::$auth;
    }

    /**
     * Sends a 401 response and terminates execution.
     */
    private static function unauthorized(string $message): never
    {
        http_response_code(401);
        header('Content-Type: application/json');

        echo json_encode([
            'code' => AUTH_FAILED,
            'message' => $message,
        ]);

        exit;
    }
}

/*
|--------------------------------------------------------------------------
| Automatic Request Validation
|--------------------------------------------------------------------------
|
| Simply requiring this file will immediately validate the Firebase token.
| If validation fails, execution stops here automatically.
|
*/
FirebaseAuthValidator::validate();