<?php

namespace Francerz\OAuth2;

class ServerAccessToken extends AccessToken
{
    private $clientId;
    private $ownerId;

    /**
     * @param string $clientId
     * @param string $ownerId
     * @param string $accessToken
     * @param string $tokenType
     * @param integer $expiresIn
     * @param string|null $refreshToken
     * @param string $scope
     * @param \DateTimeImmutable|\DateTime|int|null $createTime
     */
    public function __construct(
        string $clientId,
        string $ownerId,
        string $accessToken,
        string $tokenType = 'Bearer',
        int $expiresIn = 3600,
        ?string $refreshToken = null,
        string $scope = '',
        $createTime = null
    ) {
        parent::__construct($accessToken, $tokenType, $expiresIn, $refreshToken, $scope, $createTime);
        $this->clientId = $clientId;
        $this->ownerId = $ownerId;
    }

    public function setClientId(string $clientId)
    {
        $this->clientId = $clientId;
    }
    public function getClientId(): string
    {
        return $this->clientId;
    }

    public function setOwnerId($ownerId)
    {
        $this->ownerId = $ownerId;
    }
    public function getOwnerId()
    {
        return $this->ownerId;
    }

    public function matchAnyScope(array $scopes)
    {
        return ScopeHelper::matchAny($this->getScope(), $scopes);
    }

    public function matchAllScopes(array $scopes)
    {
        return ScopeHelper::matchAll($this->getScope(), $scopes);
    }
}
