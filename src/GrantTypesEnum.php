<?php

namespace Francerz\OAuth2;

use Francerz\Enum\AbstractEnum;

class GrantTypesEnum extends AbstractEnum
{
    public const AUTHORIZATION_CODE    = 'authorization_code';
    public const TOKEN                 = 'token';
    public const PASSWORD              = 'password';
    public const CLIENT_CREDENTIALS    = 'client_credentials';

    public const REFRESH_TOKEN         = 'refresh_token';
}
