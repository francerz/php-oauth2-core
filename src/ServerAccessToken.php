<?php

namespace Francerz\OAuth2;

class ServerAccessToken extends AccessToken
{
    private $clientId;
    private $ownerId;
    private $scope;

    public function __construct(
        string $accessToken,
        string $clientId,
        $ownerId,
        string $scope = '',
        string $tokenType = 'Bearer',
        int $expiresIn = 3600,
        ?string $refreshToken = null,
        ?int $createTime = null
    ) {
        parent::__construct($accessToken, $tokenType, $expiresIn, $refreshToken, $createTime);
        $this->clientId = $clientId;
        $this->ownerId = $ownerId;
        $this->setScope($scope);
    }

    public function setClientId(string $clientId)
    {
        $this->clientId = $clientId;
    }
    public function getClientId() : string
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
    
    public function setScope(string $scope)
    {
        $this->scope = $scope;
    }
    public function getScope() : string
    {
        return $this->scope;
    }

    public function matchAnyScope(array $scopes)
    {
        return ScopeHelper::matchAny($this->scope, $scopes);
    }

    public function matchAllScopes(array $scopes)
    {
        return ScopeHelper::matchAll($this->scope, $scopes);
    }
}