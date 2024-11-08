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
    public function replaceHeaderViews(array $args): array
    {
        $views = [];

        $views[] = [
            'search' => '<body>',
            'replace' => <<<HTML
                <body>
                <!-- Google Tag Manager (noscript) -->
                <noscript>
                    <iframe src="https://www.googletagmanager.com/ns.html?id={{ ps_enhanced_measurement_gtm_id }}" height="0" width="0" style="display:none; visibility:hidden;"></iframe>
                </noscript>
                <!-- End Google Tag Manager (noscript) -->
            HTML
        ];

        return $views;
    }

    public function replaceProductThumbViews(array $args): array
    {
        $views = [];

        $views[] = [
            'search' => '<a href="{{ href }}">',
            'replace' => '<a href="{{ href }}" data-ps-track-id="{{ product_id }}" data-ps-track-event="select_item">'
        ];

        return $views;
    }

    public function replaceBannerViews(array $args): array
    {
        $views = [];

        $views[] = [
            'search' => '<a href="{{ banner.link }}">',
            'replace' => '<a href="{{ banner.link }}"{% if banner.product_id %} data-ps-track-id="{{ banner.product_id }}" data-ps-track-event="select_promotion"{% endif %}>'
        ];

        $views[] = [
            'search' => '<script type="text/javascript">',
            'replace' => <<<HTML
                <script type="text/javascript">
                    {% for carousel in banners|batch(items) %}
                        {% for banner in carousel %}
                            {% if banner.product_id %}
                                var ps_datalayer_{{ banner.product_id }} = {{ banner.datalayer }};
                            {% endif %}
                        {% endfor %}
                    {% endfor %}
                </script>
                <script type="text/javascript">
            HTML
        ];

        return $views;
    }

    public function replaceCartItemViews(array $args): array
    {
        $views = [];

        $views[] = [
            'search' => '<a href="{{ product.href }}">',
            'replace' => '<a href="{{ product.href }}" data-ps-track-id="{{ product_id_list[product.cart_id] }}" data-ps-track-event="select_item">'
        ];

        $views[] = [
            'search' => 'title="{{ button_remove }}" class="btn btn-danger"',
            'replace' => 'title="{{ button_remove }}" class="btn btn-danger" data-ps-track-id="{{ product_id_list[product.cart_id] }}" data-ps-track-event="remove_from_cart"'
        ];

        return $views;
    }

    public function replaceCompareAndWishlistViews(array $args): array
    {
        $views = [];

        $views[] = [
            'search' => '<a href="{{ product.href }}">',
            'replace' => '<a href="{{ product.href }}" data-ps-track-id="{{ product.product_id }}" data-ps-track-event="select_item">'
        ];

        return $views;
    }

    public function replaceCatalogViewProductCategoryViews(array $args): array
    {
        $views = [];

        $views[] = [
            'search' => '{% if products %}',
            'replace' => <<<HTML
            {% if ps_enhanced_measurement %}
            <script>
                {{ ps_enhanced_measurement }}
            </script>
            {% endif %}
            {% if ps_products %}
            <script>
                {% for product_id, item in ps_products %}
                var ps_datalayer_{{ product_id }} = {{ item }};
                {% endfor %}
            </script>
            {% endif %}
            {% if products %}
            HTML
        ];

        return $views;
    }

    public function replaceCatalogViewProductProductViews(array $args): array
    {
        $views = [];

        $views[] = [
            'search' => '{{ content_top }}',
            'replace' => <<<HTML
            {{ content_top }}
            {% if ps_enhanced_measurement %}
            <script>
                {{ ps_enhanced_measurement }}
            </script>
            {% endif %}
            {% if ps_products %}
            <script>
                {% for product_id, item in ps_products %}
                var ps_datalayer_{{ product_id }} = {{ item }};
                {% endfor %}
            </script>
            {% endif %}
            HTML
        ];

        return $views;
    }

    public function replaceCatalogViewProductSearchViews(array $args): array
    {
        $views = [];

        $views[] = [
            'search' => '{% if products %}',
            'replace' => <<<HTML
            {% if ps_enhanced_measurement %}
            <script>
                {{ ps_enhanced_measurement }}
            </script>
            {% endif %}
            {% if ps_products %}
            <script>
                {% for product_id, item in ps_products %}
                var ps_datalayer_{{ product_id }} = {{ item }};
                {% endfor %}
            </script>
            {% endif %}
            {% if products %}
            HTML
        ];

        return $views;
    }

    public function replaceCatalogViewProductCompareViews(array $args): array
    {
        $views = [];

        $views[] = [
            'search' => '{% if products %}',
            'replace' => <<<HTML
            {% if ps_enhanced_measurement %}
            <script>
                {{ ps_enhanced_measurement }}
            </script>
            {% endif %}
            {% if ps_products %}
            <script>
                {% for product_id, item in ps_products %}
                var ps_datalayer_{{ product_id }} = {{ item }};
                {% endfor %}
            </script>
            {% endif %}
            {% if products %}
            HTML
        ];

        $views[] = [
            'search' => '<a href="{{ product.href }}">',
            'replace' => '<a href="{{ product.href }}" data-ps-track-id="{{ product.product_id }}" data-ps-track-event="select_item">'
        ];

        return $views;
    }

    /**
     * @param int $product_id
     *
     * @return array
     */
    public function getCategories(int $product_id): array
    {
        $product_category_data = [];

        $query = $this->db->query("SELECT `category_id` FROM `" . DB_PREFIX . "product_to_category` WHERE `product_id` = '" . (int) $product_id . "'");

        foreach ($query->rows as $result) {
            $product_category_data[] = $result['category_id'];
        }

        return $product_category_data;
    }

    /**
     * @param int $category_id
     *
     * @return array
     */
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

    /**
     * @param int $category_id
     *
     * @return array
     */
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

    /**
     * @param int $category_id
     *
     * @return array
     */
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
