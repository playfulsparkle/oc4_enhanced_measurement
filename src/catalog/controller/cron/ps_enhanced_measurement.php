<?php
namespace Opencart\Catalog\Controller\Extension\PsEnhancedMeasurement\Cron;
/**
 * Class PsEnhancedMeasurement
 *
 * @package Opencart\Catalog\Controller\Extension\PsEnhancedMeasurement\Cron
 */
class PsEnhancedMeasurement extends \Opencart\System\Engine\Controller
{
    /**
     * @param int    $cron_id
     * @param string $code
     * @param string $cycle
     * @param string $date_added
     * @param string $date_modified
     *
     * @return void
     */
    public function index(int $cron_id, string $code, string $cycle, string $date_added, string $date_modified): void
    {
        $query = $this->db->query("SELECT c.customer_id, c.session_id, c.date_added FROM `" . DB_PREFIX . "cart` c
            LEFT JOIN `" . DB_PREFIX . "product_description` p ON c.product_id = p.product_id
            WHERE c.date_added < NOW() - INTERVAL 24 HOUR AND c.ps_disqualified_lead = 0 AND NOT EXISTS (SELECT 1 FROM `" . DB_PREFIX . "order` o WHERE o.customer_id = c.customer_id)
            GROUP BY c.customer_id, c.session_id");

        if ($query->num_rows) {
            foreach ($query->rows as $cart) {
                // $eventData = [
                //     'event' => 'disqualify_lead',
                //     'user_id' => $cart['customer_id'] ?? $cart['session_id'],
                //     'reason' => 'cart_abandoned',
                // ];

                $this->db->query("UPDATE `" . DB_PREFIX . "cart` SET ps_disqualified_lead = 1 WHERE customer_id = '" . (int) $cart['customer_id'] . "' AND session_id = '" . $this->db->escape($cart['session_id']) . "'");
            }
        }
    }
}
