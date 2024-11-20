<?php
namespace Opencart\Catalog\Model\Extension\PsEnhancedMeasurement\Analytics;
/**
 * Class PsEnhancedMeasurement
 *
 * @package Opencart\Catalog\Model\Extension\PsEnhancedMeasurement\Analytics
 */
class PsEnhancedMeasurement extends \Opencart\System\Engine\Model
{
    /**
     * @param array $args
     *
     * @return array
     */
    public function replaceCatalogViewCommonHeaderBefore(array $args): array
    {
        $views = [];

        $views[] = [
            'search' => '<body>',
            'replace' => <<<HTML
            <body>
            {% if ps_enhanced_measurement_status and (ps_enhanced_measurement_implementation == 'gtm') %}
            <!-- Google Tag Manager (noscript) -->
            <noscript>
                <iframe src="https://www.googletagmanager.com/ns.html?id={{ ps_enhanced_measurement_gtm_id }}" height="0" width="0" style="display:none; visibility:hidden;"></iframe>
            </noscript>
            <!-- End Google Tag Manager (noscript) -->
            {% endif %}
            HTML
        ];

        $views[] = [
            'search' => '</head>',
            'replace' => <<<HTML
                {% if ps_enhanced_measurement_status %}<script>
                var ps_dataLayer = {
                    data: {},
                    getData: function(eventName, productId) {
                        return this.data.hasOwnProperty(eventName + '_' + productId) ? this.data[eventName + '_' + productId] : null;
                    },
                    merge: function(data) {
                        this.data = Object.assign({}, this.data, data);
                    },
                    push: function(eventName, productId) {
                        if (this.data.hasOwnProperty(eventName + '_' + productId)) {
                            this.pushData(eventName, this.data[eventName + '_' + productId]);
                        } else {
                            console.error('`' + eventName + '_' + productId + '` does not exists in dataset!');
                        }
                    },
                    pushData: function(eventName, data) {
                        {% if ps_enhanced_measurement_implementation == 'gtag' %}
                            gtag('event', eventName, data);
                        {% elseif ps_enhanced_measurement_implementation == 'gtm' %}
                            dataLayer.push({ ecommerce: null });
                            dataLayer.push({ event: eventName, ...data });
                        {% endif %}

                        console.log(JSON.stringify(Object.assign({}, {event: eventName}, data), undefined, 4));
                    }
                };
            </script>{% endif %}
            {% if ps_user_id %}<script>{{ ps_user_id }}</script>{% endif %}
            </head>
            HTML
        ];

        return $views;
    }

    public function replaceCatalogViewProductThumbBefore(array $args): array
    {
        $views = [];

        $views[] = [
            'search' => '<a href="{{ href }}">',
            'replace' => '<a href="{{ href }}" data-ps-track-id="{{ product_id }}" data-ps-track-event="{% if special %}select_promotion{% else %}select_item{% endif %}">'
        ];

        $views[] = [
            'search' => '<button type="submit" formaction="{{ add_to_cart }}"',
            'replace' => '<button type="submit" formaction="{{ add_to_cart }}" data-ps-track-id="{{ product_id }}" data-ps-track-event="{% if has_options %}{% if special %}select_promotion{% else %}select_item{% endif %}{% else %}add_to_cart{% endif %}"'
        ];

        $views[] = [
            'search' => '<button type="submit" formaction="{{ add_to_wishlist }}"',
            'replace' => '<button type="submit" formaction="{{ add_to_wishlist }}" data-ps-track-id="{{ product_id }}" data-ps-track-event="add_to_wishlist"'
        ];

        return $views;
    }

    public function replaceCatalogViewProductCategoryBefore(array $args): array
    {
        $views = [];

        $views[] = [
            'search' => '{% if products %}',
            'replace' => <<<HTML
            {% if ps_merge_items %}<script>ps_dataLayer.merge({{ ps_merge_items }});</script>{% endif %}
            {% if ps_view_item_list %}<script>ps_dataLayer.pushData('view_item_list', {{ ps_view_item_list }});</script>{% endif %}
            {% if products %}
            HTML
        ];

        return $views;
    }

    public function replaceCatalogViewProductSearchBefore(array $args): array
    {
        $views = [];

        $views[] = [
            'search' => '{% if products %}',
            'replace' => <<<HTML
            {% if ps_merge_items %}<script>ps_dataLayer.merge({{ ps_merge_items }});</script>{% endif %}
            {% if ps_search %}<script>ps_dataLayer.pushData('search', {{ ps_search }});</script>{% endif %}
            {% if ps_view_item_list %}<script>ps_dataLayer.pushData('view_item_list', {{ ps_view_item_list }});</script>{% endif %}
            {% if products %}
            HTML
        ];

        return $views;
    }

