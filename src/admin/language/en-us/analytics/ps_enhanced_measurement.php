<?php
// Heading
$_['heading_title']                         = 'Playful Sparkle - (GA4) Enhanced Measurement';
$_['heading_fix']                           = 'Fix Common Bugs';
$_['heading_getting_started']               = 'Getting Started';
$_['heading_setup']                         = 'Setting Up (GA4) Enhanced Measurement';
$_['heading_troubleshot']                   = 'Common Troubleshooting';
$_['heading_faq']                           = 'FAQ';
$_['heading_contact']                       = 'Contact Support';

// Text
$_['text_extension']                        = 'Extensions';
$_['text_edit']                             = 'Edit (GA4) Enhanced Measurement';
$_['text_success']                          = 'Success: You have modified (GA4) Enhanced Measurement!';
$_['text_getting_started']                  = '<p><strong>Overview:</strong> The Playful Sparkle - (GA4) Enhanced Measurement for OpenCart 4 extension provides advanced tracking capabilities for your eCommerce store. It supports multiple event tracking options, including user interactions, cart activities, and purchase events. Additionally, it enables integration with Google Tag Manager or Global Site Tag, giving you flexibility in implementing measurement solutions.</p><p><strong>Requirements:</strong> OpenCart 4.x, a valid Google Analytics GA4 account, and the appropriate credentials based on your selected Measurement Implementation: Google Tag ID and Measurement Protocol API secret are required when using Global Site Tag - gtag.js, and Measurement ID is required if you select Google Tag Manager (GTM). Ensure no other analytics extensions are enabled to prevent code conflicts.</p>';
$_['text_setup']                            = '<ul><li>Select your preferred Measurement Implementation (Global Site Tag or Google Tag Manager).</li><li>If using Global Site Tag, enter your Google Tag ID and Measurement Protocol API secret. For Google Tag Manager, enter your Measurement ID.</li><li>Configure the tracking events you want to enable, such as login, purchase, or add-to-cart tracking.</li><li>Verify that no other extensions injecting tracking codes (e.g., Tag Manager or Global Site Tag) are active to avoid conflicts.</li><li>Save the settings and test the implementation using the Google Analytics debug tools.</li></ul>';
$_['text_troubleshot'] = '<ul>
<li><strong>Issue:</strong> Events are not visible in the Google Analytics dashboard. <strong>Solution:</strong> Confirm the Measurement ID or Tag ID is correctly entered and matches your GA4 property. If using Global Site Tag (gtag.js), ensure the Google Tag ID and Measurement Protocol API secret are correctly configured.</li>
<li><strong>Issue:</strong> Duplicate events tracked. <strong>Solution:</strong> Check if other analytics extensions are injecting tracking code and disable them if necessary. Also, verify that the same event is not being tracked through multiple implementations (e.g., both GTM and gtag.js).</li>
<li><strong>Issue:</strong> Tracking does not work for multiple stores. <strong>Solution:</strong> Ensure the correct Tag ID or Measurement ID is configured for each store. For GTM, ensure each store has the appropriate container setup in Google Tag Manager.</li>
<li><strong>Issue:</strong> Refund data is not visible in Google Analytics. <strong>Solution:</strong> Allow time for the refund data to appear in Google Analytics and ensure the refund is correctly configured as either partial or full, as only one submission per order is accepted.</li>
</ul>';
$_['text_faq'] = '<details><summary>Why is Google Consent Mode (GCM) visible when I select Global Site Tag?</summary>Global Site Tag (gtag.js) does not support or require GCM.</details>
<details><summary>Why is there no debug mode option for Google Tag Manager?</summary>You have to set up debug mode directly in Google Tag Manager.</details>
<details><summary>What happens if I select the item ID to be different and it is not available?</summary>The product_id will be used instead.</details>
<details><summary>What happens if I do not fill out the Affiliation?</summary>The store name will be used.</details>
<details><summary>Can I delay sending events to Google Analytics?</summary>Yes, look up the Tracking Events tab and the Tracking Delay entry field.</details>
<details><summary>Why is my refund data not appearing in Google Analytics?</summary>It takes time for refund data to appear in Google Analytics.</details>
<details><summary>Why can I not refund more than once?</summary>Google Analytics does not accept multiple refund submissions. You can only process either a partial or a full refund.</details>
<details><summary>Which events are supported?</summary>The supported events are: add_payment_info, add_shipping_info, add_to_cart, add_to_wishlist, begin_checkout, generate_lead, login, purchase, refund, remove_from_cart, search, select_item, select_promotion, sign_up, view_cart, view_item, view_item_list, view_promotion.</details>
<details><summary>How does the add to cart event work?</summary>The add to cart event is fired only when the user truly inserts a product into the cart. Otherwise, the select_item or select_promotion event is triggered based on whether it is a special product or not.</details>';
$_['text_contact']                          = '<p>For further assistance, please reach out to our support team:</p><ul><li><strong>Contact:</strong> <a href="mailto:%s">%s</a></li><li><strong>Documentation:</strong> <a href="%s" target="_blank" rel="noopener noreferrer">User Documentation</a></li></ul>';
$_['text_gtag']                             = 'Global Site Tag - gtag.js';
$_['text_gtm']                              = 'Google Tag Manager';
$_['text_item_options_group']               = 'Item Options';
$_['text_store_options_group']              = 'Store Options';
$_['text_product_id']                       = 'Product ID';
$_['text_model']                            = 'Model';
$_['text_sku']                              = 'SKU';
$_['text_upc']                              = 'UPC';
$_['text_ean']                              = 'EAN';
$_['text_jan']                              = 'JAN';
$_['text_isbn']                             = 'ISBN';
$_['text_mpn']                              = 'MPN';
$_['text_default']                          = '(default)';
$_['text_category_option_type_1']           = 'The last segment of all categories associated with the product';
$_['text_category_option_type_2']           = 'All categories, category names separated with "&gt;" symbol associated with the product';
$_['text_category_option_type_3']           = 'Current category names associated with the product';
$_['text_category_option_type_4']           = 'The last segment of current category name associated with the product';
$_['text_multi_currency']                   = 'Multi-currency';
$_['text_refund_quantity']                  = 'Quantity';
$_['text_refund_successfully_sent']         = 'Success: Refund data has been successfully sent to Google Analytics.';
$_['text_group_ad_settings']                = 'Ad Settings';
$_['text_group_analytics_settings']         = 'Analytics Settings';
$_['text_group_security_settings']          = 'Security Settings';
$_['text_group_advanced_settings']          = 'Advanced Settings';
$_['text_gcm_info']                         = 'Google Consent Mode (GCM) works only when you choose Google Tag Manager in the Measurement Implementation dropdown. It doesn’t work with Global Site Tag (gtag.js). To use this feature, make sure you have a cookie banner installed. This extension sets a basic consent state by default, but the cookie banner is responsible for updating consent to allow data collection.';

