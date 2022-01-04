<?php

namespace Francerz\OAuth2;

use Francerz\Enum\AbstractEnum;

class GrantTypesEnum extends AbstractEnum
{
    /**
     * The Authorization Code grant type is used by confidential and public
     * clients to exchange an authorization code for an access token.
     *
     * After the user returns to the client via the redirect URL, the
     * application will get the authorization code from the URL and use it to
     * request an access token.
     *
     * It is recommended that all clients use the PKCE extension with this flow
     * as well to provide better security.
     */
    public const AUTHORIZATION_CODE    = 'authorization_code';

    public const PASSWORD              = 'password';

    public const CLIENT_CREDENTIALS    = 'client_credentials';

    public const REFRESH_TOKEN         = 'refresh_token';
}