    public function replaceCatalogViewProductSpecialBefore(array $args): array
    {
        $views = [];

        $views[] = [
            'search' => '{% if products %}',
            'replace' => <<<HTML
            {% if ps_merge_items %}<script>ps_dataLayer.merge({{ ps_merge_items }});</script>{% endif %}
            {% if ps_view_item_list %}<script>ps_dataLayer.pushData('view_item_list', {{ ps_view_item_list }});</script>{% endif %}
            {% if products %}
            HTML
        ];

        return $views;
    }

    public function replaceCatalogViewProductCompareBefore(array $args): array
    {
        $views = [];

        $views[] = [
            'search' => '<a href="{{ product.href }}">',
            'replace' => '<a href="{{ product.href }}" data-ps-track-id="{{ product.product_id }}" data-ps-track-event="{% if product.special %}select_promotion{% else %}select_item{% endif %}">',
        ];

        $views[] = [
            'search' => '<button type="submit" id="button-confirm"',
            'replace' => '<button type="submit" id="button-confirm" data-ps-track-id="{{ product.product_id }}" data-ps-track-event="{% if product.has_options %}{% if product.special %}select_promotion{% else %}select_item{% endif %}{% else %}add_to_cart{% endif %}"'
        ];

        $views[] = [
            'search' => '{% if products %}',
            'replace' => <<<HTML
            {% if ps_merge_items %}<script>ps_dataLayer.merge({{ ps_merge_items }});</script>{% endif %}
            {% if ps_view_item_list %}<script>ps_dataLayer.pushData('view_item_list', {{ ps_view_item_list }});</script>{% endif %}
            {% if products %}
            HTML
        ];

        return $views;
    }

    public function replaceCatalogViewAccountAllBefore(array $args): array
    {
        $views = [];

        $views[] = [
            'search' => '{{ footer }}',
            'replace' => <<<HTML
            {% if ps_login %}<script>ps_dataLayer.pushData('login', {{ ps_login }});</script>{% endif %}
            {{ footer }}
            HTML
        ];

        return $views;
    }

    public function replaceCatalogViewAccountWishlistBefore(array $args): array
    {
        $views = [];

        $views[] = [
            'search' => '<div id="wishlist">{{ list }}</div>',
            'replace' => <<<HTML
            <div id="wishlist">{{ list }}</div>
            {% if ps_merge_items %}<script>ps_dataLayer.merge({{ ps_merge_items }});</script>{% endif %}
            {% if ps_view_item_list %}<script>ps_dataLayer.pushData('view_item_list', {{ ps_view_item_list }});</script>{% endif %}
            HTML
        ];

        return $views;
    }

    public function replaceCatalogViewProductManufacturerInfoBefore(array $args): array
    {
        $views = [];

        $views[] = [
            'search' => '{% if products %}',
            'replace' => <<<HTML
            {% if ps_merge_items %}<script>ps_dataLayer.merge({{ ps_merge_items }});</script>{% endif %}
            {% if ps_view_item_list %}<script>ps_dataLayer.pushData('view_item_list', {{ ps_view_item_list }});</script>{% endif %}
            {% if products %}
            HTML
        ];

        return $views;
    }

    public function replaceCatalogViewAccountWishlistListBefore(array $args): array
    {
        $views = [];

        $views[] = [
            'search' => '<a href="{{ product.href }}">',
            'replace' => '<a href="{{ product.href }}" data-ps-track-id="{{ product.product_id }}" data-ps-track-event="{% if product.special %}select_promotion{% else %}select_item{% endif %}">',
        ];

        $views[] = [
            'search' => '<button type="submit" formaction="{{ add_to_cart }}"',
            'replace' => '<button type="submit" formaction="{{ add_to_cart }}" data-ps-track-id="{{ product.product_id }}" data-ps-track-event="{% if product.has_options %}{% if product.special %}select_promotion{% else %}select_item{% endif %}{% else %}add_to_cart{% endif %}"'
        ];

        return $views;
    }

