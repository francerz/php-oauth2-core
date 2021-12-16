<?php

namespace Francerz\OAuth2;

use Francerz\Http\Utils\HttpHelper;
use Francerz\Http\Utils\UriHelper;
use JsonSerializable;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;

class Error implements JsonSerializable
{
    /** @var string */
    private $error; // string

    /** @var string */
    private $errorDescription; // error

    /** @var UriInterface */
    private $errorUri; // UriInterface

    public function __construct(
        string $error,
        ?string $errorDescription = null,
        ?string $errorUri = null
    ) {
        $this->error = $error;
        $this->errorDescription = $errorDescription;
        $this->errorUri = $errorUri;
    }

    public function getError(): string
    {
        return $this->error;
    }

    public function getErrorDescription(): ?string
    {
        return $this->errorDescription;
    }

    public function getErrorUri(): ?string
    {
        return $this->errorUri;
    }

    public function toAssoc()
    {
        $data = array('error' => $this->error);

        if (isset($this->errorDescription)) {
            $data['error_description'] = $this->errorDescription;
        }
        if (isset($this->errorUri)) {
            $data['error_uri'] = $this->errorUri;
        }

        return $data;
    }

    public function jsonSerialize()
    {
        return $this->toAssoc();
    }

    public static function fromRequest(RequestInterface $request)
    {
        $error = static::fromUri($request->getUri());
        if (isset($error)) {
            return $error;
        }
        return static::fromMessage($request);
    }

    private static function fromMessage(MessageInterface $message)
    {
        $data = HttpHelper::getContent($message);
        if (is_object($data)) {
            $data = (array)$data;
        }
        if (!array_key_exists('error', $data)) {
            return null;
        }
        return new static(
            $data['error'],
            $data['error_description'] ?? null,
            $data['error_uri'] ?? null
        );
    }

    public static function fromResponse(ResponseInterface $response)
    {
        return static::fromMessage($response);
    }

    public static function fromUri(UriInterface $uri)
    {
        $data = UriHelper::getQueryParams($uri);
        if (!array_key_exists('error', $data)) {
            return null;
        }
        return new static(
            $data['error'],
            $data['error_description'] ?? null,
            $data['error_uri'] ?? null
        );
    }
}
