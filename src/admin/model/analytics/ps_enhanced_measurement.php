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
                    <input type="number" id="ps-refund-quantity-{{ order_product.order_product_id }}" name="refund_quantity[{{ order_product.order_product_id }}]" value="0" min="0" max="{{ order_product.quantity }}" class="form-control">
                    <button type="button" id="ps-refund-button-{{ order_product.order_product_id }}" data-refund-quantity="ps-refund-quantity-{{ order_product.order_product_id }}" data-refund-order-product-id="{{ order_product.order_product_id }}" data-bs-toggle="tooltip" title="{{ ps_button_refund }}" class="btn btn-primary"><i class="fa-solid fa-reply"></i></button>
                </div>
            </td>',
            'positions' => [1],
        ];

        $views[] = [
            'search' => 'product[\'quantity\'] + \'</td>\';',
            'replace' => 'product[\'quantity\'] + \'</td>\'; html += \'<td class="text-start"></td>\';',
        ];

        $views[] = [
            'search' => '<script type="text/javascript"><!--',
            'replace' => <<<HTML
        <script type="text/javascript"><!--
            $('button[id^="ps-refund-button"]').on('click', function () {
                var quantity = $('#' + $(this).attr('data-refund-quantity')).val();
                var order_product_id =  $(this).attr('data-refund-order-product-id');

                $.ajax({
                    url: 'index.php?route=extension/ps_enhanced_measurement/analytics/ps_enhanced_measurement.sendRefund&user_token={{ user_token }}&order_id={{ order_id }}&quantity=' + quantity + '&order_product_id=' + order_product_id,
                    dataType: 'json',
                    beforeSend: function () {
                        $('#button-invoice').button('loading');
                    },
                    complete: function () {
                        $('#button-invoice').button('reset');
                    },
                    success: function (json) {
                        $('.alert-dismissible').remove();

                        if (json['error']) {
                            $('#alert').prepend('<div class="alert alert-danger alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + json['error'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
                        }

                        if (json['success']) {
                            $('#alert').prepend('<div class="alert alert-success alert-dismissible"><i class="fa-solid fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
                        }
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        console.log(thrownError + "\\r\\n" + xhr.statusText + "\\r\\n" + xhr.responseText);
                    }
                });
            });
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
}
