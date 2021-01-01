<?php

namespace Francerz\OAuth2;

abstract class ScopeHelper
{
    public static function toArray($scope) : array
    {
        if (is_object($scope)) {
            $scope = (string)$scope;
        }
        if (is_string($scope)) {
            $scope = explode(' ', $scope);
        }
        if (!is_array($scope)) {
            return [];
        }
        return $scope;
    }

    public static function toString($scope) : string
    {
        if (is_array($scope)) {
            $scope = implode(' ', $scope);
        } elseif (is_object($scope)) {
            $scope = (string)$scope;
        }
        if (!is_string($scope)) {
            return '';
        }
        return $scope;
    }

    public static function matchAny($tokenScopes, $matchScopes)
    {
        $tokenScopes = static::toArray($tokenScopes);
        $matchScopes = static::toArray($matchScopes);

        if (empty($matchScopes)) return true;

        $matching = array_intersect($tokenScopes, $matchScopes);
        return !empty($matching);
    }

    public static function matchAll($tokenScopes, $matchScopes)
    {
        $tokenScopes = static::toArray($tokenScopes);
        $matchScopes = static::toArray($matchScopes);

        $matching = array_intersect($tokenScopes, $matchScopes);
        return count($matching) === count($matchScopes);
    }

    public static function merge($existing, $new)
    {
        $existing = static::toArray($existing);
        $new = static::toArray($new);

        return array_unique(array_merge($existing, $new));
    }

    public static function mergeString($existing, $new)
    {
        $scopes = static::merge($existing, $new);
        return static::toString($scopes);
    }
}