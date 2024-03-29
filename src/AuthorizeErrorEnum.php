<?php

namespace Francerz\OAuth2;

use Francerz\Enum\AbstractEnum;

class AuthorizeErrorEnum extends AbstractEnum
{
    /**
     * The request is missing a required parameter, includes an invalid
     * parameter value, includes a parameter more than once, or is otherwise
     * malformed.
     */
    public const INVALID_REQUEST = 'invalid_request';

    /**
     * The client is not authorized to request an authorization code using this
     * method.
     */
    public const UNAUTHORIZED_CLIENT = 'unauthorized_client';

    /**
     * The resource owner or authorization server denied the request.
     */
    public const ACCESS_DENIED = 'access_denied';

    /**
     * The authorization server does not support obtaining an authorization code
     * using this method.
     */
    public const UNSUPPORTED_RESPONSE_TYPE = 'unsupported_response_type';

    /**
     * The requested scope is invalid, unknown, or malformed.
     */
    public const INVALID_SCOPE = 'invalid_scope';

    /**
     * The authorization server encountered an unexepected condition that
     * prevented it from fulfilling the request.
     * (This error is needed because a 500 Internal Server Error HTTP status
     * code cannot be returned to the client via an HTTP redirect.)
     */
    public const SERVER_ERROR = 'server_error';

    /**
     * The authorization server is currently unable to handle the request due
     * to a temporary overloading or maintenance of the server. (This error is
     * needed because a 503 Service Unavailable HTTP status code cannot be
     * returned to the client via an HTTP redirect.)
     */
    public const TEMPORARILY_UNAVAILABLE = 'temporarily_unavailable';
}
