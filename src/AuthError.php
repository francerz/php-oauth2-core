<?php

namespace Francerz\OAuth2;

use Fig\Http\Message\StatusCodeInterface;
use Francerz\Http\Utils\UriHelper;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;

class AuthError
{
    /** @var string */
    private $error; // string

    /** @var string */
    private $errorDescription; // error

    /** @var UriInterface */
    private $errorUri; // UriInterface

    /** @var string */
    private $state; // string

    public function __construct(
        string $state,
        string $error,
        ?string $errorDescription = null,
        ?UriInterface $errorUri = null
    ) {
        $this->state = $state;
        $this->error = $error;
        $this->errorDescription = $errorDescription;
        $this->errorUri = $errorUri;
    }

    public function getErrorResponse()
    {
    }

    public function createErrorRedirect(
        ResponseFactoryInterface $responseFactory,
        UriInterface $redirectUri
    ): ResponseInterface {
        $redirectUri = UriHelper::withQueryParams($redirectUri, array(
            'state' => $this->state,
            'error' => $this->error,
            'error_description' => $this->errorDescription,
            'error_uri' => $this->errorUri
        ));

        $response = $responseFactory
            ->createResponse(StatusCodeInterface::STATUS_TEMPORARY_REDIRECT)
            ->withHeader('Location', (string)$redirectUri);

        return $response;
    }
}
