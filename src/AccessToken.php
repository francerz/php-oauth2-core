<?php

namespace Francerz\OAuth2;

use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use Exception;
use Psr\Http\Message\MessageInterface;

class AccessToken implements \JsonSerializable
{
    private $accessToken;
    private $tokenType;
    private $expiresIn;
    private $refreshToken;
    private $scope;
    private $parameters = array();

    /** @var DateTimeImmutable */
    private $createTime;

    /** @var DateTimeImmutable */
    private $expireTime;

    public static function fromMessage(MessageInterface $message): AccessToken
    {
        $jsonString = (string)$message->getBody();
        return static::fromJsonString($jsonString);
    }

    public static function fromJsonString(string $jsonString)
    {
        $jsonObject = json_decode($jsonString);

        if (!is_object($jsonObject)) {
            throw new Exception("Access Token parse error:" . PHP_EOL . $jsonString);
        }

        return static::fromObject($jsonObject);
    }

    private static function fromObject(object $object)
    {
        if (isset($object->error)) {
            throw new OAuth2ErrorException(OAuth2Error::fromObject($object));
        }

        $instance = new static(
            $object->access_token,
            isset($object->token_type)    ? $object->token_type    : 'Bearer',
            isset($object->expires_in)    ? $object->expires_in    : 3600,
            isset($object->refresh_token) ? $object->refresh_token : null,
            isset($object->scope)         ? $object->scope         : ''
        );

        foreach ($object as $k => $v) {
            if (in_array($k, ['access_token', 'token_type', 'expires_in', 'refresh_token', 'scope'])) {
                continue;
            }
            $instance = $instance->withParameter($k, $v);
        }

        return $instance;
    }

    /**
     * @param string $accessToken
     * @param string $tokenType
     * @param integer $expiresIn
     * @param string|null $refreshToken
     * @param string|null $scope
     * @param \DateTimeImmutable|\DateTime|int|null $createTime
     */
    public function __construct(
        string $accessToken,
        string $tokenType = 'Bearer',
        int $expiresIn = 3600,
        ?string $refreshToken = null,
        string $scope = '',
        $createTime = null
    ) {
        $createTime = static::valueAsDateTimeImmutable($createTime);
        $this->createTime = is_null($createTime) ? new DateTimeImmutable() : $createTime;

        $this->accessToken = $accessToken;
        $this->tokenType = $tokenType;
        $this->expiresIn = $expiresIn;
        $this->refreshToken = $refreshToken;
        $this->scope = $scope;

        $this->refreshExpireTime();
    }

    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        $json = array(
            'access_token' => $this->accessToken,
            'token_type' => $this->tokenType,
            'expires_in' => $this->expiresIn
        );
        if (isset($this->refreshToken)) {
            $json['refresh_token'] = $this->refreshToken;
        }
        $json = array_merge($json, $this->parameters);
        return $json;
    }

    public function getExpireTime(): DateTimeImmutable
    {
        return $this->expireTime;
    }

    private function refreshExpireTime()
    {
        $this->expireTime = $this->createTime->modify("+{$this->expiresIn} seconds");
    }

    /**
     * @param int $s
     * @param DateTimeInterface $now
     *
     * @return boolean
     */
    public function isExpired(int $s = 30, DateTimeInterface $now = null): bool
    {
        $now = is_null($now) ? new DateTime() : $now;
        return $this->expireTime->modify("-{$s} seconds") < $now;
    }

    public function __toString()
    {
        return "{$this->tokenType} {$this->accessToken}";
    }

    #region Property Accesors
    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    /**
     * @deprecated v0.3.4 Use immutable method withAccessToken instead.
     */
    public function setAccessToken(string $accessToken)
    {
        $this->accessToken = $accessToken;
    }

    /**
     * @param string $accessToken
     * @return static
     */
    public function withAccessToken(string $accessToken)
    {
        $clone = clone $this;
        $clone->accessToken = $accessToken;
        return $clone;
    }

    public function getTokenType(): string
    {
        return $this->tokenType;
    }

    /**
     * @deprecated v0.3.4 Use immutable method withTokenType instead.
     */
    public function setTokenType(string $tokenType)
    {
        $this->tokenType = $tokenType;
    }

    /**
     * @param string $tokenType
     * @return static
     */
    public function withTokenType(string $tokenType)
    {
        $clone = clone $this;
        $clone->tokenType = $tokenType;
        return $clone;
    }

    public function getExpiresIn(): int
    {
        return $this->expiresIn;
    }

    /**
     * @deprecated v0.3.4 Use immutable method withExpiresIn instead.
     */
    public function setExpiresIn(int $expiresIn)
    {
        $this->expiresIn = $expiresIn;
        $this->refreshExpireTime();
    }

    /**
     * @param int $expiresIn
     * @return static
     */
    public function withExpiresIn(int $expiresIn)
    {
        $clone = clone $this;
        $clone->expiresIn = $expiresIn;
        $clone->refreshExpireTime();
        return $clone;
    }

    public function getRefreshToken(): ?string
    {
        return $this->refreshToken;
    }

    /**
     * @deprecated v0.3.4 Use immutable method withRefreshToken instead.
     */
    public function setRefreshToken(string $refreshToken)
    {
        $this->refreshToken = $refreshToken;
    }

    /**
     * @param string $refreshToken
     * @return static
     */
    public function withRefreshToken(string $refreshToken)
    {
        $clone = clone $this;
        $clone->refreshToken = $refreshToken;
        return $clone;
    }

    public function getScope(): string
    {
        return $this->scope;
    }

    /**
     * @deprecated v0.3.4 Use immutable method withScope instead.
     */
    public function setScope(string $scope)
    {
        $this->scope = $scope;
    }

    /**
     * @param string $scope
     * @return static
     */
    public function withScope(string $scope)
    {
        $clone = clone $this;
        $clone->scope = $scope;
        return $clone;
    }

    public function hasParameter(string $name)
    {
        return array_key_exists($name, $this->parameters);
    }

    public function getParameter(string $name)
    {
        if (array_key_exists($name, $this->parameters)) {
            return $this->parameters[$name];
        }
        return null;
    }

    /**
     * @deprecated v0.3.4 Use immutable method withParameter instead.
     */
    public function setParameter(string $name, $value)
    {
        $this->parameters[$name] = $value;
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return static
     */
    public function withParameter(string $name, $value)
    {
        $clone = clone $this;
        $clone->parameters[$name] = $value;
        return $clone;
    }

    public function getCreateTime(): DateTimeImmutable
    {
        return $this->createTime;
    }

    private static function valueAsDateTimeImmutable($value)
    {
        if ($value instanceof DateTimeImmutable) {
            return $value;
        }
        if (is_int($value)) {
            return new DateTimeImmutable("@{$value}");
        }
        if (is_string($value)) {
            return new DateTimeImmutable($value);
        }
        if ($value instanceof DateTime) {
            return DateTimeImmutable::createFromMutable($value);
        }
        return null;
    }
    #endregion
}
