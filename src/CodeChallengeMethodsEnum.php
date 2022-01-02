<?php

namespace Francerz\OAuth2;

use Francerz\Enum\AbstractEnum;

class CodeChallengeMethodsEnum extends AbstractEnum
{
    public const PLAIN = 'plain';
    public const SHA256 = 'S256';
}
