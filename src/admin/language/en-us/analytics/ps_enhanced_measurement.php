<?php
// Heading
$_['heading_title']                         = 'Playful Sparkle - (GA4) Enhanced Measurement';
$_['heading_fix']                           = 'Fix Common Bugs';
$_['heading_getting_started']               = 'Getting Started';
$_['heading_setup']                         = 'Setting Up (GA4) Enhanced Measurement';
$_['heading_troubleshoot']                  = 'Common Troubleshooting';
$_['heading_faq']                           = 'FAQ';
$_['heading_contact']                       = 'Contact Support';

// Text
$_['text_extension']                        = 'Extensions';
$_['text_edit']                             = 'Edit (GA4) Enhanced Measurement';
$_['text_success']                          = 'Success: You have modified (GA4) Enhanced Measurement!';
$_['text_getting_started']                  = '<p><strong>Overview:</strong> The Playful Sparkle - GA4 Enhanced Measurement for OpenCart 4 extension provides advanced tracking capabilities for your eCommerce store. It supports multiple event tracking options, including user interactions, cart activities, and purchase events. Additionally, it enables integration with Google Tag Manager or Global Site Tag, offering flexibility in implementing measurement solutions.</p><p><strong>Requirements:</strong> OpenCart 4.x, a valid Google Analytics GA4 account, and the appropriate credentials based on your selected Measurement Implementation: Google Tag ID and Measurement Protocol API secret are required when using Global Site Tag (gtag.js), and Measurement ID is required if you select Google Tag Manager (GTM). Ensure no other analytics extensions are enabled to prevent code conflicts.</p>';
$_['text_setup']                            = '<ul><li>Select your preferred Measurement Implementation (Global Site Tag or Google Tag Manager).</li><li>If using Global Site Tag, enter your Google Tag ID and Measurement Protocol API secret. For Google Tag Manager, enter your Measurement ID.</li><li>Configure the tracking events you want to enable, such as login, purchase, or add-to-cart tracking.</li><li>Verify that no other extensions injecting tracking codes (e.g., Tag Manager or Global Site Tag) are active to avoid conflicts.</li><li>Save the settings and test the implementation using the Google Analytics debug tools.</li></ul>';
$_['text_troubleshoot']                     = '<ul><li><strong>Issue:</strong> Events are not visible in the Google Analytics dashboard. <strong>Solution:</strong> Confirm that the Measurement ID or Tag ID is correctly entered and matches your GA4 property. If using Global Site Tag (gtag.js), ensure the Google Tag ID and Measurement Protocol API secret are correctly configured.</li><li><strong>Issue:</strong> Duplicate events are being tracked. <strong>Solution:</strong> Check if other analytics extensions are injecting tracking code and disable them if necessary. Also, verify that the same event is not being tracked through multiple implementations (e.g., both GTM and gtag.js).</li><li><strong>Issue:</strong> Tracking does not work across multiple stores. <strong>Solution:</strong> Ensure the correct Tag ID or Measurement ID is configured for each store. For GTM, ensure each store has the appropriate container set up in Google Tag Manager.</li><li><strong>Issue:</strong> Refund data is not visible in Google Analytics. <strong>Solution:</strong> Allow time for refund data to appear in Google Analytics and ensure the refund is correctly configured as either partial or full, as only one submission per order is accepted.</li></ul>';
$_['text_faq']                              = '<details><summary>Why is Google Consent Mode (GCM) visible when I select Global Site Tag?</summary>Global Site Tag (gtag.js) does not support or require GCM.</details><details><summary>Why is there no debug mode option for Google Tag Manager?</summary>You need to set up debug mode directly in Google Tag Manager.</details><details><summary>What happens if I select a different item ID that is unavailable?</summary>The product_id will be used instead.</details><details><summary>What happens if I do not fill out the Affiliation?</summary>The store name will be used.</details><details><summary>Can I delay sending events to Google Analytics?</summary>Yes, check the Tracking Events tab and the Tracking Delay field.</details><details><summary>Why is my refund data not appearing in Google Analytics?</summary>Refund data may take time to appear in Google Analytics.</details><details><summary>Why can I not refund more than once?</summary>Google Analytics only accepts one refund submission per order. You can process either a partial or a full refund.</details><details><summary>Which events are supported?</summary>The supported events are: add_payment_info, add_shipping_info, add_to_cart, add_to_wishlist, begin_checkout, generate_lead, login, purchase, refund, remove_from_cart, search, select_item, select_promotion, sign_up, view_cart, view_item, view_item_list, view_promotion.</details><details><summary>How does the add to cart event work?</summary>The add to cart event is triggered only when the user actually adds a product to the cart. Otherwise, the select_item or select_promotion event is triggered based on whether it is a special product or not.</details>';
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
$_['tab_help_and_support']                  = 'Help & Support';
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
$_['entry_add_payment_info']                = 'Track Add Payment Info Event';
$_['entry_add_shipping_info']               = 'Track Add Shipping Info Event';
$_['entry_purchase']                        = 'Track Purchase Event';
$_['entry_user_id']                         = 'Send User ID';
$_['entry_gcm_status']                      = 'Enable GCM';
$_['entry_ad_storage']                      = 'Ad Storage';
$_['entry_ad_user_data']                    = 'Ad User Data';
$_['entry_ad_personalization']              = 'Ad Personalization';
$_['entry_analytics_storage']               = 'Analytics Storage';
$_['entry_functionality_storage']           = 'Functionality Storage';
$_['entry_personalization_storage']         = 'Personalization Storage';
$_['entry_security_storage']                = 'Security Storage';
$_['entry_wait_for_update']                 = 'Wait for Update';
$_['entry_ads_data_redaction']              = 'Ads Data Redaction';
$_['entry_url_passthrough']                 = 'URL Passthrough';
$_['entry_strict']                          = 'Strict';
$_['entry_balanced']                        = 'Balanced';
$_['entry_custom']                          = 'Custom';
$_['entry_gcm_profiles']                    = 'GCM Profiles';

