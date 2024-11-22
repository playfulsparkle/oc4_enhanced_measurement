<?php
namespace Opencart\Admin\Model\Extension\PsEnhancedMeasurement\Analytics;
/**
 * Class PsEnhancedMeasurement
 *
 * @package Opencart\Admin\Model\Extension\PsEnhancedMeasurement\Analytics
 */
class PsEnhancedMeasurement extends \Opencart\System\Engine\Model
{
    /**
     * @param array $args
     *
     * @return array
     */
    public function replaceAdminViewSaleOrderInfoBefore(array $args): array
    {
        $views = [];

        $views[] = [
            'search' => '<div class="float-end">',
            'replace' => '<div class="float-end">
            <button type="button" id="ps-refund-all-button" data-bs-toggle="tooltip" title="{{ ps_button_refund_all }}" class="btn btn-primary"{% if not order_id %} disabled{% endif %}><i class="fa-solid fa-reply"></i> {{ ps_button_refund_all }}</button>
            <button type="button" id="ps-resubmit-order-button" data-bs-toggle="tooltip" title="{{ ps_button_resubmit_order }}" class="btn btn-primary"{% if not order_id %} disabled{% endif %}><i class="fa-solid fa-rotate-right"></i> {{ ps_button_resubmit_order }}</button> '
        ];

        $views[] = [
            'search' => '<td class="text-end">{{ column_quantity }}</td>',
            'replace' => '<td class="text-end">{{ column_quantity }}</td>
            <td class="text-start">{{ ps_column_refund_quantity }}</td>'
        ];

        $views[] = [
            'search' => '<td class="text-end">{{ order_product.quantity }}</td>',
            'replace' => '<td class="text-end">{{ order_product.quantity }}</td>
            <td class="text-start">
                <div class="input-group">
                    <div class="input-group-text">{{ ps_text_refund_quantity }}</div>
                    <input type="number" name="refund_quantity[{{ order_product.order_product_id }}]" value="0" min="0" max="{{ order_product.quantity }}" class="form-control" style="flex: 0 1 30%;">
                    <button type="button" id="ps-refund-button-{{ order_product.order_product_id }}" data-refund-order-product-id="{{ order_product.order_product_id }}" data-bs-toggle="tooltip" title="{{ ps_button_refund }}" class="btn btn-primary" disabled><i class="fa-solid fa-reply"></i></button>
                </div>
            </td>',
            'positions' => [1],
        ];

        $views[] = [
            'search' => 'product[\'quantity\'] + \'</td>\';',
            'replace' => 'product[\'quantity\'] + \'</td>\'; html += \'<td class="text-start">&nbsp;</td>\';',
        ];

        $views[] = [
            'search' => '<script type="text/javascript"><!--',
            'replace' => <<<HTML
            <script type="text/javascript"><!--
            {% if ps_google_tag_id and ps_mp_api_secret %}
                $('input[name^="refund_quantity"]').on('change', function() {
                    var inputValue = parseInt($(this).val()) ?? 0;

                    $(this).next('button').prop('disabled', inputValue <= 0);
                });

                $('#ps-resubmit-order-button').on('click', function () {
                    var element = $(this);

                    element.prop('disabled', true);

                    fetch('index.php?route=extension/ps_enhanced_measurement/analytics/ps_enhanced_measurement.reSubmitOrder&user_token={{ user_token }}&order_id={{ order_id }}')
                        .then(response => { return response.json(); })
                        .then(data => {
                            if (data.error) {
                                $('#alert').prepend('<div class="alert alert-danger alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + data.error + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
                            }

                            if (data.event_data) {
                                return fetch("{{ ps_ga_server_url }}?measurement_id={{ ps_google_tag_id }}&api_secret={{ ps_mp_api_secret }}", { method: "POST", body: JSON.stringify(data.event_data) });
                            }
                        })
                        .then(ga_response => { return ga_response; })
                        .then(ga_response_data => {
                            if (ga_response_data.status) {
                                $('#alert').prepend('<div class="alert alert-success alert-dismissible"><i class="fa-solid fa-check-circle"></i> {{ ps_text_order_resubmit_success }} <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
                            } else {
                                $('#alert').prepend('<div class="alert alert-danger alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> {{ ps_error_order_resubmit }} <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');

                                console.error(ga_response_data.statusText);
                            }

                            element.prop('disabled', false);
                        })
                        .catch(error => { console.error(error); });
                });

                $('button[id^="ps-refund-button"], #ps-refund-all-button').on('click', function () {
                    var element = $(this);
                    var quantity = element.prev('input[name^="refund_quantity"]');
                    var order_product_id =  element.attr('data-refund-order-product-id');

                    var url = 'index.php?route=extension/ps_enhanced_measurement/analytics/ps_enhanced_measurement.sendRefund&user_token={{ user_token }}&order_id={{ order_id }}';

                    if (quantity.length !== 0 && typeof order_product_id !== 'undefined') {
                        url += '&quantity=' + quantity.val() + '&order_product_id=' + order_product_id;
                    }

                    element.prop('disabled', true);

                    fetch(url)
                        .then(response => { return response.json(); })
                        .then(data => {
                            if (data.error) {
                                $('#alert').prepend('<div class="alert alert-danger alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + data.error + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
                            }

                            if (data.event_data) {
                                return fetch("{{ ps_ga_server_url }}?measurement_id={{ ps_google_tag_id }}&api_secret={{ ps_mp_api_secret }}", { method: "POST", body: JSON.stringify(data.event_data) });
                            }
                        })
                        .then(ga_response => { return ga_response; })
                        .then(ga_response_data => {
                            if (ga_response_data.status) {
                                $('#alert').prepend('<div class="alert alert-success alert-dismissible"><i class="fa-solid fa-check-circle"></i> {{ ps_text_refund_successfully_sent }} <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
                            } else {
                                $('#alert').prepend('<div class="alert alert-danger alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> {{ ps_error_refund_send }} <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');

                                console.error(ga_response_data.statusText);
                            }

                            if (quantity.length !== 0) {
                                quantity.val(0);
                            } else {
                                element.prop('disabled', false);
                            }
                        })
                        .catch(error => { console.error(error); });
                });
            {% endif %}
            HTML
        ];

        return $views;
    }

    public function install()
    {
        $this->db->query("
        CREATE TABLE `" . DB_PREFIX . "ps_refund_order` (
            `refund_id` int(11) NOT NULL AUTO_INCREMENT,
            `order_id` int(11) NOT NULL,
            `client_id` varchar(50) NOT NULL,
            PRIMARY KEY (`refund_id`),
            KEY `order_id` (`order_id`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
        ");
    }

    public function uninstall()
    {
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "ps_refund_order`");
    }

    public function getClientIdByOrderId($orderId): string
    {
        $query = $this->db->query("SELECT `client_id` FROM `" . DB_PREFIX . "ps_refund_order` WHERE `order_id` = '" . (int) $orderId . "'");

        if ($query->num_rows) {
            return $query->row['client_id'];
        }

        return '';
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
}
