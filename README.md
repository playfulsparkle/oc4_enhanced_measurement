# Playful Sparkle - (GA4) Enhanced Measurement for OpenCart 4

The **Playful Sparkle - (GA4) Enhanced Measurement** extension for OpenCart 4.x+ seamlessly integrates and enhances Google Analytics GA4 tracking for your eCommerce store. This extension simplifies the implementation of Google Analytics by enabling detailed tracking of user behavior, cart activities, and purchase events. Designed to support both experienced marketers and beginners, it provides valuable insights into eCommerce performance. Additionally, it supports Google Consent Mode (GCM) with predefined profiles, ensuring that your tracking setup complies with privacy regulations when Google Tag Manager is used for measurement implementation.

## Features and Benefits

### Why Use This Extension?

- **Advanced Tracking Capabilities:** Track user interactions such as product views, cart activities, and purchases in real-time.
- **Flexible Measurement Implementations:** Supports both **Google Tag Manager (GTM)** and **Global Site Tag (gtag.js)**, giving you the freedom to choose your preferred implementation.
- **Google Consent Mode (GCM)** provides support for Google Consent Mode through predefined GCM profiles. It enables the configuration of Ad Storage, Ad User Data, Ad Personalization, Analytics Storage, Functionality Storage, Personalization Storage, and Security Storage, as well as advanced settings including Wait for Update, Ads Data Redaction, and URL Passthrough. This functionality is available exclusively when Google Tag Manager is selected as the measurement implementation.
- **Streamlined Setup:** No coding knowledge required. Just provide the necessary **Google tag ID**, **Measurement Protocol API secret**, or **Measurement ID**, depending on your selected implementation.
- **Multi-Store Support:** Easily configure tracking for multiple stores under a single setup.
- **Data-Driven Decisions:** Leverage the power of GA4 to optimize your marketing campaigns, product offerings, and overall user experience.

### Supported Measurement Implementations

1. **Google Tag Manager (GTM):**
   - Ideal for users requiring advanced customization or managing multiple tags.
   - Requires **Measurement ID**.

2. **Global Site Tag (gtag.js):**
   - Quick and straightforward for users looking to integrate Google Analytics without complexity.
   - Requires **Google Tag ID** and **Measurement Protocol API Secret**.

---

## Tracking eCommerce Events

This extension enables the tracking of key eCommerce events to provide a complete picture of user activity on your store.

### Supported Events

| **Event Name**             | **Description**                                                                                          |
|----------------------------|----------------------------------------------------------------------------------------------------------|
| `add_payment_info`         | Tracks when a user provides payment information during checkout.                                         |
| `add_shipping_info`        | Tracks when a user selects or adds shipping details during checkout.                                     |
| `add_to_cart`              | Tracks when a product is added to the shopping cart.                                                     |
| `add_to_wishlist`          | Tracks when a product is added to a wishlist.                                                            |
| `begin_checkout`           | Tracks when a user starts the checkout process.                                                          |
| `generate_lead`            | Tracks lead generation activities.                                                                       |
| `login`                    | Tracks user logins.                                                                                      |
| `purchase`                 | Tracks completed purchases, including revenue and items purchased.                                       |
| `refund`                   | Tracks refunds processed, either partial or full, for orders.                                            |
| `remove_from_cart`         | Tracks when a product is removed from the shopping cart.                                                 |
| `search`                   | Tracks user searches on your website.                                                                    |
| `select_item`              | Tracks when a product with options is selected for viewing.                                              |
| `select_promotion`         | Tracks when a promotional product with a subscription is selected for viewing.                           |
| `sign_up`                  | Tracks user registrations.                                                                               |
| `view_cart`                | Tracks when a user views their shopping cart.                                                            |
| `view_item`                | Tracks when a user views a single product.                                                               |
| `view_item_list`           | Tracks when a user views a list of products, such as search results or a category page.                  |
| `view_promotion`           | Tracks when a user views promotional elements like banners or ads.                                       |

### Event Details

- **Add to Cart:** Fires only when a product is actually added to the cart. If the product has options or a subscription assigned to it, a `select_item` or `select_promotion` event is triggered respectively instead.
- **Refund:** Captures refund events, with limitations on one submission per order (either partial or full).

---

## Installation Instructions

### 1. Download the Extension
Download the latest **Playful Sparkle - (GA4) Enhanced Measurement** release from this repository.

### 2. Upload the Extension Files
1. Log in to your OpenCart admin panel.
2. Go to `Extensions > Installer`.
3. Click the `Upload` button and upload the `ps_enhanced_measurement.ocmod.zip` file.

### 3. Install the Extension
4. Once uploaded, find the **Playful Sparkle - (GA4) Enhanced Measurement** extension and click the `Install` button.
5. Navigate to `Extensions` and select `Analytics` from the dropdown.
6. Locate the **Playful Sparkle - (GA4) Enhanced Measurement** in the module list and click the green `Install` button.

### 4. Configure the Extension
1. After installation, go to the `Extensions` page, ensuring `Analytics` is selected.
2. Click `Edit` next to the **Playful Sparkle - (GA4) Enhanced Measurement** extension.
4. Install and enable the module by setting the status to "Enabled."
5. Save the configuration.

---

## Support & Feedback

For support or any inquiries regarding the extension, feel free to open an issue on this repository or reach out via email at [support@playfulsparkle.com](mailto:support@playfulsparkle.com).

---

## License

This project is licensed under the GPL-3.0 license. See the [LICENSE](./LICENSE) file for more information.

---

## Contributing

We welcome contributions! If you would like to contribute to this project, please fork the repository and submit a pull request with your changes.
