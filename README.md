# Playful Sparkle - (GA4) Enhanced Measurement for OpenCart 4

The **Playful Sparkle - (GA4) Enhanced Measurement** extension for OpenCart 4.x+ seamlessly integrates and enhances Google Analytics GA4 tracking for your eCommerce store. This extension simplifies the implementation of Google Analytics by enabling detailed tracking of user behavior, cart activities, and purchase events. It is designed to cater to both experienced marketers and beginners, providing valuable insights into your store's performance. Additionally, it supports **Google Consent Mode (GCM)** with predefined profiles, ensuring compliance with privacy regulations when Google Tag Manager is used.

## Features

- **Advanced Tracking Made Simple:** Automatically tracks important user interactions such as:
  - Product views, category browsing, and search activity.
  - Cart interactions like adding or removing items.
  - Checkout steps and completed purchases.
- **Two Setup Options to Suit Your Needs:**
  - **Google Tag Manager (GTM):** Perfect for users who need advanced customization or manage multiple tags. Requires only the **Measurement ID**.
  - **Global Site Tag (gtag.js):** A straightforward option for quick integration with Google Analytics. Requires **Google Tag ID** and **Measurement Protocol API Secret**.
- **Google Consent Mode Integration:** Supports configuring user consent preferences for storage and ad data, with advanced options like **Ads Data Redaction** and **Wait for Update** to meet privacy requirements.
- **Automatic Event Tracking:** Captures eCommerce-specific events such as page views, product impressions, add-to-cart actions, checkout progress, and purchase confirmations—no manual configuration required.
- **Easy Setup and Management:** No technical skills needed. Simply input your required Google IDs and API secrets, and the extension handles the rest.
- **Supports Multi-Store Environments:** Track and manage analytics for multiple stores from a single setup.
- **Enhanced Insights for Better Decisions:** Use Google Analytics GA4's robust features to optimize your store's performance, improve customer experience, and maximize your revenue.
- **Multilingual Support**: Ready for international use with languages including Čeština (cs-cz), Deutsch (de-de), English (GB) (en-gb), English (US) (en-us), Español (es-es), Français (fr-fr), Magyar (hu-hu), Italiano (it-it), Русский (ru-ru), and Slovenčina (sk-sk).

---

This extension makes tracking your store’s performance easier than ever, ensuring you have all the tools needed to grow your business while staying compliant with privacy regulations. Whether you’re new to analytics or a seasoned marketer, this tool simplifies complex tracking tasks and helps you focus on what matters most—your customers.

---
## Tracking eCommerce Events

This extension helps you understand how users interact with your online store by automatically tracking key actions (called "events"). These events provide insights into the customer journey, from browsing products to completing purchases, so you can make better decisions to improve your store's performance.

### What Are Events?

Events are specific actions your customers take on your website. For example, adding a product to their cart, completing a purchase, or even searching for items. By tracking these actions, you can see what’s working well and what might need improvement.

### Events Tracked by This Extension

| **Event**                  | **What It Tracks**                                                                                     |
|----------------------------|--------------------------------------------------------------------------------------------------------|
| `Add Payment Info`         | When a customer enters their payment details during checkout.                                          |
| `Add Shipping Info`        | When a customer selects or provides shipping details during checkout.                                  |
| `Add to Cart`              | When a product is added to the shopping cart.                                                          |
| `Add to Wishlist`          | When a product is saved to a wishlist for later.                                                       |
| `Begin Checkout`           | When a customer starts the checkout process.                                                           |
| `Login`                    | When a customer logs into their account.                                                               |
| `Purchase`                 | When a customer completes an order, including the total spent and items purchased.                     |
| `Refund`                   | When a refund is issued for an order.                                                                  |
| `Remove from Cart`         | When a product is removed from the shopping cart.                                                      |
| `Search`                   | When a customer searches for something on your website.                                                |
| `Select Item`              | When a customer chooses a product to view, especially if it has options like sizes or colors.          |
| `Select Promotion`         | When a customer interacts with a special promotion or offer.                                           |
| `Sign Up`                  | When a customer creates an account or registers for something.                                         |
| `View Cart`                | When a customer views the items in their shopping cart.                                                |
| `View Item`                | When a customer views a single product page.                                                           |
| `View Item List`           | When a customer views a list of products, like on a category or search results page.                   |
| `View Promotion`           | When a customer sees promotional content, such as banners or ads.                                      |
| `Generate Lead`            | When a customer shows interest, such as by filling out a contact form.                                 |
| `Close Convert Lead`       | When a potential lead is marked as converted, e.g., when an order is successfully completed.           |
| `Close Unconvert Lead`     | When a potential lead is marked as unconverted, e.g., when an order is canceled.                       |
| `Disqualify Lead`          | When a potential lead is disqualified, e.g., due to an abandoned cart.                                 |
| `Qualify Lead`             | When a potential lead is qualified, e.g., when a customer shows interest or proceeds to checkout.      |
| `Working Lead`             | When a lead is being worked on, e.g., when a customer is contacted or engaged.                         |
| `File Download`            | When a user clicks to download a file from the website.                                                |

### Simplified Examples

- **Add to Cart:** This event is triggered only when a product is successfully added to the cart. If the product has additional options (like size, color, or subscription plans), the system triggers a `select_item` or `select_promotion` event instead.
- **Refund:** This tracks when you process a refund for an order. It can be partial (e.g., for one item) or full (for the entire order).
- **Search:** When a customer uses the search bar on your website, this event records what they are looking for, helping you understand popular keywords.

This feature allows you to get a full picture of how customers use your store, from their first visit to completing their purchase, making it easier to refine your offerings and improve their shopping experience.

---

## Installation Instructions

1. Download the latest version from this repository.
2. Log in to your OpenCart admin panel.
3. Navigate to `Extensions > Installer`.
4. Click the `Upload` button and upload the `ps_enhanced_measurement.ocmod.zip` file.
5. Locate the extension in the `Installed Extensions` list and click the `Install` button.
6. Navigate to `Extensions > Extensions` and select `Analytics` from the `Choose the extension type` dropdown list.
7. Locate the extension in the `Analytics` list and click the `Install` button.
8. Click the `Edit` button, configure the extension parameters, and click the `Save` button to save your settings.

---

## Support & Inquiries

For assistance or inquiries related to this extension, please open an issue on this repository or contact us via email at [support@playfulsparkle.com](mailto:support@playfulsparkle.com).

---

## License

This project is distributed under the GPL-3.0 license. Please refer to the [LICENSE](./LICENSE) file for further details.

---

## Contributing

We encourage contributions from the community. To contribute, please fork the repository and submit a pull request with your proposed changes.
