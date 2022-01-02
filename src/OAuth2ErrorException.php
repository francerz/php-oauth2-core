<?php

namespace Francerz\OAuth2;

use Throwable;

class OAuth2ErrorException extends OAuth2Exception
{
    private $error;

    public function __construct(OAuth2Error $error, $message = "", $code = 0, Throwable $previous = null)
    {
        if (empty($message)) {
            $message = $error->getErrorDescription() ?? '';
        }
        parent::__construct($message, $code, $previous);
    }
}