// Button
$_['button_fix_event_handler']              = 'Fix Event Handler';
$_['button_refund']                         = 'Refund';
$_['button_refund_all']                     = 'Refund All';

// Help
$_['help_google_tag_id_locate']             = 'To find your Google Tag ID, log in to your <a href="https://analytics.google.com" target="_blank" rel="external noopener noreferrer">Analytics account</a>. Go to the Admin section, select the property you want to track, and find your Google Tag ID. It will start with "G-" followed by a unique combination of letters and numbers, like "G-XXXXXXXXXXX." <a href="https://support.google.com/analytics/answer/9539598?hl=en" target="_blank" rel="external noopener noreferrer">More detailed instructions here</a>.';
$_['help_gtm_id_locate']                    = 'To find your Measurement ID for your <a href="https://tagmanager.google.com" target="_blank" rel="external noopener noreferrer">Google Tag Manager account</a>, look for the ID at the top of the workspace dashboard—it starts with "GTM-" followed by a unique series of letters and numbers, such as "GTM-XXXXXXXX". <a href="https://support.google.com/analytics/answer/12270356?hl=en" target="_blank" rel="external noopener noreferrer">More detailed instructions here</a>.';
$_['help_mp_api_secret_locate']             = 'To find your Measurement Protocol API Secret, go to your <a href="https://analytics.google.com/" target="_blank" rel="external noopener noreferrer">Google Analytics account</a>. Navigate to Admin in the left-hand menu, then under the Property Settings, select Data Streams. Choose your data stream, and scroll down to the Measurement Protocol API secrets section. Here, you can create a new API secret or find your existing ones. The API secret is a unique string, e.g., XXXXXXX-XXXXXXX-XXXXXX, used for authenticating server-side requests.';
$_['help_affiliation']                      = 'Enter the store name or department for the <strong>affiliation</strong> part of eCommerce tracking. If you leave this blank, it will automatically use the default store name from the settings.';
$_['help_location_id']                      = 'The physical location of the item, such as the store where it’s sold. It’s best to use the <a href="https://developers.google.com/maps/documentation/places/web-service/place-id" target="_blank" rel="external noopener noreferrer">Google Place ID</a> for that location, but you can also use a custom location ID.';
$_['help_tracking_delay']                   = 'Specify the delay (in milliseconds) to wait before executing the default action (e.g., navigating to a link or submitting a form) after the GA4 event is sent. This ensures the event is tracked properly before the action completes. Leave empty to use the default value.';
$_['help_generate_lead']                    = 'This event measures when a lead has been generated, specifically tracking newsletter subscriptions and contact form submissions. Use this to understand the effectiveness of your marketing campaigns and how many customers re-engage with your business after remarketing.';
$_['help_sign_up']                          = 'This event indicates that a user has signed up for an account. Use this to understand the different behaviors of logged-in and logged-out users.';
$_['help_login']                            = 'Send this event to signify that a user has logged in to your website or app.';
$_['help_add_to_wishlist']                  = 'This event signifies that an item was added to a wishlist. Use this to identify popular gift items in your app.';
$_['help_add_to_cart']                      = 'This event signifies that an item was added to a cart for purchase.';
$_['help_remove_from_cart']                 = 'This event signifies that an item was removed from a cart.';
$_['help_search']                           = 'Log this event to indicate when the user has performed a search. Use this to identify what users are searching for on your website or app. For example, send this event when a user views a search results page after performing a search.';
$_['help_view_item_list']                   = 'Log this event when the user has been presented with a list of items from a certain category.';
$_['help_select_item']                      = 'This event signifies an item was selected from a list.';
$_['help_view_item']                        = 'This event signifies that some content was shown to the user. Use this to discover the most popular items viewed.';
$_['help_select_promotion']                 = 'This event signifies a promotion was selected from a list.';
$_['help_view_promotion']                   = 'This event signifies a promotion was viewed from a list.';
$_['help_view_cart']                        = 'This event signifies that a user viewed their cart.';
$_['help_begin_checkout']                   = 'This event signifies that a user has begun the checkout process.';
$_['help_add_payment_info']                 = 'This event signifies a user has submitted their payment information in an eCommerce checkout process.';
$_['help_add_shipping_info']                = 'This event signifies a user has submitted their shipping information in an eCommerce checkout process.';
$_['help_purchase']                         = 'This event signifies when one or more items are purchased by a user.';
$_['help_user_id']                          = 'This option enables tracking of logged-in user IDs, allowing you to better understand user behavior across sessions and devices, providing more accurate and detailed analytics.';
$_['help_ad_storage']                       = 'Controls whether data storage is allowed for ad-related purposes, such as tracking ad clicks or conversions.';
$_['help_ad_user_data']                     = 'Determines if data about users interacting with ads is stored, enhancing ad targeting capabilities.';
$_['help_ad_personalization']               = 'Allows ads to be personalized based on user data, providing more relevant advertisements to users.';
$_['help_analytics_storage']                = 'Enables storage of data used for analytics purposes, helping to track site performance and user behavior.';
$_['help_functionality_storage']            = 'Allows data storage to support functionality, like user preferences or site features that enhance user experience.';
$_['help_personalization_storage']          = 'Controls storage of data for personalizing the user experience, such as recommended content or settings.';
$_['help_security_storage']                 = 'Ensures storage of security-related data, such as for fraud prevention and secure access control.';
$_['help_wait_for_update']                  = 'Sets the time (in milliseconds) to delay before updating consent status, ensuring all settings are applied.';
$_['help_ads_data_redaction']               = 'Redacts user data related to ads, ensuring privacy by hiding certain identifiable information.';
$_['help_url_passthrough']                  = 'Allows the URL to pass through consent checks, useful for tracking specific user paths without storing personal data.';
$_['help_gcm_status']                       = 'Enables Google Consent Mode, allowing your site to adjust Google tags behavior based on user consent settings. This mode provides privacy-friendly tracking, allowing for analytics and ads to function in compliance with consent preferences.';