    public function replaceCatalogViewProductProductBefore(array $args): array
    {
        $views = [];

        $views[] = [
            'search' => '<button type="submit" id="button-cart"',
            'replace' => '<input type="hidden" name="category_id" value="{{ ps_category_id }}">
            <button type="submit" id="button-cart"'
        ];

        $views[] = [
            'search' => '<button type="submit" formaction="{{ add_to_wishlist }}"',
            'replace' => '<button type="submit" formaction="{{ add_to_wishlist }}" data-ps-track-id="{{ product_id }}" data-ps-track-event="add_to_wishlist"'
        ];

        $views[] = [
            'search' => 'if (json[\'success\']) {',
            'replace' => <<<HTML
            if (json['ps_add_to_cart']) {
                ps_dataLayer.pushData('add_to_cart', json['ps_add_to_cart']);
            }

            if (json['success']) {
            HTML
        ];

        $views[] = [
            'search' => '{% if products %}',
            'replace' => <<<HTML
            {% if ps_merge_items %}<script>ps_dataLayer.merge({{ ps_merge_items }});</script>{% endif %}
            {% if ps_view_promotion %}<script>ps_dataLayer.pushData('view_promotion', {{ ps_view_promotion }});</script>{% endif %}
            {% if ps_view_item %}<script>ps_dataLayer.pushData('view_item', {{ ps_view_item }});</script>{% endif %}
            {% if products %}
            HTML
        ];

        return $views;
    }

    public function replaceCatalogViewAccountSuccessBefore(array $args): array
    {
        $views = [];

        $views[] = [
            'search' => '{{ text_message }}',
            'replace' => <<<HTML
            {{ text_message }}
            {% if ps_sign_up %}<script>ps_dataLayer.pushData('sign_up', {{ ps_sign_up }});</script>{% endif %}
            HTML
        ];

        return $views;
    }

    public function replaceCatalogViewCheckoutSuccessBefore(array $args): array
    {
        $views = [];

        $views[] = [
            'search' => '{{ text_message }}',
            'replace' => <<<HTML
            {{ text_message }}
            {% if ps_purchase %}<script>ps_dataLayer.pushData('purchase', {{ ps_purchase }});</script>{% endif %}
            HTML
        ];

        return $views;
    }

    public function replaceCatalogViewCheckoutRegisterBefore(array $args): array
    {
        $views = [];

        $views[] = [
            'search' => 'if (json[\'success\']) {',
            'replace' => <<<HTML
            if (json['ps_sign_up']) {
                ps_dataLayer.pushData('sign_up', json['ps_sign_up']);
            }

            if (json['ps_generate_lead_newsletter']) {
                ps_dataLayer.pushData('generate_lead', json['ps_generate_lead_newsletter']);
            }

            if (json['success']) {
            HTML
        ];

        return $views;
    }

    public function replaceCatalogViewAccountAccountBefore(array $args): array
    {
        $views = [];

        $views[] = [
            'search' => '<h2>{{ text_my_account }}</h2>',
            'replace' => <<<HTML
            <h2>{{ text_my_account }}</h2>
            {% if ps_generate_lead_newsletter %}<script>ps_dataLayer.pushData('generate_lead', {{ ps_generate_lead_newsletter }});</script>{% endif %}
            HTML
        ];

        return $views;
    }

    public function replaceCatalogViewInformationContactSuccessBefore(array $args): array
    {
        $views = [];

        $views[] = [
            'search' => '{{ text_message }}',
            'replace' => <<<HTML
            {{ text_message }}
            {% if ps_generate_lead_contact_form %}<script>ps_dataLayer.pushData('generate_lead', {{ ps_generate_lead_contact_form }});</script>{% endif %}
            HTML
        ];

        return $views;
    }

    public function replaceCatalogViewCheckoutPaymentMethodBefore(array $args): array
    {
        $views = [];

        $views[] = [
            'search' => 'if (json[\'success\']) {',
            'replace' => <<<HTML
            if (json['ps_add_payment_info']) {
                ps_dataLayer.pushData('add_payment_info', json['ps_add_payment_info']);
            }

            if (json['success']) {
            HTML
        ];

        return $views;
    }

    public function replaceCatalogViewCheckoutShippingMethodBefore(array $args): array
    {
        $views = [];

        $views[] = [
            'search' => 'if (json[\'success\']) {',
            'replace' => <<<HTML
            if (json['ps_add_shipping_info']) {
                ps_dataLayer.pushData('add_shipping_info', json['ps_add_shipping_info']);
            }

            if (json['success']) {
            HTML
        ];

        return $views;
    }

    public function replaceCatalogViewCheckoutCheckoutBefore(array $args): array
    {
        $views = [];

        $views[] = [
            'search' => '<h1>{{ heading_title }}</h1>',
            'replace' => '<h1>{{ heading_title }}</h1>
            {% if ps_begin_checkout %}<script>ps_dataLayer.pushData(\'begin_checkout\', {{ ps_begin_checkout }});</script>{% endif %}'
        ];

        return $views;
    }

    public function replaceCatalogViewCheckoutCartBefore(array $args): array
    {
        $views = [];

        $views[] = [
            'search' => '<div id="shopping-cart">{{ list }}</div>',
            'replace' => '<div id="shopping-cart">{{ list }}</div>
            {% if ps_view_cart %}<script>ps_dataLayer.pushData(\'view_cart\', {{ ps_view_cart }});</script>{% endif %}'
        ];

        return $views;
    }

    /**
     * Main checkout page - list controller
     *
     * @param array $args
     * @return array
     */
    public function replaceCatalogViewCheckoutCartListBefore(array $args): array
    {
        $views = [];

        $views[] = [
            'search' => '<a href="{{ product.href }}">',
            'replace' => '<a href="{{ product.href }}" data-ps-track-id="{{ product.cart_id }}" data-ps-track-event="select_item">',
        ];

        $views[] = [
            'search' => '<input type="text" name="quantity"',
            'replace' => '<input type="text" id="product-quantity-{{ product.cart_id }}" name="quantity"',
        ];

        $views[] = [
            'search' => '<button type="submit" formaction="{{ product_edit }}"',
            'replace' => '<button type="submit" formaction="{{ product_edit }}" data-ps-track-id="{{ product.cart_id }}" data-ps-track-event="update_cart"',
        ];

        $views[] = [
            'search' => '<button type="submit" formaction="{{ product_remove }}"',
            'replace' => '<button type="submit" formaction="{{ product_remove }}" data-ps-track-id="{{ product.cart_id }}" data-ps-track-event="remove_from_cart"',
        ];

        $views[] = [
            'search' => '<div class="table-responsive">',
            'replace' => '{% if ps_merge_items %}<script>ps_dataLayer.merge({{ ps_merge_items }});</script>{% endif %}
            <div class="table-responsive">'
        ];

        return $views;
    }

    /**
     * Dropdown cart button list
     *
     * @param array $args
     * @return array
     */
    public function replaceCatalogViewCheckoutCartInfoBefore(array $args): array
    {
        $views = [];

        $views[] = [
            'search' => '<a href="{{ product.href }}">',
            'replace' => '<a href="{{ product.href }}" data-ps-track-id="{{ product.cart_id }}" data-ps-track-event="select_item">',
        ];

        $views[] = [
            'search' => '<button type="submit" data-bs-toggle="tooltip" title="{{ button_remove }}"',
            'replace' => '<button type="submit" data-bs-toggle="tooltip" title="{{ button_remove }}" data-ps-track-id="{{ product.cart_id }}" data-ps-track-event="remove_from_cart"',
        ];

        $views[] = [
            'search' => '<button type="button" data-bs-toggle="dropdown"',
            'replace' => '{% if ps_merge_items %}<script>ps_dataLayer.merge({{ ps_merge_items }});</script>{% endif %}
            <button type="button" data-bs-toggle="dropdown"'
        ];

        return $views;
    }

    public function replaceCatalogViewCheckoutConfirmBefore(array $args): array
    {
        $views = [];

        $views[] = [
            'search' => '<a href="{{ product.href }}">',
            'replace' => '<a href="{{ product.href }}" data-ps-track-id="{{ product.cart_id }}" data-ps-track-event="select_item">',
        ];

        return $views;
    }

    public function hasOptions(int $product_id): bool
    {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "product_option` WHERE `product_id` = '" . (int) $product_id . "'");

        return (int) $query->row['total'] > 0;
    }

    public function getProductOptionInfo($options, $productId): array
    {
        $option_price = 0;
        $variant = [];

        foreach ($options as $product_option_id => $value) {
            $option_query = $this->db->query("SELECT po.`product_option_id`, po.`option_id`, od.`name`, o.`type` FROM `" . DB_PREFIX . "product_option` po LEFT JOIN `" . DB_PREFIX . "option` o ON (po.`option_id` = o.`option_id`) LEFT JOIN `" . DB_PREFIX . "option_description` od ON (o.`option_id` = od.`option_id`) WHERE po.`product_option_id` = '" . (int) $product_option_id . "' AND po.`product_id` = '" . (int) $productId . "' AND od.`language_id` = '" . (int) $this->config->get('config_language_id') . "'");

            if ($option_query->num_rows) {
                if ($option_query->row['type'] == 'select' || $option_query->row['type'] == 'radio') {
                    $option_value_query = $this->db->query("SELECT pov.`option_value_id`, ovd.`name`, pov.`quantity`, pov.`subtract`, pov.`price`, pov.`price_prefix`, pov.`points`, pov.`points_prefix`, pov.`weight`, pov.`weight_prefix` FROM `" . DB_PREFIX . "product_option_value` pov LEFT JOIN `" . DB_PREFIX . "option_value` ov ON (pov.`option_value_id` = ov.`option_value_id`) LEFT JOIN `" . DB_PREFIX . "option_value_description` ovd ON (ov.`option_value_id` = ovd.`option_value_id`) WHERE pov.`product_option_value_id` = '" . (int) $value . "' AND pov.`product_option_id` = '" . (int) $product_option_id . "' AND ovd.`language_id` = '" . (int) $this->config->get('config_language_id') . "'");

                    if ($option_value_query->num_rows) {
                        if ($option_value_query->row['price_prefix'] == '+') {
                            $option_price += $option_value_query->row['price'];
                        } elseif ($option_value_query->row['price_prefix'] == '-') {
                            $option_price -= $option_value_query->row['price'];
                        }

                        $variant[] = html_entity_decode($option_query->row['name'] . ': ' . $option_value_query->row['name'], ENT_QUOTES, 'UTF-8');
                    }
                } elseif ($option_query->row['type'] == 'checkbox' && is_array($value)) {
                    foreach ($value as $product_option_value_id) {
                        $option_value_query = $this->db->query("SELECT pov.`option_value_id`, pov.`quantity`, pov.`subtract`, pov.`price`, pov.`price_prefix`, pov.`points`, pov.`points_prefix`, pov.`weight`, pov.`weight_prefix`, ovd.`name` FROM `" . DB_PREFIX . "product_option_value` `pov` LEFT JOIN `" . DB_PREFIX . "option_value_description` ovd ON (pov.`option_value_id` = ovd.option_value_id) WHERE pov.product_option_value_id = '" . (int) $product_option_value_id . "' AND pov.product_option_id = '" . (int) $product_option_id . "' AND ovd.language_id = '" . (int) $this->config->get('config_language_id') . "'");

                        if ($option_value_query->num_rows) {
                            if ($option_value_query->row['price_prefix'] == '+') {
                                $option_price += $option_value_query->row['price'];
                            } elseif ($option_value_query->row['price_prefix'] == '-') {
                                $option_price -= $option_value_query->row['price'];
                            }

                            $variant[] = html_entity_decode($option_query->row['name'] . ': ' . $option_value_query->row['name'], ENT_QUOTES, 'UTF-8');
                        }
                    }
                } elseif ($option_query->row['type'] == 'text' || $option_query->row['type'] == 'textarea' || $option_query->row['type'] == 'file' || $option_query->row['type'] == 'date' || $option_query->row['type'] == 'datetime' || $option_query->row['type'] == 'time') {
                    $variant[] = html_entity_decode($option_query->row['name'] . ': ' . $value, ENT_QUOTES, 'UTF-8');
                }

            }
        }

        return [
            'option_price' => $option_price,
            'variant' => $variant,
        ];
    }

    public function getProductDiscount($productId, $quantity)
    {
        $product_discount_query = $this->db->query("SELECT `price` FROM `" . DB_PREFIX . "product_discount` WHERE `product_id` = '" . (int) $productId . "' AND `customer_group_id` = '" . (int) $this->config->get('config_customer_group_id') . "' AND `quantity` <= '" . (int) $quantity . "' AND ((`date_start` = '0000-00-00' OR `date_start` < NOW()) AND (`date_end` = '0000-00-00' OR `date_end` > NOW())) ORDER BY `quantity` DESC, `priority` ASC, `price` ASC LIMIT 1");

        if ($product_discount_query->num_rows) {
            return $product_discount_query->row['price'];
        }

        return false;
    }

    public function getProductSpecial($productId)
    {
        $product_special_query = $this->db->query("SELECT `price` FROM `" . DB_PREFIX . "product_special` WHERE `product_id` = '" . (int) $productId . "' AND `customer_group_id` = '" . (int) $this->config->get('config_customer_group_id') . "' AND ((`date_start` = '0000-00-00' OR `date_start` < NOW()) AND (`date_end` = '0000-00-00' OR `date_end` > NOW())) ORDER BY `priority` ASC, `price` ASC LIMIT 1");

        if ($product_special_query->num_rows) {
            return $product_special_query->row['price'];
        }

        return false;
    }

    public function getProductScubscription($productId, $subscriptionPlanId)
    {
        $subscription_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "product_subscription` ps LEFT JOIN `" . DB_PREFIX . "subscription_plan` sp ON (ps.`subscription_plan_id` = sp.`subscription_plan_id`) LEFT JOIN `" . DB_PREFIX . "subscription_plan_description` spd ON (sp.`subscription_plan_id` = spd.`subscription_plan_id`) WHERE ps.`product_id` = '" . (int) $productId . "' AND ps.`subscription_plan_id` = '" . (int) $subscriptionPlanId . "' AND ps.`customer_group_id` = '" . (int) $this->config->get('config_customer_group_id') . "' AND spd.`language_id` = '" . (int) $this->config->get('config_language_id') . "' AND sp.`status` = '1'");

        if ($subscription_query->num_rows) {
            return $subscription_query->row;
        }

        return false;
    }

    public function getManufacturerNameByProductId($product_id)
    {
        $query = $this->db->query("
            SELECT m.name
            FROM " . DB_PREFIX . "manufacturer m
            INNER JOIN " . DB_PREFIX . "product p ON m.manufacturer_id = p.manufacturer_id
            WHERE p.product_id = '" . (int) $product_id . "'
            LIMIT 1
        ");

        if ($query->num_rows) {
            return ['name' => $query->row['name']];
        }

        return false;
    }

    public function getCategories(int $product_id): array
    {
        $product_category_data = [];

        $query = $this->db->query("SELECT `category_id` FROM `" . DB_PREFIX . "product_to_category` WHERE `product_id` = '" . (int) $product_id . "'");

        foreach ($query->rows as $result) {
            $product_category_data[] = $result['category_id'];
        }

        return $product_category_data;
    }

    public function getCategoryType1(int $category_id): array
    {
        $query = $this->db->query("SELECT
            DISTINCT *,
            (SELECT cd1.`name` FROM `" . DB_PREFIX . "category_path` cp LEFT JOIN `" . DB_PREFIX . "category_description` cd1 ON (cp.`path_id` = cd1.`category_id`) WHERE cp.`category_id` = c.`category_id` AND cd1.`language_id` = '" . (int) $this->config->get('config_language_id') . "' ORDER BY cp.`level` DESC LIMIT 1) AS `last_category_name`
        FROM `" . DB_PREFIX . "category` c
        LEFT JOIN `" . DB_PREFIX . "category_description` cd2 ON (c.`category_id` = cd2.`category_id`)
        WHERE c.`category_id` = '" . (int) $category_id . "' AND cd2.`language_id` = '" . (int) $this->config->get('config_language_id') . "'");

        return $query->row;
    }

    public function getCategoryType2(int $category_id): array
    {
        $query = $this->db->query("SELECT
                DISTINCT *,
                (SELECT GROUP_CONCAT(cd1.`name` ORDER BY `level` SEPARATOR ' &gt; ') FROM `" . DB_PREFIX . "category_path` cp LEFT JOIN `" . DB_PREFIX . "category_description` cd1 ON (cp.`path_id` = cd1.`category_id` AND cp.`category_id` != cp.`path_id`) WHERE cp.`category_id` = c.`category_id` AND cd1.`language_id` = '" . (int) $this->config->get('config_language_id') . "' GROUP BY cp.`category_id`) AS `path`
            FROM `" . DB_PREFIX . "category` c
            LEFT JOIN `" . DB_PREFIX . "category_description` cd2 ON (c.`category_id` = cd2.`category_id`)
            WHERE c.`category_id` = '" . (int) $category_id . "' AND cd2.`language_id` = '" . (int) $this->config->get('config_language_id') . "'");

        return $query->row;
    }

    public function getCategoryType3(int $category_id): array
    {
        $query = $this->db->query("SELECT cd1.`name`
            FROM `" . DB_PREFIX . "category_path` cp
            LEFT JOIN `" . DB_PREFIX . "category_description` cd1 ON (cp.`path_id` = cd1.`category_id`)
            WHERE cp.`category_id` = '" . (int) $category_id . "' AND cd1.`language_id` = '" . (int) $this->config->get('config_language_id') . "'
            ORDER BY cp.`level` ASC");

        return $query->rows;
    }
}
