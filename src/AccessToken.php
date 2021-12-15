<?php

namespace Francerz\OAuth2;

use DateInterval;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use Francerz\Http\Utils\HttpHelper;
use Psr\Http\Message\MessageInterface;

class AccessToken implements \JsonSerializable
{
    private $accessToken;
    private $tokenType;
    private $expiresIn;
    private $refreshToken;
    private $parameters = array();

    /** @var DateTimeImmutable */
    private $createTime;

    /** @var DateTimeImmutable */
    private $expireTime;

    public static function fromHttpMessage(MessageInterface $message): AccessToken
    {
        $at = HttpHelper::getContent($message);

        $instance = new static(
            $at->access_token,
            $at->token_type,
            $at->expires_in,
            isset($at->refresh_token) ? $at->refresh_token : null,
            null
        );

        foreach ($at as $k => $v) {
            $instance->setParameter($k, $v);
        }

        return $instance;
    }

    /**
     * @param string $accessToken
     * @param string $tokenType
     * @param integer $expiresIn
     * @param string|null $refreshToken
     * @param \DateTimeImmutable|\DateTime|int|null $createTime
     */
    public function __construct(
        string $accessToken,
        string $tokenType = 'Bearer',
        int $expiresIn = 3600,
        ?string $refreshToken = null,
        $createTime = null
    ) {
        $this->accessToken = $accessToken;
        $this->tokenType = $tokenType;
        $this->expiresIn = $expiresIn;
        $this->refreshToken = $refreshToken;
        if (is_int($createTime)) {
            $createTime = new DateTimeImmutable("@{$createTime}");
        } elseif ($createTime instanceof DateTime) {
            $createTime = DateTimeImmutable::createFromMutable($createTime);
        }
        $this->createTime = is_null($createTime) ? new DateTimeImmutable() : $createTime;
        $this->expireTime = $this->createTime->add(DateInterval::createFromDateString("{$this->expiresIn} seconds"));
    }

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

    /**
     * @param int $s
     * @param DateTimeInterface $now
     *
     * @return boolean
     */
    public function isExpired(int $s = 30, DateTimeInterface $now = null): bool
    {
        $now = is_null($now) ? new DateTime() : $now;
        return $this->expireTime->sub(new DateInterval("P{$s}S")) < $now;
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
    public function setAccessToken(string $accessToken)
    {
        $this->accessToken = $accessToken;
    }
    public function getTokenType(): string
    {
        return $this->tokenType;
    }
    public function setTokenType(string $tokenType)
    {
        $this->tokenType = $tokenType;
    }
    public function getExpiresIn(): int
    {
        return $this->expiresIn;
    }
    public function setExpiresIn(int $expiresIn)
    {
        $this->expiresIn = $expiresIn;
    }
    public function getRefreshToken(): ?string
    {
        return $this->refreshToken;
    }
    public function setRefreshToken(string $refreshToken)
    {
        $this->refreshToken = $refreshToken;
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
    public function setParameter(string $name, $value)
    {
        $this->parameters[$name] = $value;
    }
    public function getCreateTime(): DateTimeImmutable
    {
        return $this->createTime;
    }
    #endregion
}
