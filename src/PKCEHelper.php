<?php

namespace Francerz\OAuth2;

use Francerz\Http\Utils\UriHelper;

abstract class PKCEHelper
{
    /**
     * @param int $length
     */
    public static function generateCode($length)
    {
        return random_bytes(ceil($length * 3 / 4));
    }
    public static function urlEncode($code, $method = CodeChallengeMethodsEnum::PLAIN)
    {
        switch ($method) {
            case CodeChallengeMethodsEnum::PLAIN:
                return $code;
            case CodeChallengeMethodsEnum::SHA256:
                return UriHelper::base64Encode(hash('sha256', $code, true));
        }
    }
}