// Column
$_['column_refund_quantity']                = 'Refund Quantity';

// Tab
$_['tab_general']                           = 'General';
$_['tab_gcm']                               = 'Google Consent Mode (GCM)';
$_['tab_track_events']                      = 'Tracking Events';
$_['tab_help_and_support']                  = 'Help &amp; Support';
$_['tab_gtag']                              = 'Global Site Tag - gtag.js';
$_['tab_gtm']                               = 'Google Tag Manager (GTM)';

// Entry
$_['entry_status']                          = 'Status';
$_['entry_measurement_implementation']      = 'Measurement Implementation';
$_['entry_google_tag_id']                   = 'Google Tag ID';
$_['entry_gtm_id']                          = 'Measurement ID';
$_['entry_measurement_protocol_api_secret'] = 'Measurement Protocol API Secret';
$_['entry_item_id']                         = 'Item ID';
$_['entry_item_category_option']            = 'Item Category';
$_['entry_tracking_delay']                  = 'Tracking Delay';
$_['entry_affiliation']                     = 'Affiliation';
$_['entry_location_id']                     = 'Location ID';
$_['entry_item_price_tax']                  = 'Show Prices with Tax';
$_['entry_currency']                        = 'Currency';
$_['entry_debug_mode']                      = 'Debug Mode';
$_['entry_gtag_debug_mode']                 = 'Debug Global Site Tag';
$_['entry_generate_lead']                   = 'Track Generate Lead Event';
$_['entry_sign_up']                         = 'Track Sign Up Event';
$_['entry_login']                           = 'Track Login Event';
$_['entry_add_to_wishlist']                 = 'Track Add to Wishlist Event';
$_['entry_add_to_cart']                     = 'Track Add to Cart Event';
$_['entry_remove_from_cart']                = 'Track Remove from Cart Event';
$_['entry_search']                          = 'Track Search Event';
$_['entry_view_item_list']                  = 'Track View Item List Event';
$_['entry_select_item']                     = 'Track Select Item Event';
$_['entry_view_item']                       = 'Track View Item Event';
$_['entry_select_promotion']                = 'Track Select Promotion Event';
$_['entry_view_promotion']                  = 'Track View Promotion Event';
$_['entry_view_cart']                       = 'Track View Cart Event';
$_['entry_begin_checkout']                  = 'Track Begin Checkout Event';
