# About

Google Tag Manager extension for Adobe Commerce.

Events summary table:

* [Page Load](#page-load)
    + [PDP](#pdp)
    + [PLP](#plp)
    + [SRP](#srp)
    + [CMS Page](#cms-page)
    + [Include / Exclude Pages](#include---exclude-pages)
    + [User type](#user-type)
    + [Custom URLs](#custom-urls)
* [Click](#click)
    + [Product](#product)
    + [Menu Item](#menu-item)
    + [Swatch](#swatch)
* [Checkout Flow](#checkout-flow)
    + [Added to Cart](#added-to-cart)
    + [Removed from Cart](#removed-from-cart)
    + [Cart item Qty Changed](#cart-item-qty-changed)
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

# General Info

Module configuration is done under the 
`Stores > Settings > Configuration > Sales > Google API > Google Tag Manager [Acid Unit]`
section. All sections, fields and options are plain and self-described.

![Admin GTM Section](https://github.com/acid-unit/docs/blob/main/google-tag-manager/admin-section.png?raw=true)

If you need to modify event structure or data that is pushed to data layer,
this should be done via the code. Admin configuration provides toggling events, setting event names
and conditions for separate events. 

If the event name is set, it will be pushed as `event` property like on the screenshot below.

## Debugging

When debug option is enabled, every time the object is pushed to data layer, it will be
displayed to the console as well:

![Debugging GTM Events](https://github.com/acid-unit/docs/blob/main/google-tag-manager/debug-console.png?raw=true)

# GTM Events

Below, event data structure examples will be shows as they are pushed to the data layer.

## Page Load

Page load events can be triggered for any kind of page. Based on the page type,
different set of data will be pushed to data layer.

Event structure and data can be modified in `js/model/page-load.js` file

### PDP

```json
{
    "event": "pdp_load", // as defined in config
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
        }
    }
}
```

### PLP

Example of event data structure that is pushed when PLP is loaded:

```json
{
    "event": "plp_load", // as defined in config
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
        ]
    }
}
```

### SRP

Search Results Page has similar event data structure to PLP:

```json
{
    "event": "srp_load", // as defined in config
    "ecommerce": {
        "search_query": "jacket",
        
        // the same array with products as the 'impressions' property has in PLP load event
        "search_result": [
            // ...
        ]
    }
}
```

### CMS Page

Event data structure example that is pushed to data layer when CMS Page is loaded:

```json
{
    "event": "page_load", // as defined in config
    "user_type": "new", // user type can be 'new' or 'registerd'. Will be added to the object if admin config value is enabled
    "title": "Home Page", // inner text of page <h1> tag
    "page_type": "Home Page" // will be added to the object if page handle matches one of the predefined. List of predefined handles is set in 'js/model/page-handle.js' file
}
```

### Include / Exclude Pages

You can exclude pages from trigger GTM events on load. Add one of the page handles
to the `Page Layout Handles List` textarea, and make sure `List Behavior` is set to `Exclude`:

![Page Handles List Behavior](https://github.com/acid-unit/docs/blob/main/google-tag-manager/page-handles-list-behavior.png?raw=true)

In this case, home page and product category with id `12` won't trigger event on load.

If you change `List Behavior` to `Include`, the list behavior will be reversed. 
Only home page and category with id `12` will trigger page load events.

### Custom URLs

Events can be pushed to the data layer when custom pages are loaded. Custom pages are those, 
that do not fall under either of the previous categories. For example, `/contact` page:

![Custom Pages Load](https://github.com/acid-unit/docs/blob/main/google-tag-manager/custom-pages-load.png?raw=true)

**Note**: Page URL here can be set as a part of URL, it is not mandatory to set entire URL.
Also, there is a specific limitation - if you have, for instance, two pages with the following URLs:
 
- `/contact`
- `/contact-us`

The event will be triggered on both of them, because both of URLs contain `/contact` substring. 

## Click

Event structure and data can be modified in `js/model/page-click.js` file

### Product

This event is triggered when the product is clicked on PLP, SRP or any other product list.
Under the hood, click event listener is attached to the following selectors:

- `a.product-item-photo`
- `a.product-item-link`

Event data and structure:

```json
{
    "event": "product_clicked", // as defined in config
    "ecommerce": {
        "click": {
            "actionField": {
                "list": "Category Page" // current product list. eg 'upsell', 'related', 'Search Results' etc
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
        }
    }
}
```

### Menu Item

```json
{
    "event": "menu_item_clicked", // as defined in config
    "ecommerce": {
        "click": {
            "menuItem": "Jackets", // menu item title
            "path": "Women > Tops > Jackets" // breadcrumbs path to the clicked menu item
        }
    }
}
```

### Swatch

```json
{
    "event": "swatch_clicked", // as defined in config
    "swatchLabel": "Color",
    "optionLabel": "Orange",
    "inProductList": true, // 'true for listing page, false for PDP
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

```json
{
    "event": "product_added_to_cart", // as defined in config
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
                    "qty": "1"
                }
            ]
        }
    }
}
```

### Removed from Cart

```json
{
    "event": "product_removed_from_cart", // as defined in config
    "ecommerce": {
        "remove": {
            "products": [
                {
                    "item_id": "219",
                    "name": "Beaumont Summit Kit",
                    "price": 42,
                    "sku": "MJ01-M-Red",
                    "qty": "6",

                    // options array will be pushed only for configurable products
                    "options": {
                        "size": "M",
                        "color": "Red"
                    }
                }
            ]
        }
    }
}
```

### Cart item Qty Changed

```json
{
    "event": "cart_item_qty_changed", // as defined in config
    "ecommerce": {
        "update": {
            "products": [
                {
                    "id": "1236",
                    "name": "Josie Yoga Jacket",
                    "price": 56.25,
                    "sku": "WJ02-XS-Black",
                    "qty": "4",

                    // options array will be pushed only for configurable products
                    "options": {
                        "size": "XS",
                        "color": "Black"
                    },
                    "old_qty": "2"
                }
            ]
        }
    }
}
```

### Checkout Steps Reached

Shipping step:

```json
{
    "event": "checkout_step", // as defined in config
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
                "qty": "1"
            },
            {
                "id": "1236",
                "sku": "WJ02-XS-Black",
                "name": "Josie Yoga Jacket",
                "price": 56.25,
                "qty": "4",
                "options": {
                    "size": "XS",
                    "color": "Black"
                }
            }
        ]
    }
}
```

Billing step:

```json
{
    "event": "checkout_step", // as defined in config
    "ecommerce": {
        "checkout": {
            "actionField": {
                "step": 2
            }
        }
    }
}
```

**Note**: cart products info is not included in the event data for billing step.

### Purchase Done

```json
{
    "event": "purchase_done", // as defined in config
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
                    "qty": "1"
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
                    "qty": "4"
                }
            ]
        }
    }
}
```

## Customer Session

For customer session events, event name and Customer ID or Error Message is sent to data layer.

Event structure and data can be modified in `js/handler/page-data.js` file

### Logged In

```json
{
    "event": "login", // as defined in config
    "customerId": "2"
}
```

#### Failed

```json
{
    "event": "failed_login", // as defined in config
    "message": "The account sign-in was incorrect or your account is disabled temporarily. Please wait and try again later."
}
```

### Logged Out

```json
{
    "event": "logout" // as defined in config
}
```

### Registration

```json
{
    "event": "registration", // as defined in config
    "customerId": "4"
}
```

#### Failed

```json
{
    "event": "failed_registration", // as defined in config
    "message": "There is already an account with this email address."
}
```

## Exposure

Exposure events are pushed to data layer when the blocks get visible on the screen.
When there are multiple blocks of the same type, they are pushed as elements of array.

When the block reveals on the screen, a timeout (default is 100ms) is triggered, 
which will clear if the same type of block gets visible. This allows to combine the same type
of exposure blocks into a single event if the customer scrolls the page rapidly.

Event structure and data can be modified in `js/handler/exposure.js` file

### Products in List

```json
{
    "event": "product_exposure", // as defined in config
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
                "list": "Category Page" // current product list. eg 'upsell', 'related', 'Search Results' etc
            }
        ]
    }
}
```

### Top Menu Category

```json
{
    "event": "menu_category_exposure", // as defined in config
    "ecommerce": {
        "menu_hover": {
            "name": "Tops",
            "path": "Women > Tops"
        }
    }
}
```

### Custom Blocks

Here you can set any block to trigger exposure event by using a HTML selector  

![Exposure Custom Blocks](https://github.com/acid-unit/docs/blob/main/google-tag-manager/exposure-custom-blocks.png?raw=true)

```json
{
    "event": "block_exposure", // as defined in config
    "ecommerce": {
        "blockView": [
            {
                "name": "ECO", // block name as defined in the config
                "page": "Home Page" // current page
            }
        ]
    }
}
```

## Wishlist

Event structure and data can be modified in `js/handler/page-data.js` file

### Product Added

Event data and structure:

```json
{
    "event": "added_to_wishlist", // as defined in config
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

Event structure is the same as for adding product to wishlist, but with the different
event name:

```json
{
    "event": "removed_from_wishlist", // as defined in config

    // the same as for adding product to wishlist
    "products": [
        // ...
    ]
}
```

# Installation

`composer require acid-unit/module-google-tag-manager`

# Requirements

- `Adobe Commerce 2.4.4` or newer
- `PHP 8.1` or newer

<small>Tested on Adobe Commerce 2.4.7-p3 with PHP 8.3</small>
