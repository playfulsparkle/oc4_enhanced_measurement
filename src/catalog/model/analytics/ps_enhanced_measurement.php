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
    {% if ps_enhanced_measurement_status %}
    <script>
        var ps_dataLayer = {
            data: {},
            merge: function(data) {
                this.data = Object.assign({}, this.data, data);
            },
            push: function(eventName, data) {
                {% if ps_enhanced_measurement_implementation == 'gtag' %}
                    gtag('event', eventName, data);
                {% elseif ps_enhanced_measurement_implementation == 'gtm' %}
                    dataLayer.push({ ecommerce: null });
                    dataLayer.push({ event: eventName, ...data });
                {% endif %}
            }
        };
    </script>
    {% endif %}
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
            'replace' => '<a href="{{ href }}" data-ps-track-id="{{ product_id }}" data-ps-track-event="select_item">'
        ];

        if (false === $args['has_options']) {
            $views[] = [
                'search' => '<button type="submit" formaction="{{ add_to_cart }}"',
                'replace' => '<button type="submit" formaction="{{ add_to_cart }}" data-ps-track-id="{{ product_id }}" data-ps-track-event="add_to_cart"'
            ];
        }

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
            {% if ps_view_item_list %}
            <script>
                ps_dataLayer.merge({{ ps_all_items }});
                ps_dataLayer.push('view_item_list', {{ ps_view_item_list }});
            </script>
            {% endif %}
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
            {% if ps_view_item_list %}
            <script>
                ps_dataLayer.merge({{ ps_all_items }});
                ps_dataLayer.push('view_item_list', {{ ps_view_item_list }});
            </script>
            {% endif %}
            {% if products %}
            HTML
        ];

        return $views;
    }

    public function replaceCatalogViewProductProductBefore(array $args): array
    {
        $views = [];

        $views[] = [
            'search' => '{% if products %}',
            'replace' => <<<HTML
            {% if ps_view_item %}
            <script>
                ps_dataLayer.merge({{ ps_all_items }});
                ps_dataLayer.push('view_item', {{ ps_view_item }});
            </script>
            {% endif %}
            {% if products %}
            HTML
        ];

        return $views;
    }

    public function hasOptions(int $product_id): bool
    {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "product_option` WHERE `product_id` = '" . (int) $product_id . "'");

        return (int) $query->row['total'] > 0;
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
