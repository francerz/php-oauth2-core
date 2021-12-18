<?php

namespace Francerz\OAuth2;

use Francerz\Enum\AbstractEnum;

class ResponseTypesEnum extends AbstractEnum
{
    public const AUTHORIZATION_CODE = 'code';
    public const TOKEN = 'token';
}
