<?php
/**
 * Copyright © Acid Unit (https://acid.7prism.com). All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace AcidUnit\GoogleTagManager\Model;

class GtmEvents
{
    public const LOGIN_SUCCESSFUL = 'loginSuccessful';
    public const LOGIN_FAILED = 'loginFailed';
    public const LOGOUT_SUCCESSFUL = 'logoutSuccessful';
    public const REGISTRATION_SUCCESSFUL = 'registrationSuccessful';
    public const REGISTRATION_FAILED = 'registrationFailed';
}
