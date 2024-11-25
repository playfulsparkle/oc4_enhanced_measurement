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
            'search' => '<div class="container-fluid">',
            'replace' => '<div class="container-fluid">
            {% if not ps_is_refundable %}<div class="alert alert-info"><i class="fa-solid fa-circle-exclamation"></i> {{ ps_text_product_already_refunded }}</div>{% endif %}',
            'positions' => [2],
        ];

        $views[] = [
            'search' => '<div class="float-end">',
            'replace' => '<div class="float-end">
            <button type="button" id="ps-refund-selected-button" data-bs-toggle="tooltip" title="{{ ps_button_refund_selected }}" class="btn btn-primary"{% if not order_id or not ps_is_refundable %} disabled{% endif %}><i class="fa-solid fa-check-circle"></i> {{ ps_button_refund_selected }}</button>
            <button type="button" id="ps-refund-all-button" data-bs-toggle="tooltip" title="{{ ps_button_refund_all }}" class="btn btn-primary"{% if not order_id or not ps_is_refundable %} disabled{% endif %}><i class="fa-solid fa-sync"></i> {{ ps_button_refund_all }}</button> '
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
                    <input type="number" name="refund[{{ order_product.order_product_id }}]" value="0" min="0" max="{{ order_product.quantity }}" class="form-control" style="flex: 0 1 30%;"{% if not order_id or not ps_is_refundable %} disabled{% endif %}>
                    <button type="button" id="ps-refund-button-{{ order_product.order_product_id }}" data-bs-toggle="tooltip" title="{{ ps_button_refund }}" class="btn btn-primary" disabled><i class="fa-solid fa-check-circle"></i></button>
                </div>
            </td>',
            'positions' => [1],
        ];

        $views[] = [
            'search' => '<td colspan="5"></td>',
            'replace' => '<td colspan="6"></td>',
        ];

        $views[] = [
            'search' => "product['quantity'] + '</td>';",
            'replace' => "product['quantity'] + '</td>';
            html += '  <td class=\"text-start\"></td>';",
        ];

        $views[] = [
            'search' => "$('#form-product-add').prepend('<div class=\"alert alert-success",
            'replace' => "$('#ps-refund-selected-button').prop('disabled', true);
            $('#ps-refund-all-button').prop('disabled', true);
            $('#form-product-add').prepend('<div class=\"alert alert-success",
        ];

        if ($args['ps_is_refundable']) {
            $views[] = [
                'search' => '<script type="text/javascript"><!--',
                'replace' => <<<HTML
                <script type="text/javascript"><!--
                    var ps_refund_btns = $('button[id^="ps-refund-button"], #ps-refund-selected-button, #ps-refund-all-button');

                    $('input[name^="refund"]').on('change', function() {
                        $(this).next('button').prop('disabled', (parseInt($(this).val()) ?? 0) <= 0);
                    });

                    $('#ps-refund-all-button').on('click', function () {
                        var formData = new FormData();

                        formData.append('order_id', {{ order_id }});

                        var refundInputs = $('input[name^="refund"]').each(function () {
                            formData.append($(this).attr('name'), $(this).attr('max'));
                        });

                        sendRefundData(refundInputs, formData);
                    });

                    $('#ps-refund-selected-button').on('click', function () {
                        var formData = new FormData();
                        var counter = 0;

                        var refundInputs = $('input[name^="refund"]').each(function () {
                            if ($(this).val() > 0) {
                                formData.append($(this).attr('name'), $(this).val());
                                counter++;
                            }
                        });

                        if (counter > 0) {
                            formData.append('order_id', {{ order_id }});

                            sendRefundData(refundInputs, formData);
                        } else {
                            $('#alert').prepend('<div class="alert alert-danger alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> {{ ps_error_no_refundable_selected }} <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
                        }
                    });

                    $('button[id^="ps-refund-button"]').on('click', function () {
                        var self = $(this);
                        var refundInput = self.prev('input[name^="refund"]');

                        var formData = new FormData();

                        formData.append(refundInput.attr('name'), refundInput.val());
                        formData.append('order_id', {{ order_id }});

                        sendRefundData(refundInput, formData);
                    });

                    function sendRefundData(refundInputs, formData) {
                        ps_refund_btns.prop('disabled', true);

                        fetch('index.php?route=extension/ps_enhanced_measurement/analytics/ps_enhanced_measurement.send_refund&user_token={{ user_token }}', {
                                method: 'POST',
                                body: formData
                            })
                            .then(response => { return response.json(); })
                            .then(data => {
                                if (data.error) {
                                    $('#alert').prepend('<div class="alert alert-danger alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + data.error + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');

                                    console.error(ga_response_data.statusText);
                                }

                                if (data.success) {
                                    $('#alert').prepend('<div class="alert alert-success alert-dismissible"><i class="fa-solid fa-check-circle"></i> ' + data.success + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
                                }

                                refundInputs.each(function () {
                                    $(this).val(0);
                                });
                            })
                            .catch(error => { console.error(error); });
                    }
                HTML
            ];
        }

        return $views;
    }

    public function install()
    {
        $this->db->query("
        CREATE TABLE `" . DB_PREFIX . "ps_refund_order` (
            `refund_id` int(11) NOT NULL AUTO_INCREMENT,
            `order_id` int(11) NOT NULL,
            `user_id` int(11) NOT NULL,
            `client_id` varchar(50) NOT NULL,
            `refunded` tinyint(1) NOT NULL DEFAULT 0,
            `date_added` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`refund_id`),
            KEY `order_id` (`order_id`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
        ");
    }

    public function uninstall()
    {
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "ps_refund_order`");
    }

    public function getClientIdByOrderId($orderId)
    {
        $query = $this->db->query("SELECT `user_id`, `client_id` FROM `" . DB_PREFIX . "ps_refund_order` WHERE `order_id` = '" . (int) $orderId . "' AND `client_id` != ''");

        if ($query->num_rows) {
            return $query->row;
        }

        return null;
    }

    public function isRefundableByOrderId($orderId)
    {
        $query = $this->db->query("SELECT `user_id`, `client_id` FROM `" . DB_PREFIX . "ps_refund_order` WHERE `order_id` = '" . (int) $orderId . "' AND `client_id` != '' AND `refunded` != '1'");

        return $query->num_rows > 0;
    }

    public function saveRefundedState($orderId): void
    {
        $this->db->query("UPDATE `" . DB_PREFIX . "ps_refund_order` SET `refunded` = '1' WHERE `order_id` = '" . (int) $orderId . "'");
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
