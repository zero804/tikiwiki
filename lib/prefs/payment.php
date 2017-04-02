<?php
// (c) Copyright 2002-2016 by authors of the Tiki Wiki CMS Groupware Project
// 
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

function prefs_payment_list()
{
	return array(
		'payment_feature' => array(
			'name' => tra('Payment'),
			'description' => tra('Feature for tracking and managing payment requests.'),
			'type' => 'flag',
			'help' => 'Payment',
			'default' => 'n',
			'admin' => 'payment',
			'view' => 'tiki-payment.php',
			'keywords' => 'shopping',
		),
		'payment_system' => array(
			'name' => tra('Payment system'),
			'description' => tra('Currently a choice between PayPal, and Cclite (in development), or Tiki User Credits.'),
			'hint' => tra('PayPal: see PayPal.com - Cclite: Community currency'),
			'type' => 'list',
			'options' => array(
				'paypal' => tra('PayPal'),
				'israelpost' => tra('Israel Post Payment Module'),
				'cclite' => tra('Cclite'),
				'tikicredits' => tra('Tiki User Credits'),
			),
			'dependencies' => array( 'payment_feature' ),
			'default' => 'paypal',
		),
		'payment_paypal_business' => array(
			'name' => tra('Paypal ID'),
			'description' => tra('Enable payments through PayPal.') . ' ' . tra('This account can be either Personal, Business Standard, or Business Pro. A Personal account allows to receive small payments from shopping carts. A Business Standard account allows also to receive periodic payments through Plugin GroupMembership or receive bigger amounts. And a Business Pro account allows further customizations. See paypal.com for more information and exact fees depending on your country and business type.'),
			'hint' => tra('Primary email of your PayPal account') . ' ' . tra('(Personal or Business).'),
			'type' => 'text',
			'filter' => 'email',
			'dependencies' => array( 'payment_feature' ),
			'size' => 50,
			'default' => '',
		),

		'payment_paypal_password' => array(
			'name' => tra('Paypal business password') . ' ' . tr('(*)'),
			'description' => tra('The PayPal password associated with your PayPal Pro business account') . '. ' . tra('(*) This is NOT needed for Paypal Personal or Paypal Standard Business accounts.'),
			'hint' => tra('Primary password of your PayPal Pro business account') . ' ' . tr('(*) OPTIONAL'),
			'type' => 'password',
			'filter' => 'text',
			'dependencies' => array( 'payment_feature' ),
			'size' => 50,
			'default' => '',
			''
		),

		'payment_paypal_signature' => array(
			'name' => tra('Paypal business signature'). ' ' . tr('(*)'),
			'description' => tra('Your API signature associated with your PayPal Pro business account') . '. ' . tra('(*) This is NOT needed for Paypal Personal or Paypal Standard Business accounts.'),
			'hint' => tra('Your API signature associated with your PayPal Pro business account') . ' ' . tr('(*) OPTIONAL'),
			'type' => 'text',
			'filter' => 'text',
			'dependencies' => array( 'payment_feature' ),
			'size' => 50,
			'default' => '',
		),
		'payment_paypal_environment' => array(
			'name' => tra('PayPal environment'),
			'description' => tra('Used to switch between the PayPal sandbox, used for testing and development and the live environment.'),
			'type' => 'list',
			'options' => array(
				'https://www.paypal.com/cgi-bin/webscr' => tra('Production'),
				'https://www.sandbox.paypal.com/cgi-bin/webscr' => tra('Sandbox'),
			),
			'dependencies' => array( 'payment_paypal_business' ),
			'default' => 'https://www.paypal.com/cgi-bin/webscr',
		),
		'payment_paypal_ipn' => array(
			'name' => tra('PayPal instant payment notification (IPN)'),
			'description' => tra('Enable IPN for automatic payment completion. When enabled, PayPal will ping back the site when a payment is confirmed. The payment will then be entered automatically. This may not be possible if the site is not on a public server.'),
			'type' => 'flag',
			'dependencies' => array( 'payment_paypal_business' ),
			'default' => 'y',
		),
		'payment_currency' => array(
			'name' => tra('Currency'),
			'description' => tra('Currency used when entering payments.'),
			'type' => 'text',
			'size' => 3,
			'filter' => 'alpha',
			'default' => 'USD',
		),
		'payment_default_delay' => array(
			'name' => tra('Default acceptable payment delay'),
			'description' => tra('Number of days before the payment requests becomes overdue. This can be changed per payment request.'),
			'shorthint' => tra('days'),
			'type' => 'text',
			'filter' => 'digits',
			'size' => 3,
			'default' => 30,
		),
		'payment_cclite_registries' => array(
			'name' => tra('Cclite registries'),
			'description' => tra('Registries in Cclite.'),
			'hint' => tra('Registry names in Cclite'),
			'type' => 'text',
			'dependencies' => array( 'payment_feature' ),
			'size' => 40,
			'separator' => ',',
			'default' => '',
		),
		'payment_cclite_currencies' => array(
			'name' => tra('Cclite registry currencies'),
			'description' => tra('Currencies in Cclite.'),
			'hint' => tra('Each registry in Cclite can have its own currency. Must be one per registry. (case sensitive)'),
			'type' => 'text',
			'dependencies' => array( 'payment_feature' ),
			'size' => 40,
			'separator' => ',',
			'default' => '',
		),
		'payment_cclite_gateway' => array(
			'name' => tra('Cclite server URL'),
			'description' => tra('Full URL of the repository.'),
			'shorthint' => tra('for example, https://cclite.yourdomain.org/cclite/'),
			'type' => 'text',
			'size' => 60,
		'dependencies' => array( 'payment_cclite_registries' ),
			'default' => '',
		),
		'payment_cclite_merchant_key' => array(
			'name' => tra('Cclite merchant key'),
			'description' => tra('Corresponds with merchant key setting in Cclite'),
			'type' => 'text',
			'dependencies' => array( 'payment_cclite_registries' ),
			'default' => '',
		),
		'payment_cclite_merchant_user' => array(
			'name' => tra('Cclite merchant user'),
			'description' => tra('Username in Cclite representing "the management". Defaults to "manager"'),
			'type' => 'text',
			'dependencies' => array( 'payment_cclite_registries' ),
			'default' => 'manager',
		),
		'payment_cclite_mode' => array(
			'name' => tra('Cclite enable payments'),
			'description' => tra('Test or Live operation'),
			'type' => 'list',
			'options' => array(
				'live' => tra('Live'),
				'test' => tra('Test'),
			),
			'dependencies' => array( 'payment_cclite_registries' ),
			'default' => 'test',
		),
		'payment_cclite_hashing_algorithm' => array(
			'name' => tra('Hashing algorithm'),
			'description' => tra('Encryption type'),
			'type' => 'list',
			'options' => array(
				'sha1' => tra('SHA1'),
				'sha256' => tra('SHA256'),
				'sha512' => tra('SHA512'),
			),
			'dependencies' => array( 'payment_cclite_registries' ),
			'default' => 'sha1',
		),
		'payment_cclite_notify' => array(
			'name' => tra('Cclite payment notification'),
			'description' => tra('To do'),
			'type' => 'flag',
			'dependencies' => array( 'payment_cclite_registries' ),
			'default' => 'y',
		),
		'payment_manual' => array(
			'name' => tra('Wiki page with manual payment instructions'),
			'description' => tra('Enter wiki page name'),
			'type' => 'text',
			'dependencies' => array( 'payment_feature' ),
			'default' => '',
		),
		'payment_invoice_prefix' => array(
			'name' => tra('Invoice prefix'),
			'description' => tra('The prefix must be set and unique if the same PayPal account is used for different Tiki sites, because PayPal checks that the invoice is not paid twice'),
			'type' => 'text',
			'dependencies' => array( 'payment_feature' ),
			'default' => '',
		),
		'payment_tikicredits_types' => array(
			'name' => tra('Types of credit to use'),
			'description' => tra('This is a list of the types of Tiki user credits that are acceptable for payment.'),
			'type' => 'text',
			'dependencies' => array( 'payment_feature', 'feature_credits' ),
			'separator' => ',',
			'default' => '',
		),
		'payment_tikicredits_xcrates' => array(
			'name' => tra('Exchange rate for types of credit to use'),
			'description' => tra('This is a corresponding list of the number of credits equivalent to 1 unit of the payment currency.'),
			'type' => 'text',
			'dependencies' => array( 'payment_feature', 'feature_credits' ),
			'separator' => ',',
			'default' => '',
		),
		'payment_user_only_his_own' => array(
			'name' => tra('User can only see own outstanding payments'),
			'description' => tra('Unless the user has administer-payment permissions, a user can only see his or her own outstanding payments'),
			'type' => 'flag', 
			'default' => 'n',
		),
		'payment_user_only_his_own_past' => array(
			'name' => tra('User can only see own past or cancelled payments'),
			'description' => tra('Unless the user has administer-payment permissions, the user can only see his or her own past or cancelled payments'),
			'type' => 'flag',
			'default' => 'n',
		),
		'payment_anonymous_allowed' => array(
			'name' => tra('Anonymous users can pay outstanding invoices'),
			'description' => tra('Anonymous users can see outstanding invoices to pay them through a simple shopping cart (without the need to setup the advanced cart)'),
			'type' => 'flag',
			'default' => 'n',
		),
		'payment_cart_inventory' => array(
			'name' => tra('Manage product inventory'),
			'description' => tra('Activate product inventory feature. This requires a products tracker to be set up properly, and the item ID must be the product code'),
			'type' => 'flag',
			'dependencies' => array( 'payment_cart_product_tracker', 'payment_cart_inventory_type_field', 'payment_cart_inventory_total_field', 'payment_cart_inventory_lesshold_field' ),
			'default' => 'n',
		),
		'payment_cart_product_tracker' => array(
			'name' => tra('Products tracker ID'),
			'description' => tra('ID of the tracker that is the products tracker, which is needed for advanced cart features; the item ID will be the product code'),
			'type' => 'text',
			'filter' => 'digits',
			'size' => 3,
			'default' => '',
			'profile_reference' => 'tracker',
		),
		'payment_cart_product_tracker_name' => array(
			'name' => tra('Products tracker name'),
			'description' => tra('Name of tracker that is the products tracker, needed for advanced cart features, the itemId will be the product code'),
			'type' => 'text',
			'default' => '',
		),
		'payment_cart_inventory_type_field' => array(
			'name' => tra('Inventory type field ID'),
			'description' => tra('In the products tracker, the ID of the field to store the inventory type; the value of the field must be "none" or "internal"'),
			'type' => 'text',
			'filter' => 'digits',
			'size' => 3,
			'default' => 0,
		),
		'payment_cart_inventory_total_field' => array(
			'name' => tra('Inventory total field ID'),
			'description' => tra('In the products tracker, the ID of the field to store the total inventory of product'),
			'type' => 'text',
			'filter' => 'digits',
			'size' => 3,
			'default' => '',
		),
		'payment_cart_inventory_lesshold_field' => array(
			'name' => tra('Inventory total less hold field ID'),
			'description' => tra('In the products tracker, the ID of the field to store the total inventory of product less the amount on hold because they are currently in carts'),
			'type' => 'text',
			'filter' => 'digits',
			'size' => 3,		
			'default' => '',
		),
		'payment_cart_inventoryhold_expiry' => array(
			'name' => tra('Inventory hold timeout'),
			'description' => tra('Minutes to hold inventory before making it available again when there is no user cart action'),
			'type' => 'text',
			'filter' => 'digits',
			'size' => 3,
			'default' => 15,
		),
		'payment_cart_bundles' => array(
			'name' => tra('Bundled products feature for cart'),
			'description' => tra('Activate bundled products feature. This requires a products tracker to be set up properly, and the item ID must be the product code'),
			'type' => 'flag',
			'dependencies' => array( 'payment_cart_product_name_fieldname', 'payment_cart_products_inbundle_fieldname', 'payment_cart_product_price_fieldname', 'payment_cart_orders_tracker_name', 'payment_cart_orderitems_tracker_name' ),
			'default' => 'n',
		),
		'payment_cart_product_name_fieldname' => array(
			'name' => tra('Product name field name'),
			'description' => tra('In the products tracker, the name of the field for the product name that will be used as the label of the product, for example in a bundle.'),
			'type' => 'text',
			'default' => '',
		),
		'payment_cart_products_inbundle_fieldname' => array(
			'name' => tra('Items in bundle field name'),
			'description' => tra('In the products tracker, the name of the field for a comma-separated list of product IDs of products in the bundle (that is, if the field contains anything, then this product is a bundle). You can also specify the number of the sub-products; for example, 23:("colon")2,24 means item 23 (x2) + item 24 (x1)'),
			'type' => 'text',
			'default' => '',
		),
		'payment_cart_product_price_fieldname' => array(
			'name' => tra('Product price field name'),
			'description' => tra('In the products tracker, the name of the field for the product price.'),
			'type' => 'text',
			'default' => '',
		),
		'payment_cart_associated_event_fieldname' => array(
			'name' => tra('Associated event ID field name'),
			'description' => tra('In the products tracker, the field name for the associated event ID. This is needed for the associated events cart feature, and requires an events tracker to be set up in which the item ID there is the event ID to associate with'),
			'type' => 'text',
			'default' => '',
		),
		'payment_cart_product_classid_fieldname' => array(
			'name' => tra('Product class ID field name'),
			'description' => tra('In the products tracker, the name of the field for the product class ID, needed for the gift certificates cart feature.'),
			'type' => 'text',
			'default' => '',
		),
		'payment_cart_giftcerttemplate_fieldname' => array(
			'name' => tra('Gift certificate template field name'),
			'description' => tra('In the products tracker, the name of the field for the gift certificate template, needed for the gift certificates cart feature.'),
			'type' => 'text',
			'default' => '',
		),
		'payment_cart_orders' => array(
			'name' => tra('Record cart orders in trackers'),
			'description' => tra('This feature applies to registered users and requires two trackers to be created and configured, an orders tracker and an orders item tracker. Profiles also need to be configured, to do the recording.'),
			'type' => 'flag',
			'dependencies' => array( 'payment_cart_orders_profile', 'payment_cart_orderitems_profile' ),
			'default' => '',
		),
		'payment_cart_orders_profile' => array(
			'name' => tra('Orders profile'),
			'description' => tra('Wiki page containing the profile for creating orders (page name must not have spaces)'),
			'help' => 'OrderProfile',
			'type' => 'text',
			'default' => '',
		),
		'payment_cart_orderitems_profile' => array(
			'name' => tra('Order item profile'),
			'description' => tra('Wiki page containing the profile for creating orders items (page name must not have spaces)'),
			'help' => 'OrderItemProfile',
			'type' => 'text',
			'default' => '',
		),
		'payment_cart_anonymous' => array(
			'name' => tra('Allow anonymous shopping'),
			'description' => tra('Allows shopping by anonymous users and record the orders in trackers'),
			'help' => 'Shopping Cart',
			'dependencies' => array( 'auth_token_access', 'payment_cart_anonshopper_profile', 'payment_cart_anonorders_profile', 'payment_cart_anonorderitems_profile', 'payment_cart_anon_reviewpage', 'payment_cart_anon_group' ),
			'type' => 'flag',
			'default' => 'n',
		),
		'payment_cart_anonorders_profile' => array(
			'name' => tra('Anonymous orders profile'),
			'description' => tra('Wiki page containing the profile for creating orders for anonymous users (page name must not have spaces)'),
			'help' => 'AnonOrderProfile',
			'type' => 'text',
			'default' => '',
		),
		'payment_cart_anonorderitems_profile' => array(
			'name' => tra('Anonymous order item profile'),
			'description' => tra('Wiki page containing the profile for creating order items for anonymous users (page name must not have spaces)'),
			'help' => 'AnonOrderItemProfile',
			'type' => 'text',
			'default' => '',
		),
		'payment_cart_anonshopper_profile' => array(
			'name' => tra('Anonymous shopper info profile'),
			'description' => tra('Wiki page containing the profile for creating order items for anonymous users (page name must not have spaces)'),
			'help' => 'AnonShopperProfile',
			'type' => 'text',
			'default' => '',
		),
		'payment_cart_anon_reviewpage' => array(
			'name' => tra('Anonymous users order review page'),
			'description' => tra('Wiki page where anonymous users can review their orders'),
			'type' => 'text',
			'default' => '',
		),
		'payment_cart_anon_group' => array(
			'name' => tra('Temporary shopper group'),
			'description' => tra('Group name of group with permission to access review page via a token'),
			'type' => 'text',
			'default' => '',
		),
		'payment_cart_associatedevent' => array(
			'name' => tra('Allow association of product orders'),
			'description' => tra('Allow association of products with events (or projects, etc.)'),
			'help' => 'Shopping Cart',
			'dependencies' => array( 'payment_cart_orders', 'payment_cart_associated_event_fieldname', 'payment_cart_event_tracker', 'payment_cart_event_tracker_name', 'payment_cart_eventstart_fieldname', 'payment_cart_eventend_fieldname' ),
			'type' => 'flag',
			'default' => 'n',
		),
		'payment_cart_event_tracker' => array(
			'name' => tra('Events tracker ID'),
			'description' => tra('Tracker ID of tracker that is the events tracker'),
			'type' => 'text',
			'filter' => 'digits',
			'size' => 3,
			'default' => '',
			'profile_reference' => 'tracker',
		),
		'payment_cart_event_tracker_name' => array(
			'name' => tra('Events tracker name'),
			'description' => tra('Name of tracker that is the events tracker'),
			'type' => 'text',
			'default' => '',
		),
		'payment_cart_eventstart_fieldname' => array(
			'name' => tra('Event start field name'),
			'description' => tra('Field Name in events tracker of start date/time'),
			'type' => 'text',
			'default' => '',
		),
		'payment_cart_eventend_fieldname' => array(
			'name' => tra('Event end field name'),
			'description' => tra('Field Name in events tracker of end date/time'),
			'type' => 'text',
			'default' => '',
		),
		'payment_cart_exchange' => array(
			'name' => tra('Allow exchange of products'),
			'help' => 'Shopping Cart',
			'dependencies' => array( 'payment_cart_orderitems_tracker', 'payment_cart_product_tracker', 'payment_cart_product_classid_fieldname', 'payment_cart_productclasses_tracker_name' ),
			'type' => 'flag',
			'default' => '',
		),
		'payment_cart_orderitems_tracker' => array(
			'name' => tra('Order items tracker ID'),
			'description' => tra('Tracker ID of tracker that is the order items tracker'),
			'type' => 'text',
			'filter' => 'digits',
			'size' => 3,
			'default' => '',
			'profile_reference' => 'tracker',
		),
		'payment_cart_giftcerts' => array(
			'name' => tra('Gift certificates'),
			'help' => 'Shopping Cart',
			'dependencies' => array( 'payment_cart_giftcert_tracker', 'payment_cart_giftcert_tracker_name', 'payment_cart_giftcerttemplate_fieldname', 'payment_cart_product_classid_fieldname', 'payment_cart_productclasses_tracker_name' ),
			'type' => 'flag',
			'default' => 'n',
		),
		'payment_cart_giftcert_tracker' => array(
			'name' => tra('Gift certificate tracker ID'),
			'description' => tra('ID of the tracker that is the gift certificate tracker'),
			'type' => 'text',
			'filter' => 'digits',
			'size' => 3,
			'default' => '',
			'profile_reference' => 'tracker',
		),
		'payment_cart_giftcert_tracker_name' => array(
			'name' => tra('Gift certificate tracker name'),
			'description' => tra('Name of tracker that is the gift certificate tracker'),
			'type' => 'text',
			'default' => '',
		),
		'payment_cart_orders_tracker_name' => array(
			'name' => tra('Orders tracker name'),
			'description' => tra('Name of tracker that is the orders tracker'),
			'type' => 'text',
			'default' => '',
		),
		'payment_cart_orderitems_tracker_name' => array(
			'name' => tra('Order items tracker name'),
			'description' => tra('Name of tracker that is the order items tracker'),
			'type' => 'text',
			'default' => '',
		),
		'payment_cart_productclasses_tracker_name' => array(
			'name' => tra('Product classes tracker name'),
			'description' => tra('Name of tracker that is the product classes tracker'),
			'type' => 'text',
			'default' => '',
		),
		'payment_cart_heading' => array(
			'name' => tra('Payment heading'),
			'description' => tra('Heading displayed on payment form'),
			'type' => 'text',
			'filter' => 'text',
			'default' => 'Cart Check-Out',
		),
		'payment_israelpost_environment' => array(
			'name' => tra('Israel Post payment environment'),
			'description' => tra('Switch between test transactions and real transactions'),
			'type' => 'list',
			'options' => array(
				'https://www.israelpost.co.il/extpay.nsf/' => tr('Production'),
				'https://test.israelpost.co.il/extpay.nsf/' => tr('Test'),
			),
			'default' => 'https://www.israelpost.co.il/extpay.nsf/',
		),
		'payment_israelpost_request_preauth' => array(
			'name' => tra('Israel Post use pre-authorization'),
			'description' => tra('Use the pre-authorization mechanism prior to processing the real transaction. Must match the account configuration at IsraelPost.'),
			'type' => 'flag',
			'default' => 'n',
		),
		'payment_israelpost_business_id' => array(
			'name' => tra('Israel Post business ID'),
			'description' => tra('Provided by Israel Post, usually the business name.'),
			'type' => 'text',
			'filter' => 'text',
			'default' => '',
		),
		'payment_israelpost_api_password' => array(
			'name' => tra('Israel Post API password'),
			'description' => tra('Provided by Israel Post.'),
			'type' => 'text',
			'filter' => 'text',
			'default' => '',
		),
	);
}

		
