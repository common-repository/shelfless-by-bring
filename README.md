# Shelfless by Bring #
**Contributors:** [shelflessbybring](https://profiles.wordpress.org/shelflessbybring), [averysphere](https://profiles.wordpress.org/averysphere)  
**Tags:** woocommerce, mybring, inventory, warehouse, warehouse management, shipping, labels, fulfillment, shelfless  
**Requires at least:** 5.6.1  
**Tested up to:** 6.1  
**Requires PHP:** 7.4  
**Stable tag:** 1.3.0  
**License:** GPLv2 or later  
**License URI:** https://www.gnu.org/licenses/gpl-2.0.html  

Shelfless by Bring plugin integrates Bring's Mybring and Shelfless solutions with your WooCommerce store.

## Description ##

Shelfless by Bring for WooCommerce plugin integrates Bring's Mybring and Shelfless solutions to WooComerce. Shelfless by Bring for WooComerce enables the integration of your WooCommerce store to Bring's Mybring and Shelfless services, wherein you will have the ability to fulfill and ship the orders through Bring's warehouses straight from your WooCommerce store's administrative interface.

This plugin needs an account from Bring, such as Mybring Customer ID and API Key, in order to work. You may get these details from your MyBring account or from a Bring executive handling your account. Additionally, a Shelfless API key has to be set up for you by your account executive. If you have no Shelfless account yet, you may reach us through <a href="https://www.bring.com/customer-service">Bring Customer Service</a> and we will be glad to process it for you.

This plugin will use two Bring APIs:

- <a href="https://www.bring.no/radgivning/netthandel/shelfless">Shelfless by Bring Fulfillment Services APIs</a> - the Shelfless API services used exclusively by Bring's developers in creating official Bring solutions such as Shelfless by Bring for WooComerce.
- <a href="https://developer.bring.com/">Bring Integration APIs, such as the Shipping Guide API</a> - the general integrations APIs used by third-party developers for integrating with ecommerce sites and logistics software.

## Features ##

- Works with major shipping and checkout plugins available, but does not interfere with their respective functions
- Works with WooCommerce's free shipping and flat rate tables
- Ability to select which order statuses to process fulfillment
- Includes detection of specific custom statuses created by other third-party extensions and allows them to be the selector for fulfillment
- Includes the ability to move to certain statuses (usually Complete) when fulfillment has been completed and orders are shipped
- Includes the integration of tracking numbers directly to WooCommerce’s orders' notes
- When orders are canceled or cannot be fulfilled at the warehouse, the conditions and statuses will be transmitted back to the WooCommerce store so merchants are aware
- Has a set of Shelfless-only custom statuses that can be enabled by merchants if they want to identify orders that have been processed by Shelfess by Bring
- Automatically fetches stock counts and inventory from a Shelfless warehouse, and updates the local inventory
- Allows for total control on product and order fulfillment: merchants can choose which products are allowed to be shipped by Shelfless, and products to be updated with Shelfless inventory
- Products created on WooCommerce can be automatically created and pushed to the Shelfless warehouse, as well as product information updates such as product names and descriptions
- Uses standard SKU naming conventions: WooCommerce SKUs are the same as the Shelfless SKUs

## Installation ##

This section describes how to install the plugin and get it working.

1. Upload the plugin to the to the `/wp-content/plugins/` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Install and activate WooCommerce if you haven't already done so, as this is a requirement for this plugin.

## Frequently Asked Questions ##

### Do I need a Mybring Customer ID? ###
Yes, you need a Mybring Customer ID, as well as API Key and API Secret Key. You may get these details from your Mybring account or from a Bring account executive handling your account.

### Do I need a Shelfless by Bring contract to use this plugin? ###
Yes, you need to have an approved contract with Shelfless by Bring to start using this plugin. Initially, your Bring account executive will arrange this for you, as well as assist you in sending your product inventory to Shelfless warehouses before you start installing this plugin.

### What are the terms of service enforced by the Shelfless by Bring? ###
A complete terms of service will be given to your by your Bring and/or Shelfless account executive. You can also find various terms from here: <a href="https://www.bring.no/en/terms-and-conditions">https://www.bring.no/en/terms-and-conditions</a>. If you want to talk to Bring customer service, please visit <a href="https://www.bring.com/customer-service">https://www.bring.com/customer-service</a>.

### What is the Shelfless by Bring fulfillment service? ###
Shelfless by Bring is Posten Norge AS's largest investment in efficient and green logistics services for all online stores. Shelfless take care of storage, pick, pack and send your products so you can focus on growing your business. With Shelfless, you give your customers the best customer experience. We take care of your entire logistics flow, from receipt to delivery and returns. With seamless integration between your WooCommerce store and our systems, we help your online store to grow.

You get

- Access to automated warehouses and return hubs throughout the Nordic region
- Flexible, fast and green deliveries with short lead times
- Same day deliveries
- Seamless integration with our proprietary WMS (Warehouse Management System)
- Real-time tracking

As a Shelfless customer, your online store gets access to the market's fastest lead times with high cost efficiency. You sell and we pick, pack and deliver products for an outstanding customer experience. It gives you economies of scale and you can focus on growth. The warehouse becomes your own, which you easily have an overview of via our customer portal.

Regardless of the size of your online store, you can grow with us. We handle online stores from start-ups to billion-dollar companies. In other words, online stores from 1 order per day to over 15,000 orders per day.

### What are included with the Shelfless by Bring fulfillment service? ###
Here are some of the benefits that Shelfless customers get:

- Goods receipt of goods, storage, picking and packing
- Return handling
- Order deadline until 21.00
- Direct integration with your WooCommerce store
- Cloud-based information flow and access to the Nordic region's largest marketplaces
- Distribution services
- Customer portal, including purchase order handling 
- Return portal

## Changelog ##

### v1.3.0 ###
- Added more refined control on Bring's same-day delivery service for Shelfless Delivery based on pre-determined delivery/sorting areas
- Retained settings during plugin deactivation and/or plugin updates
- Support for per zone Same Day Delivery for Shelfless Delivery in Norway
- Support for multisite (Wordpress Network) setup
- Support for single setup within multisite
- Additional default settings for Shelfless

### v1.2.9 ###
- Suppressed the is_express check for Bring services when Dream is turned on

### v1.2.8 ###
- Add DreamPack shipping service to Shipping Mapping
- Removed the service title toggle on Shelfless Delivery
- Removed the service price toggle on Shelfless Delivery
- Updated user guide and FAQ document

### v1.2.7 ###
- Fixed non-display of Shelfless Delivery `home delivery` and `same-day home delivery` services due to schema changes in Bring Shipping Guide API

### v1.2.6 ###
- Integration of Dream Logistics services with Shelfless
- UI streamlining
- Detecting pickup point agents when using nShift third-party plugin and Dream Logistics
- Integration and support of nShift-based services using the Media Strategi/Oktagon nShift plugin
- Inclusion of FAQ and user guide documents within the Shelfless plugin
- Addition of Shelfless Delivery shipping services feature for providing Bring-specific shipping products upon checkout
- Addition of Bring pickup points on cart page and checkout page
- Reinstatement of same-day options for flat-rate shipping rates created via WooCommerce shipping settings page
- Additional same-day shipping options for Shelfless Delivery (Pakke levert hjem - samme dag)
- Sorting of shipping methods in ascending order based on cost

### v1.2.5 ###
- Integration of Dream Logistics services to Shelfless

### v1.2.4 ###
- Fixed issues with uninstalling the plugin

### v1.2.3 ###
- Changed plugin name to `Shelfless by Bring`
- Standardized functionalities for supporting PHP 8.0+
- Added support for WooCommerce 6.0+
- Added more details on what the plugin can do
- Updated the contributors list, plugin URI and plugin author

### v1.2.2 ###
- Addition of UTF-8 encoding to article fields
- Fixes to article data update
- Fixes to incorrect order line item numbers when transmitting to the warehouse
- Implementation of the order information link to Mybring
- Standardizing Shelfless status indicators with that of Mybring in the Mybring information
- Overhauled processes for order cancellation and order update

### v1.2.1 ###
- Redefined update and cancellation requests
- Addition of message handlers to new endpoint responses
- Addition of new status handling orders that could not be fulfilled at warehouse due to stock unavailability
- Fully functional plugin-based article creation
- Updated inventory synchronization
- Addition of shipping mapping for free shipping method
- Ability to add more default VAS codes
- Fixed Bootstrap library overriding platform CSS
- Addition of `is_shippable` flag
- Addition of response caching from endpoints
- Fixed order message spamming
- Addition of robust per-page parsing of article master data responses
- Addition of ability to use custom order number as needed as invoice numbers sent to warehouse
