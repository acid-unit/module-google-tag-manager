# 🧪 Google Tag Manager Magento Extension

A powerful, flexible, and user-friendly GTM extension for Magento Open Source & Adobe Commerce.

🔹 Tracks eCommerce events like **Add to Cart, Checkout, Purchases, Clicks, and more**.  
🔹 Customizable **via admin settings** (no coding needed).  
🔹 Works **out of the box** with minimal setup.

## Quick Installation

To install the module, run:

```shell
composer require acid-unit/module-google-tag-manager
```

After installation, enable the module and update Magento:

```shell
bin/magento module:enable AcidUnit_GoogleTagManager
bin/magento setup:upgrade
bin/magento setup:di:compile
bin/magento cache:flush
```

# General Info

Module configuration is done under the
`Stores > Settings > Configuration > Acid Unit > Google Tag Manager`
section. All settings are clearly labeled and designed for ease of use.

![Admin GTM Section](https://github.com/acid-unit/docs/blob/main/google-tag-manager/admin-section.png?raw=true)

Modifying event structure or data layer output requires code-level changes.
Admin settings allow you to enable/disable specific events,
set custom event names, and define conditions for event triggers—without touching code.

If the event name is set, it will be pushed as `event` property like on the screenshot below.

## Debugging

When debugging is enabled, all objects pushed to the data layer are logged in the browser console.

![Debugging GTM Events](https://github.com/acid-unit/docs/blob/main/google-tag-manager/debug-console.png?raw=true)

## Tracked Events

The module automatically tracks the following **user interactions** and pushes structured
data to the Google Tag Manager data layer:

* [Page Load](#page-load)
    + [PDP](#pdp)
    + [PLP](#plp)
    + [SRP](#srp)
    + [CMS Page](#cms-page)
    + [Include or Exclude Pages](#include-or-exclude-pages)
    + [Custom URLs](#custom-urls)
* [Click](#click)
    + [Product](#product)
    + [Menu Item](#menu-item)
    + [Swatch](#swatch)
* [Checkout Flow](#checkout-flow)
    + [Added to Cart](#added-to-cart)
    + [Removed from Cart](#removed-from-cart)
    + [Cart Item Qty Changed](#cart-item-qty-changed)
    + [Checkout Steps Reached](#checkout-steps-reached)
    + [Purchase Done](#purchase-done)
* [Customer Session](#customer-session)
    + [Logged In](#logged-in)
        - [Failed](#failed)
    + [Logged Out](#logged-out)
    + [Registration](#registration)
        - [Failed](#failed-1)
* [Exposure](#exposure)
    + [Products in List](#products-in-list)
    + [Top Menu Category](#top-menu-category)
    + [Custom Blocks](#custom-blocks)
* [Wishlist](#wishlist)
    + [Product Added](#product-added)
    + [Product Removed](#product-removed)

# GTM Events

Below, event data structure examples will be shown as they are pushed to the data layer.

## Page Load

Page Load events can be triggered for any kind of page. Based on the page type,
different set of data will be pushed to the data layer.

Event structure and data can be modified in `js/model/page-load.js` file

### PDP

```yaml
{
    "event": "pdp_load",
    "ecommerce": {
        "detail": {
            "products": [
                {
                    "id": "1903",
                    "sku": "WP13",
                    "name": "Portia Capri",
                    "category": "Pants",
                    "type": "configurable",
                    "price": 49,

                    // options array will be pushed only for configurable products
                    "options": [

                        // set of configurable product options (simple products)
                        {
                            "id": "1897",
                            "sku": "WP13-28-Blue",
                            "name": "Portia Capri-28-Blue",
                            "price": 49
                        },
                        // ...
                        {
                            "id": "1902",
                            "sku": "WP13-29-Orange",
                            "name": "Portia Capri-29-Orange",
                            "price": 49
                        }
                    ]
                }
            ]
        },
        "currencyCode": "USD"
    }
}
```

### PLP

Example of event data structure that is pushed when PLP is loaded:

```yaml
{
    "event": "plp_load",
    "ecommerce": {

        // array with products that are rendered on current page
        "impressions": [
            {
                "id": "1220",
                "sku": "WJ01",
                "name": "Stellar Solar Jacket",
                "category": "Jackets",
                "type": "configurable",
                "price": 75,

                // options array will be pushed only for configurable products
                "options": [
                    {
                        "id": "1211",
                        "sku": "WJ01-S-Blue",
                        "name": "Stellar Solar Jacket-S-Blue",
                        "price": 75
                    },
                    // ...
                    {
                        "id": "1219",
                        "sku": "WJ01-L-Yellow",
                        "name": "Stellar Solar Jacket-L-Yellow",
                        "price": 75
                    }
                ]
            },
            // ...
            {
                "id": "1381",
                "sku": "WJ12-XS-Black",
                "name": "Olivia 1/4 Zip Light Jacket-XS-Black",
                "category": "Jackets",
                "type": "simple",
                "price": 77
            },
            // ...
            {
                "id": "46",
                "sku": "24-WG085_Group",
                "name": "Set of Sprite Yoga Straps",
                "category": "Fitness Equipment",
                "type": "grouped",

                // 'price' property is replaced with 'min_price' and 'max_price' for grouped and bundle products
                "min_price": 14,
                "max_price": 21
            }
        ],
        "currencyCode": "USD"
    }
}
```

### SRP

Search Results Page has similar event data structure to PLP:

```yaml
{
    "event": "srp_load",
    "ecommerce": {
        "search_query": "jacket",

        // the same array with products as the 'impressions' property has in PLP load event
        "search_result": [
            // ...
        ],
        "currencyCode": "USD"
    }
}
```

### CMS Page

Event data structure example that is pushed to the data layer when CMS Page is loaded:

```yaml
{
    "event": "page_load",

    // user type can be 'new' or 'registered' and will be added to the event data if config is enabled
    "user_type": "new",

    // inner text of current page <h1> tag
    "title": "Home Page",
    "page": "Home Page"
}
```

### Include or Exclude Pages

You can exclude specific pages from triggering GTM events using Magento layout handles.
Add one of the page handles to the `Page Layout Handles List` textarea,
and make sure `List Behavior` is set to `Exclude`:

![Page Handles List Behavior](https://github.com/acid-unit/docs/blob/main/google-tag-manager/page-handles-list-behavior.png?raw=true)

In this case, home page and product category with id `12` won't trigger event on load.

Setting `List Behavior` to `Include` reverses the logic - only
the listed pages will trigger Page Load events.
Meaning, only home page and category with id `12` will trigger Page Load events.

More info regarding layout handles you can find on a
corresponding <a href="https://developer.adobe.com/commerce/frontend-core/guide/layouts/#layout-handles" target="_blank">
Adobe Developer Portal Page</a>

### Custom URLs

Events can be pushed to the data layer for custom pages—those that do not fit
into predefined categories like PDP, PLP, or CMS pages. For example, the `/contact` page:

![Custom Pages Load](https://github.com/acid-unit/docs/blob/main/google-tag-manager/custom-pages-load.png?raw=true)

**Note**: The page URL can be set as part of the URL; it is not mandatory to define the entire URL.

URL matching works on a **substring basis**.
This means that if you define a rule for `/contact`,
it will also match URLs that contain `/contact` within them, such as:

- `/contact`
- `/contact-us`

If you need to track these pages separately,
consider change page URLs to avoid unintentional overlap.

## Click

Event structure and data can be modified in `js/model/page-click.js` file

### Product

This event is triggered when the product is clicked on PLP, SRP or any other product list.
Click event listeners are attached to the following selectors:

- `a.product-item-photo`
- `a.product-item-link`

Event data and structure:

```yaml
{
    "event": "product_clicked",
    "ecommerce": {
        "click": {
            "actionField": {
                // current product list. eg 'upsell', 'related', 'Search Results' etc
                "list": "Category Page"
            },
            "products": [
                {
                    "id": "286",
                    "sku": "MJ02",
                    "name": "Hyperion Elements Jacket",
                    "category": "Jackets",
                    "type": "configurable",
                    "price": 51,

                    // options array will be pushed only for configurable products
                    "options": [
                        {
                            "id": "271",
                            "sku": "MJ02-XS-Green",
                            "name": "Hyperion Elements Jacket-XS-Green",
                            "price": 51
                        },
                        // ...
                        {
                            "id": "285",
                            "sku": "MJ02-XL-Red",
                            "name": "Hyperion Elements Jacket-XL-Red",
                            "price": 51
                        }
                    ]
                }
            ]
        },
        "currencyCode": "USD"
    }
}
```

### Menu Item

```yaml
{
    "event": "menu_item_clicked",
    "ecommerce": {
        "menu_item_click": {
            // menu item title
            "menu_item": "Jackets",

            // breadcrumbs path to the clicked menu item
            "path": "Women > Tops > Jackets"
        }
    }
}
```

### Swatch

```yaml
{
    "event": "swatch_clicked",
    "swatch_label": "Color",
    "option_label": "Orange",

    // true for listing page, false for PDP
    "in_product_list": true,
    "product": {
        "id": "1268",
        "sku": "WJ04",
        "name": "Ingrid Running Jacket",
        "category": "Jackets",
        "type": "configurable",
        "price": 84,
        "options": [
            {
                "id": "1253",
                "sku": "WJ04-XS-Orange",
                "name": "Ingrid Running Jacket-XS-Orange",
                "price": 84
            },
            // ...
            {
                "id": "1267",
                "sku": "WJ04-XL-White",
                "name": "Ingrid Running Jacket-XL-White",
                "price": 84
            }
        ]
    }
}
```

## Checkout Flow

Event structure and data can be modified in `js/model/checkout-flow.js` and `js/model/page-load.js` files

### Added to Cart

```yaml
{
    "event": "product_added_to_cart",
    "ecommerce": {
        "add": {
            "products": [
                {
                    "id": "1236",
                    "sku": "WJ02",
                    "name": "Josie Yoga Jacket",
                    "category": "Jackets",
                    "type": "configurable",
                    "price": 56.25,

                    // options array will be pushed only for configurable products
                    "options": {
                        "size": "XS",
                        "color": "Black"
                    },
                    "qty": 1
                }
            ]
        },
        "currencyCode": "USD"
    }
}
```

### Removed from Cart

```yaml
{
    "event": "product_removed_from_cart",
    "ecommerce": {
        "remove": {
            "products": [
                {
                    "item_id": "219",
                    "name": "Beaumont Summit Kit",
                    "price": 42,
                    "sku": "MJ01-M-Red",
                    "qty": 6,

                    // options array will be pushed only for configurable products
                    "options": {
                        "size": "M",
                        "color": "Red"
                    }
                }
            ]
        },
        "currencyCode": "USD"
    }
}
```

### Cart Item Qty Changed

Event is triggered both when qty is changed in minicart and on cart page.
If multiple product quantities are updated at once (e.g. on cart page),
all affected products will be included in the `products` array.

```yaml
{
    "event": "cart_item_qty_changed",
    "ecommerce": {
        "update": {
            "products": [
                {
                    "id": "1236",
                    "name": "Josie Yoga Jacket",
                    "price": 56.25,
                    "sku": "WJ02-XS-Black",
                    "qty": 4,

                    // options array will be pushed only for configurable products
                    "options": {
                        "size": "XS",
                        "color": "Black"
                    },
                    "old_qty": 2
                }
            ]
        },
        "currencyCode": "USD"
    }
}
```

### Checkout Steps Reached

When shipping step is loaded:

```yaml
{
    "event": "checkout_step",
    "ecommerce": {
        "checkout": {
            "actionField": {
                "step": 1
            }
        },
        "products": [
            {
                "id": "1382",
                "sku": "WJ12-XS-Blue",
                "name": "Olivia 1/4 Zip Light Jacket-XS-Blue",
                "price": 77,
                "qty": 1
            },
            {
                "id": "1236",
                "sku": "WJ02-XS-Black",
                "name": "Josie Yoga Jacket",
                "price": 56.25,
                "qty": 4,
                "options": {
                    "size": "XS",
                    "color": "Black"
                }
            }
        ],
        "currencyCode": "USD"
    }
}
```

When the billing step is loaded, cart product info is not included in the event data:

```yaml
{
    "event": "checkout_step",
    "ecommerce": {
        "checkout": {
            "actionField": {
                "step": 2
            }
        },
        "currencyCode": "USD"
    }
}
```

**Note**: cart products info is not included in the event data for billing step.

### Purchase Done

```yaml
{
    "event": "purchase_done",
    "ecommerce": {
        "purchase": {
            "actionField": {
                "id": "000000006",
                "grand_total": 266.6,
                "shipping_amount": 25,
                "discount": -60.4
            },
            "products": [
                {
                    "id": "1382",
                    "sku": "WJ12-XS-Blue",
                    "name": "Olivia 1/4 Zip Light Jacket-XS-Blue",
                    "category": "Jackets",
                    "type": "simple",
                    "price": 77,
                    "qty": 1
                },
                {
                    "id": "1236",
                    "sku": "WJ02",
                    "name": "Josie Yoga Jacket",
                    "category": "Jackets",
                    "type": "configurable",
                    "price": 56.25,
                    "options": {
                        "size": "XS",
                        "color": "Black"
                    },
                    "qty": 4
                }
            ]
        },
        "currencyCode": "USD"
    }
}
```

## Customer Session

Customer session events send the event name, Customer ID, or error message to the data layer.

Event structure and data can be modified in `js/handler/page-data.js` file

### Logged In

```yaml
{
    "event": "login",
    "customerId": "2"
}
```

#### Failed

```yaml
{
    "event": "failed_login",

    // error message displayed to the customer
    "message": "The account sign-in was incorrect or your account is disabled temporarily. Please wait and try again later."
}
```

### Logged Out

```yaml
{
    "event": "logout"
}
```

### Registration

```yaml
{
    "event": "registration",
    "customerId": "4"
}
```

#### Failed

```yaml
{
    "event": "failed_registration",

    // error message displayed to the customer
    "message": "There is already an account with this email address."
}
```

## Exposure

Exposure events are pushed to the data layer when the blocks get visible on the screen.
When there are multiple blocks of the same type, they are pushed as elements of array.

When the block reveals on the screen, a timeout (default is 100ms) is triggered,
which will clear if the same type of block gets visible. This allows to combine the same type
of exposure blocks into a single event if the customer scrolls the page rapidly.

Event structure and data can be modified in `js/handler/exposure.js` file

### Products in List

```yaml
{
    "event": "product_exposure",
    "page": "Category Page",
    "ecommerce": {
        "exposure": [
            {
                "id": "1382",
                "sku": "WJ12-XS-Blue",
                "name": "Olivia 1/4 Zip Light Jacket-XS-Blue",
                "category": "Jackets",
                "type": "simple",
                "price": 77
            },
            {
                "id": "1396",
                "sku": "WJ12",
                "name": "Olivia 1/4 Zip Light Jacket",
                "category": "Jackets",
                "type": "configurable",
                "price": 77,
                "options": [
                    {
                        "id": "1381",
                        "sku": "WJ12-XS-Black",
                        "name": "Olivia 1/4 Zip Light Jacket-XS-Black",
                        "price": 77
                    },
                    // ...
                    {
                        "id": "1395",
                        "sku": "WJ12-XL-Purple",
                        "name": "Olivia 1/4 Zip Light Jacket-XL-Purple",
                        "price": 77
                    }
                ],

                // current product list. eg 'upsell', 'related', 'Search Results' etc
                "list": "Category Page"
            }
        ],
        "currencyCode": "USD"
    }
}
```

### Top Menu Category

```yaml
{
    "event": "menu_category_exposure",
    "ecommerce": {
        "menu_hover": {
            // menu item title
            "name": "Tops",

            // breadcrumbs path to the hovered item
            "path": "Women > Tops"
        }
    }
}
```

### Custom Blocks

Here you can set any block to trigger exposure event by using an HTML selector

![Exposure Custom Blocks](https://github.com/acid-unit/docs/blob/main/google-tag-manager/exposure-custom-blocks.png?raw=true)

```yaml
{
    "event": "block_exposure",
    "ecommerce": {
        "blockView": [
            {
                // block name as defined in the config
                "name": "ECO",
                "page": "Home Page"
            }
        ],
        "currencyCode": "USD"
    }
}
```

## Wishlist

Event structure and data can be modified in `js/handler/page-data.js` file

### Product Added

Event data and structure:

```yaml
{
    "event": "added_to_wishlist",
    "products": [
        {
            "id": "1396",
            "sku": "WJ12",
            "name": "Olivia 1/4 Zip Light Jacket",
            "category": "Jackets",
            "type": "configurable",
            "price": 77,

            // options will be pushed only for configurable products
            "options": [
                {
                    "id": "1381",
                    "sku": "WJ12-XS-Black",
                    "name": "Olivia 1/4 Zip Light Jacket-XS-Black",
                    "price": 77
                },
                // ...
                {
                    "id": "1395",
                    "sku": "WJ12-XL-Purple",
                    "name": "Olivia 1/4 Zip Light Jacket-XL-Purple",
                    "price": 77
                }
            ]
        }
    ]
}
```

### Product Removed

The event structure is identical to the "Product Added to Wishlist" event,
but with a different event name:

```yaml
{
    "event": "removed_from_wishlist",

    // the same as for adding product to wishlist
    "products": [
        // ...
    ]
}
```

# Full Setup Reference

Complete setup process:

```shell
composer require acid-unit/module-google-tag-manager
bin/magento module:enable AcidUnit_GoogleTagManager
bin/magento setup:upgrade
bin/magento setup:di:compile
bin/magento cache:flush
```

# Requirements

✅ **Compatible with**: Magento Open Source & Adobe Commerce `>=2.4.4`  
✅ Requires `PHP 8.1+`

<small>🛠 **Tested on** Magento Open Source `2.4.7-p3` with `PHP 8.3`</small>