// Error
$_['error_permission']                      = 'Warning: You do not have permission to modify (GA4) Enhanced Measurement settings!';
$_['error_refund_send']                     = 'Warning: Failed to send refund data to Google Analytics (GA4). Please check your settings and try again.';
$_['error_google_tag_id']                   = 'The Google Tag ID field is required. Please enter your Google Analytics ID.';
$_['error_google_tag_id_invalid']           = 'The Google Tag ID format is incorrect. Ensure it follows the format G-XXXXXXXXXXX.';
$_['error_gtm_id']                          = 'The GTM ID field is required. Please enter your Measurement ID.';
$_['error_gtm_id_invalid']                  = 'The GTM ID format is incorrect. Ensure it follows the format GTM-XXXXXXXX.';
$_['error_mp_api_secret']                   = 'The Measurement Protocol API secret field is required. Please enter your Measurement Protocol API secret.';
$_['error_mp_api_secret_invalid']           = 'The Measurement Protocol API secret format is incorrect. Ensure it follows the format XXXXXXX-XXXXXXX-XXXXXX.';
$_['error_measurement_implementation']      = 'Measurement implementation is not configured. Please select either Global Site Tag or Google Tag Manager.';
$_['error_client_id']                       = 'Warning: Client ID was not saved during checkout.';
$_['error_order_product_id']                = 'Warning: The product ID associated with this order was not found.';
$_['error_request_parameters']              = 'Warning: Required request parameters are missing or incomplete.';
$_['error_analytics_extension']             = 'It looks like another analytics tool is already active on your site. Having more than one tool like this can create problems, such as duplicate or missing tracking. Please check your site’s settings.';
$_['error_tracking_delay']                  = 'The Tracking Delay must be at least 100 milliseconds to ensure proper event tracking.';
$_['error_wait_for_update']                 = 'The Wait for Update value must be a number between 0 and 10000 milliseconds.';
