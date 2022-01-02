<?php

namespace Francerz\OAuth2;

use Francerz\Enum\AbstractEnum;

class ClientTypesEnum extends AbstractEnum
{
    public const TYPE_CONFIDENTIAL = 'confidential';
    public const TYPE_PUBLIC = 'public';
}
