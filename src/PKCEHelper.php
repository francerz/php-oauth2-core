<?php

namespace Francerz\OAuth2;

use Francerz\Http\Utils\UriHelper;
use RuntimeException;

abstract class PKCEHelper
{
    /**
     * Generates a criptographically random string with URL safe features.
     * 
     * @param int $length The length of random string.
     */
    public static function generateCode($length = 32)
    {
        $string = random_bytes(ceil($length * 3 / 4));
        $urlsafe = strtr(rtrim(base64_encode($string), '='), '+/', '-_');
        return substr($urlsafe, 0, $length);
    }
    public static function urlEncode($code, $method = CodeChallengeMethodsEnum::PLAIN)
    {
        switch ($method) {
            case CodeChallengeMethodsEnum::PLAIN:
                return $code;
            case CodeChallengeMethodsEnum::SHA256:
                return UriHelper::base64Encode(hash('sha256', $code, true));
            default:
                throw new RuntimeException('Unknown Code Challenge Method.');
        }
    }
}
