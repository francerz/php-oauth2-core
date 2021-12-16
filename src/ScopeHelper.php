<?php

namespace Francerz\OAuth2;

abstract class ScopeHelper
{
    /**
     * @param string|array|object $scope
     * @return array
     */
    public static function toArray($scope): array
    {
        if (is_object($scope)) {
            $scope = (string)$scope;
        }
        if (is_string($scope)) {
            if (empty($scope)) {
                return [];
            }
            $scope = explode(' ', $scope);
        }
        if (!is_array($scope)) {
            return [];
        }
        return array_unique($scope);
    }

    /**
     * @param string|array|object $scope
     * @return string
     */
    public static function toString($scope): string
    {
        if (is_object($scope)) {
            $scope = (string)$scope;
        }
        if (is_array($scope)) {
            $scope = trim(implode(' ', array_unique($scope)));
        }
        if (!is_string($scope)) {
            return '';
        }
        return $scope;
    }

    /**
     * @param string|array|object $tokenScopes
     * @param string|array|object $matchScopes
     * @return bool
     */
    public static function matchAny($tokenScopes, $matchScopes)
    {
        $tokenScopes = static::toArray($tokenScopes);
        $matchScopes = static::toArray($matchScopes);

        if (empty($matchScopes)) {
            return true;
        }

        $matching = array_intersect($tokenScopes, $matchScopes);
        return !empty($matching);
    }

    /**
     * @param string|array|object $tokenScopes
     * @param string|array|object $matchScopes
     * @return bool
     */
    public static function matchAll($tokenScopes, $matchScopes)
    {
        $tokenScopes = static::toArray($tokenScopes);
        $matchScopes = static::toArray($matchScopes);

        $matching = array_intersect($tokenScopes, $matchScopes);
        return count($matching) === count($matchScopes);
    }

    /**
     * @param string|array|object $existing
     * @param string|array|object $new
     * @return array
     */
    public static function merge($existing, $new)
    {
        $existing = static::toArray($existing);
        $new = static::toArray($new);

        return array_unique(array_merge($existing, $new));
    }

    /**
     * @param string|array|object $existing
     * @param string|array|object $new
     * @return string
     */
    public static function mergeString($existing, $new)
    {
        $scopes = static::merge($existing, $new);
        return static::toString($scopes);
    }
}
