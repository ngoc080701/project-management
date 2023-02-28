<?php

namespace App\Enum;

/**
 * Class AuthConst
 *
 * @package Modules\Auth\Constants
 */
class AuthConst
{
    public const TOKEN_TYPE = 'Bearer';

    /**
     * Authentication Guards
     */
    public const GUARD_API = 'api';
    public const GUARD_WEB = 'web';

    /**
     * Authentication Hash
     */
    public const HASH = 'HS512';
}
