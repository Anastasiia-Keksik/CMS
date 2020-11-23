<?php

namespace App\Services;

use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;

ini_set('error_reporting', E_ALL); // or error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');

class PayPal
{
    /**
     * Returns PayPal HTTP client instance with environment that has access
     * credentials context. Use this instance to invoke PayPal APIs, provided the
     * credentials have access.
     */
    public static function client()
    {
        return new PayPalHttpClient(self::environment());
    }

    /**
     * Set up and return PayPal PHP SDK environment with PayPal access credentials.
     * This sample uses SandboxEnvironment. In production, use LiveEnvironment.
     */
    public static function environment()
    {
        $clientId = getenv("CLIENT_ID") ?: "AbUp2Tmzd9KibaJBxnWfuSdrzEkbzkeROzDwHmjkd1BUB0IZhDb18txt07sVwn4eadMUF9IbP7vcm4dm";
        $clientSecret = getenv("CLIENT_SECRET") ?: "EKIGQFm81xogVjdBGxh-H40MXwHk6ZkIYLgvCeHRHHFZ08Oafs1jJoA_i8RPDgR2oTmIScLXoQgfMSVv";
        return new SandboxEnvironment($clientId, $clientSecret);
    }
}