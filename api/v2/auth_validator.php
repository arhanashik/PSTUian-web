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
    private static ?string $email = null;

    /**
     * Automatically validates the request.
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
            $claims = $verifiedToken->claims();

            self::$uid = $claims->get('sub');
            self::$email = $claims->get('email');
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
     * Returns the authenticated Firebase email.
     */
    public static function email(): ?string
    {
        self::validate();
        return self::$email;
    }

    /**
     * Returns both UID and email.
     */
    public static function user(): array
    {
        self::validate();

        return [
            'uid' => self::$uid,
            'email' => self::$email,
        ];
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
*/
FirebaseAuthValidator::validate();