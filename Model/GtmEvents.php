<?php
/**
 * Copyright © Acid Unit (https://acid.7prism.com). All rights reserved.
 * See LICENSE file for license details.
 */

/** @noinspection PhpMissingClassConstantTypeInspection */

declare(strict_types=1);

namespace AcidUnit\GoogleTagManager\Model;

class GtmEvents
{
    // Customer Events
    public const LOGIN_SUCCESSFUL = 'loginSuccessful';
    public const LOGIN_FAILED = 'loginFailed';
    public const LOGOUT_SUCCESSFUL = 'logoutSuccessful';
    public const REGISTRATION_SUCCESSFUL = 'registrationSuccessful';
    public const REGISTRATION_FAILED = 'registrationFailed';

    // Checkout Events
    public const PRODUCT_REMOVED_FROM_CART = 'productRemovedFromCart';

    // Wishlist
    public const PRODUCT_ADDED_TO_WISHLIST = 'productAddedToWishlist';
    public const PRODUCT_REMOVED_FROM_WISHLIST = 'productRemovedFromWishlist';
}
