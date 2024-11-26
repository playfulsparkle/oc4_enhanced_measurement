<?php
namespace Opencart\Admin\Controller\Extension\PsEnhancedMeasurement\Analytics;
/**
 * Class PsEnhancedMeasurement
 *
 * @package Opencart\Admin\Controller\Extension\PsEnhancedMeasurement\Analytics
 */
class PsEnhancedMeasurement extends \Opencart\System\Engine\Controller
{
    /**
     * @var string The support email address.
     */
    const EXTENSION_EMAIL = 'support@playfulsparkle.com';

    /**
     * @var string The documentation URL for the extension.
     */
    const EXTENSION_DOC = 'https://playfulsparkle.com/en-us/resources/downloads/';

    /**
     * @return void
     */
    public function index(): void
    {
        $this->load->language('extension/ps_enhanced_measurement/analytics/ps_enhanced_measurement');

        $this->document->setTitle($this->language->get('heading_title'));

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true),
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_extension'),
            'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=analytics', true),
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/analytics/ps_enhanced_measurement', 'user_token=' . $this->session->data['user_token'] . '&store_id=' . $this->request->get['store_id'], true),
        ];


        $separator = version_compare(VERSION, '4.0.2.0', '>=') ? '.' : '|';

        $data['action'] = $this->url->link('extension/ps_enhanced_measurement/analytics/ps_enhanced_measurement' . $separator . 'save', 'user_token=' . $this->session->data['user_token'] . '&store_id=' . $this->request->get['store_id']);
        $data['fix_event_handler'] = $this->url->link('extension/ps_enhanced_measurement/analytics/ps_enhanced_measurement' . $separator . 'fixEventHandler', 'user_token=' . $this->session->data['user_token']);
        $data['back'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=analytics');

        $data['analytics_ps_enhanced_measurement_status'] = (bool) $this->config->get('analytics_ps_enhanced_measurement_status');
        $data['analytics_ps_enhanced_measurement_implementation'] = $this->config->get('analytics_ps_enhanced_measurement_implementation');
        $data['analytics_ps_enhanced_measurement_gtm_id'] = $this->config->get('analytics_ps_enhanced_measurement_gtm_id');
        $data['analytics_ps_enhanced_measurement_google_tag_id'] = $this->config->get('analytics_ps_enhanced_measurement_google_tag_id');
        $data['analytics_ps_enhanced_measurement_mp_api_secret'] = $this->config->get('analytics_ps_enhanced_measurement_mp_api_secret');
        $data['analytics_ps_enhanced_measurement_gtag_debug_mode'] = $this->config->get('analytics_ps_enhanced_measurement_gtag_debug_mode');
        $data['analytics_ps_enhanced_measurement_item_id'] = $this->config->get('analytics_ps_enhanced_measurement_item_id');
        $data['analytics_ps_enhanced_measurement_item_category_option'] = $this->config->get('analytics_ps_enhanced_measurement_item_category_option');
        $data['analytics_ps_enhanced_measurement_item_price_tax'] = $this->config->get('analytics_ps_enhanced_measurement_item_price_tax');
        $data['analytics_ps_enhanced_measurement_affiliation'] = $this->config->get('analytics_ps_enhanced_measurement_affiliation');
        $data['analytics_ps_enhanced_measurement_location_id'] = $this->config->get('analytics_ps_enhanced_measurement_location_id');
        $data['analytics_ps_enhanced_measurement_currency'] = $this->config->get('analytics_ps_enhanced_measurement_currency');
        $data['analytics_ps_enhanced_measurement_debug_mode'] = $this->config->get('analytics_ps_enhanced_measurement_debug_mode');
        $data['analytics_ps_enhanced_measurement_tracking_delay'] = $this->config->get('analytics_ps_enhanced_measurement_tracking_delay');
        $data['analytics_ps_enhanced_measurement_track_user_id'] = $this->config->get('analytics_ps_enhanced_measurement_track_user_id');
        $data['analytics_ps_enhanced_measurement_track_generate_lead'] = $this->config->get('analytics_ps_enhanced_measurement_track_generate_lead');
        $data['analytics_ps_enhanced_measurement_track_qualify_lead'] = $this->config->get('analytics_ps_enhanced_measurement_track_qualify_lead');
        $data['analytics_ps_enhanced_measurement_track_sign_up'] = $this->config->get('analytics_ps_enhanced_measurement_track_sign_up');
        $data['analytics_ps_enhanced_measurement_track_login'] = $this->config->get('analytics_ps_enhanced_measurement_track_login');
        $data['analytics_ps_enhanced_measurement_track_add_to_wishlist'] = $this->config->get('analytics_ps_enhanced_measurement_track_add_to_wishlist');
        $data['analytics_ps_enhanced_measurement_track_add_to_cart'] = $this->config->get('analytics_ps_enhanced_measurement_track_add_to_cart');
        $data['analytics_ps_enhanced_measurement_track_remove_from_cart'] = $this->config->get('analytics_ps_enhanced_measurement_track_remove_from_cart');
        $data['analytics_ps_enhanced_measurement_track_search'] = $this->config->get('analytics_ps_enhanced_measurement_track_search');
        $data['analytics_ps_enhanced_measurement_track_view_item_list'] = $this->config->get('analytics_ps_enhanced_measurement_track_view_item_list');
        $data['analytics_ps_enhanced_measurement_track_select_item'] = $this->config->get('analytics_ps_enhanced_measurement_track_select_item');
        $data['analytics_ps_enhanced_measurement_track_view_item'] = $this->config->get('analytics_ps_enhanced_measurement_track_view_item');
        $data['analytics_ps_enhanced_measurement_track_select_promotion'] = $this->config->get('analytics_ps_enhanced_measurement_track_select_promotion');
        $data['analytics_ps_enhanced_measurement_track_view_promotion'] = $this->config->get('analytics_ps_enhanced_measurement_track_view_promotion');
        $data['analytics_ps_enhanced_measurement_track_view_cart'] = $this->config->get('analytics_ps_enhanced_measurement_track_view_cart');
        $data['analytics_ps_enhanced_measurement_track_begin_checkout'] = $this->config->get('analytics_ps_enhanced_measurement_track_begin_checkout');
        $data['analytics_ps_enhanced_measurement_track_add_payment_info'] = $this->config->get('analytics_ps_enhanced_measurement_track_add_payment_info');
        $data['analytics_ps_enhanced_measurement_track_add_shipping_info'] = $this->config->get('analytics_ps_enhanced_measurement_track_add_shipping_info');
        $data['analytics_ps_enhanced_measurement_track_purchase'] = $this->config->get('analytics_ps_enhanced_measurement_track_purchase');
        $data['analytics_ps_enhanced_measurement_gcm_status'] = (bool) $this->config->get('analytics_ps_enhanced_measurement_gcm_status');
        $data['analytics_ps_enhanced_measurement_ad_storage'] = (bool) $this->config->get('analytics_ps_enhanced_measurement_ad_storage');
        $data['analytics_ps_enhanced_measurement_ad_user_data'] = (bool) $this->config->get('analytics_ps_enhanced_measurement_ad_user_data');
        $data['analytics_ps_enhanced_measurement_ad_personalization'] = (bool) $this->config->get('analytics_ps_enhanced_measurement_ad_personalization');
        $data['analytics_ps_enhanced_measurement_analytics_storage'] = (bool) $this->config->get('analytics_ps_enhanced_measurement_analytics_storage');
        $data['analytics_ps_enhanced_measurement_functionality_storage'] = (bool) $this->config->get('analytics_ps_enhanced_measurement_functionality_storage');
        $data['analytics_ps_enhanced_measurement_personalization_storage'] = (bool) $this->config->get('analytics_ps_enhanced_measurement_personalization_storage');
        $data['analytics_ps_enhanced_measurement_security_storage'] = (bool) $this->config->get('analytics_ps_enhanced_measurement_security_storage');
        $data['analytics_ps_enhanced_measurement_wait_for_update'] = (int) $this->config->get('analytics_ps_enhanced_measurement_wait_for_update');
        $data['analytics_ps_enhanced_measurement_ads_data_redaction'] = (bool) $this->config->get('analytics_ps_enhanced_measurement_ads_data_redaction');
        $data['analytics_ps_enhanced_measurement_url_passthrough'] = (bool) $this->config->get('analytics_ps_enhanced_measurement_url_passthrough');

        if ($this->config->get('analytics_ps_enhanced_measurement_close_convert_lead_status')) {
            $data['analytics_ps_enhanced_measurement_close_convert_lead_status'] = $this->config->get('analytics_ps_enhanced_measurement_close_convert_lead_status');
        } else {
            $data['analytics_ps_enhanced_measurement_close_convert_lead_status'] = [];
        }

        if ($this->config->get('analytics_ps_enhanced_measurement_close_unconvert_lead_status')) {
            $data['analytics_ps_enhanced_measurement_close_unconvert_lead_status'] = $this->config->get('analytics_ps_enhanced_measurement_close_unconvert_lead_status');
        } else {
            $data['analytics_ps_enhanced_measurement_close_unconvert_lead_status'] = [];
        }

        if ($this->config->get('analytics_ps_enhanced_measurement_disqualify_lead_status')) {
            $data['analytics_ps_enhanced_measurement_disqualify_lead_status'] = $this->config->get('analytics_ps_enhanced_measurement_disqualify_lead_status');
        } else {
            $data['analytics_ps_enhanced_measurement_disqualify_lead_status'] = [];
        }

        $data['analytics_ps_enhanced_measurement_gcm_profiles'] = 0;

        if (
            !$data['analytics_ps_enhanced_measurement_ad_storage'] &&
            !$data['analytics_ps_enhanced_measurement_ad_user_data'] &&
            !$data['analytics_ps_enhanced_measurement_ad_personalization'] &&
            !$data['analytics_ps_enhanced_measurement_analytics_storage'] &&
            $data['analytics_ps_enhanced_measurement_functionality_storage'] &&
            $data['analytics_ps_enhanced_measurement_personalization_storage'] &&
            $data['analytics_ps_enhanced_measurement_security_storage']
        ) {
            $data['analytics_ps_enhanced_measurement_gcm_profiles'] = 1;
        }

        if (
            $data['analytics_ps_enhanced_measurement_ad_storage'] &&
            $data['analytics_ps_enhanced_measurement_ad_user_data'] &&
            !$data['analytics_ps_enhanced_measurement_ad_personalization'] &&
            $data['analytics_ps_enhanced_measurement_analytics_storage'] &&
            $data['analytics_ps_enhanced_measurement_functionality_storage'] &&
            $data['analytics_ps_enhanced_measurement_personalization_storage'] &&
            $data['analytics_ps_enhanced_measurement_security_storage']
        ) {
            $data['analytics_ps_enhanced_measurement_gcm_profiles'] = 2;
        }

        $data['text_contact'] = sprintf($this->language->get('text_contact'), self::EXTENSION_EMAIL, self::EXTENSION_EMAIL, self::EXTENSION_DOC);

        $this->load->model('localisation/order_status');

        $data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

        $data['measurement_implementations'] = [
            '' => $this->language->get('text_none'),
            'gtag' => $this->language->get('text_gtag') . ' ' . $this->language->get('text_default'),
            'gtm' => $this->language->get('text_gtm'),
        ];

        $data['item_id_options'] = [
            'product_id' => $this->language->get('text_product_id') . ' ' . $this->language->get('text_default'),
            'model' => $this->language->get('text_model'),
            'sku' => $this->language->get('text_sku'),
            'upc' => $this->language->get('text_upc'),
            'ean' => $this->language->get('text_ean'),
            'jan' => $this->language->get('text_jan'),
            'isbn' => $this->language->get('text_isbn'),
            'mpn' => $this->language->get('text_mpn'),
        ];

        $data['item_category_options'] = [
            $this->language->get('text_category_option_type_1'),
            $this->language->get('text_category_option_type_2'),
            $this->language->get('text_category_option_type_3'),
            $this->language->get('text_category_option_type_4'),
        ];

        $data['gcm_profiles'] = [
            $this->language->get('entry_custom'),
            $this->language->get('entry_strict'),
            $this->language->get('entry_balanced'),
        ];

        $this->load->model('localisation/currency');

        $currencies = $this->model_localisation_currency->getCurrencies();

        $data['currencies'] = [
            '' => $this->language->get('text_multi_currency')
        ];

        foreach ($currencies as $currency) {
            if ($currency['status']) {
                if ($this->config->get('config_currency') === $currency['code']) {
                    $data['currencies'][$currency['code']] = sprintf('%s (%s) %s', $currency['title'], $currency['code'], $this->language->get('text_default'));
                } else {
                    $data['currencies'][$currency['code']] = sprintf('%s (%s)', $currency['title'], $currency['code']);
                }
            }
        }

        $this->load->model('setting/extension');

        $extensions = $this->model_setting_extension->getExtensionsByType('analytics');

        $enabled_extensions = 0;

        foreach ($extensions as $extension) {
            if ($extension['code'] === 'ps_enhanced_measurement') {
                continue;
            }

            if ($this->config->get('analytics_' . $extension['code'] . '_status')) {
                $enabled_extensions++;
            }
        }

        $data['enabled_extensions'] = $enabled_extensions;

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/ps_enhanced_measurement/analytics/ps_enhanced_measurement', $data));
    }

    public function save(): void
    {
        $this->load->language('extension/ps_enhanced_measurement/analytics/ps_enhanced_measurement');

        $json = [];

        if (!$this->user->hasPermission('modify', 'extension/ps_enhanced_measurement/analytics/ps_enhanced_measurement')) {
            $json['error'] = $this->language->get('error_permission');
        }

        if (!$json) {
            if (empty($this->request->post['analytics_ps_enhanced_measurement_implementation'])) {
                $json['error']['input-measurement-implementation'] = $this->language->get('error_measurement_implementation');
            } else if ($this->request->post['analytics_ps_enhanced_measurement_implementation'] === '') {
                $json['error']['input-measurement-implementation'] = $this->language->get('error_measurement_implementation');
            }
        }

        if (!$json) {
            if (empty($this->request->post['analytics_ps_enhanced_measurement_google_tag_id'])) {
                $json['error']['input-google-tag-id'] = $this->language->get('error_google_tag_id');
            } elseif (preg_match('/^G-[A-Z0-9]{10}$/', $this->request->post['analytics_ps_enhanced_measurement_google_tag_id']) !== 1) {
                $json['error']['input-google-tag-id'] = $this->language->get('error_google_tag_id_invalid');
            }

            if (empty($this->request->post['analytics_ps_enhanced_measurement_mp_api_secret'])) {
                $json['error']['input-gtm-id'] = $this->language->get('error_mp_api_secret');
            } elseif (preg_match('/^[A-Za-z0-9]{7}-[A-Za-z0-9]{7}-[A-Za-z0-9]{6}$/', $this->request->post['analytics_ps_enhanced_measurement_mp_api_secret']) !== 1) {
                $json['error']['input-gtm-id'] = $this->language->get('error_mp_api_secret_invalid');
            }

            if (empty($this->request->post['analytics_ps_enhanced_measurement_gtm_id'])) {
                $json['error']['input-gtm-id'] = $this->language->get('error_gtm_id');
            } elseif (preg_match('/^GTM-[A-Z0-9]{8}$/', $this->request->post['analytics_ps_enhanced_measurement_gtm_id']) !== 1) {
                $json['error']['input-gtm-id'] = $this->language->get('error_gtm_id_invalid');
            }

            if ($this->request->post['analytics_ps_enhanced_measurement_tracking_delay'] < 100) {
                $json['error']['input-tracking-delay'] = $this->language->get('error_tracking_delay');
            }

            if (
                $this->request->post['analytics_ps_enhanced_measurement_wait_for_update'] < 0 ||
                $this->request->post['analytics_ps_enhanced_measurement_wait_for_update'] > 10000
            ) {
                $json['error']['input-wait-for-update'] = $this->language->get('error_wait_for_update');
            }
        }

        if (!$json) {
            $this->load->model('setting/setting');

            $this->model_setting_setting->editSetting('analytics_ps_enhanced_measurement', $this->request->post);

            $json['success'] = $this->language->get('text_success');
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function install(): void
    {
        if ($this->user->hasPermission('modify', 'extension/ps_enhanced_measurement/analytics/ps_enhanced_measurement')) {
            $this->load->model('setting/setting');

            $data = [
                'analytics_ps_enhanced_measurement_item_id' => 'product_id',
                'analytics_ps_enhanced_measurement_item_category_option' => 0,
                'analytics_ps_enhanced_measurement_item_price_tax' => 1,
                'analytics_ps_enhanced_measurement_debug_mode' => 0,
                'analytics_ps_enhanced_measurement_gtag_debug_mode' => 0,
                'analytics_ps_enhanced_measurement_currency' => $this->config->get('config_currency'),
                'analytics_ps_enhanced_measurement_tracking_delay' => 800,
                'analytics_ps_enhanced_measurement_track_user_id' => 1,
                'analytics_ps_enhanced_measurement_track_generate_lead' => 1,
                'analytics_ps_enhanced_measurement_track_qualify_lead' => 1,
                'analytics_ps_enhanced_measurement_track_sign_up' => 1,
                'analytics_ps_enhanced_measurement_track_login' => 1,
                'analytics_ps_enhanced_measurement_track_add_to_wishlist' => 1,
                'analytics_ps_enhanced_measurement_track_add_to_cart' => 1,
                'analytics_ps_enhanced_measurement_track_remove_from_cart' => 1,
                'analytics_ps_enhanced_measurement_track_search' => 1,
                'analytics_ps_enhanced_measurement_track_view_item_list' => 1,
                'analytics_ps_enhanced_measurement_track_select_item' => 1,
                'analytics_ps_enhanced_measurement_track_view_item' => 1,
                'analytics_ps_enhanced_measurement_track_select_promotion' => 1,
                'analytics_ps_enhanced_measurement_track_view_promotion' => 1,
                'analytics_ps_enhanced_measurement_track_view_cart' => 1,
                'analytics_ps_enhanced_measurement_track_begin_checkout' => 1,
                'analytics_ps_enhanced_measurement_track_add_payment_info' => 1,
                'analytics_ps_enhanced_measurement_track_add_shipping_info' => 1,
                'analytics_ps_enhanced_measurement_track_purchase' => 1,
                'analytics_ps_enhanced_measurement_wait_for_update' => 500,
            ];

            $this->model_setting_setting->editSetting('analytics_ps_enhanced_measurement', $data);

            $this->load->model('setting/event');

            $this->_registerEvents();

            $this->load->model('extension/ps_enhanced_measurement/analytics/ps_enhanced_measurement');

            $this->model_extension_ps_enhanced_measurement_analytics_ps_enhanced_measurement->install();
        }
    }

    public function uninstall(): void
    {
        if ($this->user->hasPermission('modify', 'extension/ps_enhanced_measurement/analytics/ps_enhanced_measurement')) {
            $this->load->model('setting/event');

            $this->_unregisterEvents();

            $this->load->model('extension/ps_enhanced_measurement/analytics/ps_enhanced_measurement');

            $this->model_extension_ps_enhanced_measurement_analytics_ps_enhanced_measurement->uninstall();
        }
    }

    public function fixEventHandler(): void
    {
        $this->load->language('extension/ps_enhanced_measurement/analytics/ps_enhanced_measurement');

        $json = [];

        if (!$this->user->hasPermission('modify', 'extension/ps_enhanced_measurement/analytics/ps_enhanced_measurement')) {
            $json['error'] = $this->language->get('error_permission');
        }

        if (!$json) {
            $this->load->model('setting/event');

            $this->_unregisterEvents();

            if ($this->_registerEvents() > 0) {
                $json['success'] = $this->language->get('text_success');
            } else {
                $json['error'] = $this->language->get('error_event');
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    private function _unregisterEvents(): void
    {
        $this->model_setting_event->deleteEventByCode('analytics_ps_enhanced_measurement');
    }

    private function _registerEvents(): int
    {
        $separator = version_compare(VERSION, '4.0.2.0', '>=') ? '.' : '|';

        $events = [
            ['trigger' => 'admin/view/sale/order_info/before', 'actionName' => 'eventAdminViewSaleOrderInfoBefore'],
            ['trigger' => 'admin/controller/sale/order' . $separator . 'call/after', 'actionName' => 'eventAdminControllerSaleOrderAddHistoryAfter'],

            ['trigger' => 'catalog/view/common/header/before', 'actionName' => 'eventCatalogViewCommonHeaderBefore'],

            ['trigger' => 'catalog/view/product/thumb/before', 'actionName' => 'eventCatalogViewProductThumbBefore'],
            ['trigger' => 'catalog/view/product/category/before', 'actionName' => 'eventCatalogViewProductCategoryBefore'],
            ['trigger' => 'catalog/view/product/search/before', 'actionName' => 'eventCatalogViewProductSearchBefore'],
            ['trigger' => 'catalog/view/product/special/before', 'actionName' => 'eventCatalogViewProductSpecialBefore'],
            ['trigger' => 'catalog/view/product/product/before', 'actionName' => 'eventCatalogViewProductProductBefore'],
            ['trigger' => 'catalog/view/product/compare/before', 'actionName' => 'eventCatalogViewProductCompareBefore'],
            ['trigger' => 'catalog/view/product/manufacturer_info/before', 'actionName' => 'eventCatalogViewProductManufacturerInfoBefore'],

            ['trigger' => 'catalog/view/account/order_info/before', 'actionName' => 'eventCatalogViewAccountOrderInfoBefore'],
            ['trigger' => 'catalog/view/account/wishlist/before', 'actionName' => 'eventCatalogViewAccountWishlistBefore'],
            ['trigger' => 'catalog/view/account/wishlist_list/before', 'actionName' => 'eventCatalogViewAccountWishlistListBefore'],
            ['trigger' => 'catalog/view/account/account/before', 'actionName' => 'eventCatalogViewAccountAccountBefore'],
            ['trigger' => 'catalog/view/common/success/before', 'actionName' => 'eventCatalogViewAccountSuccessBefore'],
            ['trigger' => 'catalog/controller/account/login' . $separator . 'login/after', 'actionName' => 'eventCatalogControllerAccountLoginLoginAfter'],
            ['trigger' => 'catalog/controller/account/newsletter' . $separator . 'save/after', 'actionName' => 'eventCatalogControllerAccountNewsletterSaveAfter'],
            ['trigger' => 'catalog/controller/account/register' . $separator . 'register/after', 'actionName' => 'eventCatalogControllerAccountRegisterRegisterAfter'],

            ['trigger' => 'catalog/view/checkout/payment_method/before', 'actionName' => 'eventCatalogViewCheckoutPaymentMethodBefore'],
            ['trigger' => 'catalog/view/checkout/shipping_method/before', 'actionName' => 'eventCatalogViewCheckoutShippingMethodBefore'],
            ['trigger' => 'catalog/view/checkout/confirm/before', 'actionName' => 'eventCatalogViewCheckoutConfirmBefore'],
            ['trigger' => 'catalog/view/checkout/checkout/before', 'actionName' => 'eventCatalogViewCheckoutCheckoutBefore'],
            ['trigger' => 'catalog/controller/checkout/success/before', 'actionName' => 'eventCatalogControllerCheckoutSuccessBefore'],
            ['trigger' => 'catalog/view/common/success/before', 'actionName' => 'eventCatalogViewCheckoutSuccessBefore'],
            ['trigger' => 'catalog/view/checkout/cart/before', 'actionName' => 'eventCatalogViewCheckoutCartBefore'],
            ['trigger' => 'catalog/view/checkout/cart_list/before', 'actionName' => 'eventCatalogViewCheckoutCartListBefore'],
            ['trigger' => 'catalog/view/checkout/cart_info/before', 'actionName' => 'eventCatalogViewCheckoutCartInfoBefore'], // cart button widget
            ['trigger' => 'catalog/view/common/cart/before', 'actionName' => 'eventCatalogViewCheckoutCartInfoBefore'], // standard cart page
            ['trigger' => 'catalog/view/checkout/register/before', 'actionName' => 'eventCatalogViewCheckoutRegisterBefore'],
            ['trigger' => 'catalog/controller/checkout/payment_method' . $separator . 'save/after', 'actionName' => 'eventCatalogViewCheckoutPaymentMethodSaveAfter'],
            ['trigger' => 'catalog/controller/checkout/shipping_method' . $separator . 'save/after', 'actionName' => 'eventCatalogViewCheckoutShippingMethodSaveAfter'],
            ['trigger' => 'catalog/controller/checkout/cart' . $separator . 'add/after', 'actionName' => 'eventCatalogControllerCheckoutCartAddAfter'],
            ['trigger' => 'catalog/controller/checkout/register' . $separator . 'save/after', 'actionName' => 'eventCatalogControllerCheckoutRegisterSaveAfter'],

            ['trigger' => 'catalog/controller/extension/opencart/module/bestseller/after', 'actionName' => 'eventCatalogViewExtensionOpencartModuleBestsellerAfter'],
            ['trigger' => 'catalog/controller/extension/opencart/module/featured/after', 'actionName' => 'eventCatalogViewExtensionOpencartModuleFeaturedAfter'],
            ['trigger' => 'catalog/controller/extension/opencart/module/latest/after', 'actionName' => 'eventCatalogViewExtensionOpencartModuleLatestAfter'],
            ['trigger' => 'catalog/controller/extension/opencart/module/special/after', 'actionName' => 'eventCatalogViewExtensionOpencartModuleSpecialAfter'],

            ['trigger' => 'catalog/controller/information/contact' . $separator . 'send/after', 'actionName' => 'eventCatalogControllerInformationContactSendAfter'],
            ['trigger' => 'catalog/view/common/success/before', 'actionName' => 'eventCatalogViewInformationContactSuccessBefore'],
        ];

        $result = 0;

        if (version_compare(VERSION, '4.0.1.0', '>=')) {
            foreach ($events as $event) {
                $result += $this->model_setting_event->addEvent([
                    'code' => 'analytics_ps_enhanced_measurement',
                    'description' => '',
                    'trigger' => $event['trigger'],
                    'action' => 'extension/ps_enhanced_measurement/analytics/ps_enhanced_measurement' . $separator . $event['actionName'],
                    'status' => '1',
                    'sort_order' => '0'
                ]);
            }
        } else {
            foreach ($events as $event) {
                $result += $this->model_setting_event->addEvent(
                    'analytics_ps_enhanced_measurement',
                    '',
                    $event['trigger'],
                    'extension/ps_enhanced_measurement/analytics/ps_enhanced_measurement' . $separator . $event['actionName']
                );
            }
        }

        return $result > 0;
    }

    public function send_refund(): void
    {
        if (
            !$this->config->get('analytics_ps_enhanced_measurement_status') ||
            !$this->config->get('analytics_ps_enhanced_measurement_implementation')
        ) {
            return;
        }

        $this->load->language('extension/ps_enhanced_measurement/analytics/ps_enhanced_measurement');

        if (!isset($this->request->post['order_id'])) {
            $json = [];

            $json['error'] = $this->language->get('error_refund_no_order_id');

            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($json, JSON_NUMERIC_CHECK));

            return;
        }

        $order_id = (int) $this->request->post['order_id'];

        $this->load->model('extension/ps_enhanced_measurement/analytics/ps_enhanced_measurement');
        $this->load->model('sale/order');
        $this->load->model('catalog/category');
        $this->load->model('catalog/manufacturer');
        $this->load->model('catalog/product');


        $item_category_option = (int) $this->config->get('analytics_ps_enhanced_measurement_item_category_option');
        $item_price_tax = $this->config->get('analytics_ps_enhanced_measurement_item_price_tax');
        $location_id = $this->config->get('analytics_ps_enhanced_measurement_location_id');
        $item_id_option = $this->config->get('analytics_ps_enhanced_measurement_item_id');

        $currency = $this->config->get('analytics_ps_enhanced_measurement_currency');

        if (empty($currency)) {
            $currency = $this->session->data['currency'];
        }

        $affiliation = $this->config->get('analytics_ps_enhanced_measurement_affiliation');

        if (empty($affiliation)) {
            $affiliation = $this->config->get('config_name');
        }

        $refund = isset($this->request->post['refund']) ? (array) $this->request->post['refund'] : [];


        $order_total_data['total_coupon'] = null;
        $order_total_data['total_voucher'] = null;

        $order_totals = $this->model_sale_order->getTotals($order_id);

        foreach ($order_totals as $order_total) {
            $start = strpos($order_total['title'], '(') + 1;
            $end = strrpos($order_total['title'], ')');

            if ($start && $end) {
                $order_total_data['total_' . $order_total['code']] = substr($order_total['title'], $start, $end - $start);
            }
        }

        if ($order_total_data['total_coupon']) {
            $product_coupon = $order_total_data['total_coupon'];
        } else if (isset($this->session->order_total_data['voucher'])) {
            $product_coupon = $order_total_data['total_voucher'];
        } else {
            $product_coupon = '';
        }


        $items = [];
        $index = 0;
        $total = 0;
        $sub_total = 0;

        $products = $this->model_sale_order->getProducts($order_id);

        if ($products) {
            if ($refund) {
                foreach ($products as $product) {
                    if (isset($refund[(int) $product['order_product_id']])) {
                        $product_info = $this->model_catalog_product->getProduct($product['product_id']);

                        $quantity = $refund[(int) $product['order_product_id']];

                        $item = [];

                        $item['item_id'] = isset($product_info[$item_id_option]) && !empty($product_info[$item_id_option]) ? $this->formatListId($product_info[$item_id_option]) : $product_info['product_id'];
                        $item['item_name'] = html_entity_decode($product_info['name'], ENT_QUOTES, 'UTF-8');
                        $item['affiliation'] = $affiliation;

                        if ($product_coupon) {
                            $item['coupon'] = $product_coupon;
                        }

                        $item['index'] = $index;

                        $manufacturer_info = $this->model_catalog_manufacturer->getManufacturer((int) $product_info['manufacturer_id']);

                        if ($manufacturer_info) {
                            $item['item_brand'] = $manufacturer_info['name'];
                        }

                        switch ($item_category_option) {
                            case 0:
                                $categories = $this->getCategoryType1($product_info['product_id']);
                                break;
                            case 1:
                                $categories = $this->getCategoryType2($product_info['product_id']);
                                break;
                            default:
                                $categories = $this->getCategoryType1($product_info['product_id']);
                                break;
                        }

                        $total_categories = count($categories);

                        foreach ($categories as $category_index => $category_name) {
                            if ($total_categories === 0 || $category_index === 0) {
                                $item['item_category'] = $category_name;
                            } else {
                                $item['item_category' . ($category_index + 1)] = $category_name;
                            }
                        }

                        if ($location_id) {
                            $item['location_id'] = $location_id;
                        }

                        if ($item_price_tax) {
                            $price = $product['price'] + $product['tax'];
                        } else {
                            $price = $product['price'];
                        }

                        $total += $price * $quantity;

                        $sub_total += (($product['price'] + $product['tax']) * $quantity) - ($product['tax'] * $quantity);


                        $item['price'] = $this->currency->format($price, $currency, 0, false);

                        $item['quantity'] = $quantity;

                        $items[(int) $product['order_product_id']] = $item;

                        break;
                    }
                } // end foreach
            } else {
                foreach ($products as $product) {
                    $product_info = $this->model_catalog_product->getProduct($product['product_id']);

                    $item = [];

                    $item['item_id'] = isset($product_info[$item_id_option]) && !empty($product_info[$item_id_option]) ? $this->formatListId($product_info[$item_id_option]) : $product_info['product_id'];
                    $item['item_name'] = html_entity_decode($product_info['name'], ENT_QUOTES, 'UTF-8');
                    $item['affiliation'] = $affiliation;

                    if ($product_coupon) {
                        $item['coupon'] = $product_coupon;
                    }

                    $item['index'] = $index;

                    $manufacturer_info = $this->model_catalog_manufacturer->getManufacturer((int) $product_info['manufacturer_id']);

                    if ($manufacturer_info) {
                        $item['item_brand'] = $manufacturer_info['name'];
                    }

                    switch ($item_category_option) {
                        case 0:
                            $categories = $this->getCategoryType1($product_info['product_id']);
                            break;
                        case 1:
                            $categories = $this->getCategoryType2($product_info['product_id']);
                            break;
                        default:
                            $categories = $this->getCategoryType1($product_info['product_id']);
                            break;
                    }

                    $total_categories = count($categories);

                    foreach ($categories as $category_index => $category_name) {
                        if ($total_categories === 0 || $category_index === 0) {
                            $item['item_category'] = $category_name;
                        } else {
                            $item['item_category' . ($category_index + 1)] = $category_name;
                        }
                    }

                    if ($location_id) {
                        $item['location_id'] = $location_id;
                    }

                    if ($item_price_tax) {
                        $price = $product['price'] + $product['tax'];
                    } else {
                        $price = $product['price'];
                    }

                    $total += $price * $product['quantity'];

                    $sub_total += (($product['price'] + $product['tax']) * $product['quantity']) - ($product['tax'] * $product['quantity']);


                    $item['price'] = $this->currency->format($price, $currency, 0, false);

                    $item['quantity'] = $product['quantity'];

                    $items[(int) $product['order_product_id']] = $item;

                    $index++;
                } // end foreach
            }
        }

        if (!$items) {
            $json = [];

            $json['error'] = $this->language->get('error_refund_no_items');

            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($json, JSON_NUMERIC_CHECK));

            return;
        }


        $total_fees = 0;
        $shipping = 0;

        foreach ($order_totals as $order_total) {
            if (in_array($order_total['code'], ['sub_total', 'tax', 'total'])) {
                continue;
            }

            if ($order_total['code'] === 'shipping') {
                $shipping = $order_total['value'];
                continue;
            }

            $total_fees += $order_total['value'];
        }


        $client_info = $this->model_extension_ps_enhanced_measurement_analytics_ps_enhanced_measurement->getClientIdByOrderId($order_id);

        $params = [
            'currency' => $currency,
            'transaction_id' => $order_id,
            'value' => $this->currency->format($sub_total + $total_fees, $currency, 0, false),
            'coupon' => $product_coupon ? $product_coupon : '',
            'shipping' => $this->currency->format($shipping, $currency, 0, false),
            'tax' => $this->currency->format($total - $sub_total, $currency, 0, false),
            'items' => array_values($items),
        ];

        if ($this->config->get('analytics_ps_enhanced_measurement_debug_mode')) {
            $params['engagement_time_msec'] = 1200;
            $params['debug_mode'] = true;
        }

        $event_data = [
            'client_id' => $client_info['client_id'],
            'user_id' => $client_info['user_id'],
            'non_personalized_ads' => true,
            'events' => [
                [
                    'name' => 'refund',
                    'params' => $params,
                ],
            ],
        ];


        $json = [];

        if ($this->sendGAAnalyticsData($event_data)) {
            $this->model_extension_ps_enhanced_measurement_analytics_ps_enhanced_measurement->saveRefundedState($order_id);

            $json['success'] = $this->language->get('text_refund_success');
        } else {
            $json['error'] = $this->language->get('error_refund_send');
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    protected function sendGAAnalyticsData(array $data): bool
    {
        $ps_google_tag_id = $this->config->get('analytics_ps_enhanced_measurement_google_tag_id');
        $ps_mp_api_secret = $this->config->get('analytics_ps_enhanced_measurement_mp_api_secret');

        $json_payload = json_encode($data);

        // $url = 'https://www.google-analytics.com/debug/mp/collect?measurement_id=' . $ps_google_tag_id . '&api_secret=' . $ps_mp_api_secret;
        $url = 'https://www.google-analytics.com/mp/collect?measurement_id=' . $ps_google_tag_id . '&api_secret=' . $ps_mp_api_secret;

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json_payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($json_payload),
        ]);

        $response = curl_exec($ch);

        curl_close($ch);

        return (bool) $response;
    }

    public function eventAdminControllerSaleOrderAddHistoryAfter(string &$route, array &$args, string &$output = null)
    {
        if (!isset($this->request->get['action'])) {
            return;
        }

        if ($this->request->get['action'] !== 'sale/order.addHistory') {
            return;
        }

        if (!$this->config->get('analytics_ps_enhanced_measurement_status')) {
            return;
        }

        $json_response = json_decode($this->response->getOutput(), true);

        if (!$json_response || !isset($json_response['success'])) {
            return;
        }

        if (isset($this->request->post['order_id'])) {
            $order_id = (int) $this->request->post['order_id'];
        } else {
            $order_id = 0;
        }

        if ($order_id === 0) {
            return;
        }

        if (isset($this->request->post['order_status_id'])) {
            $order_status_id = (int) $this->request->post['order_status_id'];
        } else {
            $order_status_id = 0;
        }

        if (isset($this->request->post['working_lead'])) {
            $working_lead = (int) $this->request->post['working_lead'];
        } else {
            $working_lead = 0;
        }


        $this->load->language('extension/ps_enhanced_measurement/analytics/ps_enhanced_measurement');

        $this->load->model('extension/ps_enhanced_measurement/analytics/ps_enhanced_measurement');
        $this->load->model('sale/order');
        $this->load->model('localisation/order_status');

        $order_info = $this->model_sale_order->getOrder($order_id);

        $client_info = $this->model_extension_ps_enhanced_measurement_analytics_ps_enhanced_measurement->getClientIdByOrderId($order_id);


        if ($working_lead === 1) {
            $params = [
                'currency' => $order_info['currency_code'],
                'value' => $this->currency->format($order_info['total'], $order_info['currency_code'], 0, false),
                'lead_status' => 'conversation',
            ];

            $event_data = [
                'client_id' => $client_info['client_id'],
                'user_id' => $client_info['user_id'],
                'events' => [
                    [
                        'name' => 'working_lead',
                        'params' => $params,
                    ],
                ],
            ];

            if ($this->config->get('analytics_ps_enhanced_measurement_debug_mode')) {
                $params['engagement_time_msec'] = 1200;
                $params['debug_mode'] = true;
            }

            if ($this->sendGAAnalyticsData($event_data)) {
                $json_response['ps_success'] = $this->language->get('text_working_lead_success');
            } else {
                $json_response['ps_error'] = $this->language->get('error_working_lead');
            }
        } else {
            $config_close_convert_lead_status = (array) $this->config->get('analytics_ps_enhanced_measurement_close_convert_lead_status');
            $config_close_unconvert_lead_status = (array) $this->config->get('analytics_ps_enhanced_measurement_close_unconvert_lead_status');
            $config_disqualify_lead_status = (array) $this->config->get('analytics_ps_enhanced_measurement_disqualify_lead_status');


            $order_statuses = $this->model_localisation_order_status->getOrderStatuses();

            $order_status_text = '';

            foreach ($order_statuses as $order_status) {
                if ((int) $order_status['order_status_id'] === $order_status_id) {
                    $order_status_text = $order_status['name'];
                }
            }


            if ($config_close_convert_lead_status && in_array($order_status_id, $config_close_convert_lead_status)) {
                $params = [
                    'currency' => $order_info['currency_code'],
                    'value' => $this->currency->format($order_info['total'], $order_info['currency_code'], 0, false),
                ];

                $event_data = [
                    'client_id' => $client_info['client_id'],
                    'user_id' => $client_info['user_id'],
                    'events' => [
                        [
                            'name' => 'close_convert_lead',
                            'params' => $params,
                        ],
                    ],
                ];

                if ($this->config->get('analytics_ps_enhanced_measurement_debug_mode')) {
                    $params['engagement_time_msec'] = 1200;
                    $params['debug_mode'] = true;
                }

                if ($this->sendGAAnalyticsData($event_data)) {
                    $json_response['ps_success'] = $this->language->get('text_close_convert_lead_success');
                } else {
                    $json_response['ps_error'] = $this->language->get('error_close_convert_lead');
                }
            } else if ($config_close_unconvert_lead_status && in_array($order_status_id, $config_close_unconvert_lead_status)) {
                $params = [
                    'currency' => $order_info['currency_code'],
                    'value' => $this->currency->format($order_info['total'], $order_info['currency_code'], 0, false),
                    'unconvert_lead_reason' => $order_status_text,
                ];

                $event_data = [
                    'client_id' => $client_info['client_id'],
                    'user_id' => $client_info['user_id'],
                    'events' => [
                        [
                            'name' => 'close_unconvert_lead',
                            'params' => $params,
                        ],
                    ],
                ];

                if ($this->config->get('analytics_ps_enhanced_measurement_debug_mode')) {
                    $params['engagement_time_msec'] = 1200;
                    $params['debug_mode'] = true;
                }

                if ($this->sendGAAnalyticsData($event_data)) {
                    $json_response['ps_success'] = $this->language->get('text_close_unconvert_lead_success');
                } else {
                    $json_response['ps_error'] = $this->language->get('error_close_unconvert_lead');
                }
            } else if ($config_disqualify_lead_status && in_array($order_status_id, $config_disqualify_lead_status)) {
                $params = [
                    'currency' => $order_info['currency_code'],
                    'value' => $this->currency->format($order_info['total'], $order_info['currency_code'], 0, false),
                    'disqualified_lead_reason' => $order_status_text,
                ];

                $event_data = [
                    'client_id' => $client_info['client_id'],
                    'user_id' => $client_info['user_id'],
                    'events' => [
                        [
                            'name' => 'disqualify_lead',
                            'params' => $params,
                        ],
                    ],
                ];

                if ($this->config->get('analytics_ps_enhanced_measurement_debug_mode')) {
                    $params['engagement_time_msec'] = 1200;
                    $params['debug_mode'] = true;
                }

                if ($this->sendGAAnalyticsData($event_data)) {
                    $json_response['ps_success'] = $this->language->get('text_disqualify_lead_success');
                } else {
                    $json_response['ps_error'] = $this->language->get('error_disqualify_lead');
                }
            }
        }

        $this->response->setOutput(json_encode($json_response, JSON_NUMERIC_CHECK));
    }

    public function eventAdminViewSaleOrderInfoBefore(string &$route, array &$args, string &$template): void
    {
        if (!$this->config->get('analytics_ps_enhanced_measurement_status')) {
            return;
        }

        if (!$this->config->get('analytics_ps_enhanced_measurement_google_tag_id') || !$this->config->get('analytics_ps_enhanced_measurement_mp_api_secret')) {
            return;
        }


        $this->load->language('extension/ps_enhanced_measurement/analytics/ps_enhanced_measurement', 'ps');

        $this->load->model('extension/ps_enhanced_measurement/analytics/ps_enhanced_measurement');


        if (isset($this->request->get['order_id'])) {
            $order_id = (int) $this->request->get['order_id'];

            $args['ps_text_refund_quantity'] = $this->language->get('ps_text_refund_quantity');
            $args['ps_column_refund_quantity'] = $this->language->get('ps_column_refund_quantity');
            $args['ps_button_refund'] = $this->language->get('ps_button_refund');
            $args['ps_button_refund_all'] = $this->language->get('ps_button_refund_all');
            $args['ps_button_refund_selected'] = $this->language->get('ps_button_refund_selected');
            $args['ps_error_no_refundable_selected'] = $this->language->get('ps_error_no_refundable_selected');
            $args['ps_text_product_already_refunded'] = $this->language->get('ps_text_product_already_refunded');
            $args['ps_entry_working_lead'] = $this->language->get('ps_entry_working_lead');
            $args['ps_help_working_lead'] = $this->language->get('ps_help_working_lead');

            if ($refund_info = $this->model_extension_ps_enhanced_measurement_analytics_ps_enhanced_measurement->getClientIdByOrderId($order_id)) {
                $args['ps_refunded'] = (bool) $refund_info['refunded'];
                $args['ps_client_info'] = true;
            } else {
                $args['ps_refunded'] = false;
                $args['ps_client_info'] = false;
            }

            $views = $this->model_extension_ps_enhanced_measurement_analytics_ps_enhanced_measurement->replaceAdminViewSaleOrderInfoBefore($args);

            $template = $this->replaceViews($route, $template, $views);
        }
    }

    protected function formatListId(string $string): string
    {
        if (function_exists('iconv')) {
            $new_string = iconv('UTF-8', 'ASCII//TRANSLIT', $string);
        } elseif (function_exists('mb_convert_encoding')) {
            $new_string = mb_convert_encoding($string, 'ASCII');
        } else {
            $new_string = false;
        }

        if ($new_string === false) {
            $new_string = $string;
        }

        $string = preg_replace('/[^a-zA-Z0-9_]/', ' ', $string);
        $string = strtolower($string);
        $string = preg_replace('/\s+/', ' ', $string);

        return str_replace(' ', '_', trim($string));
    }

    protected function getCategoryType1(int $product_id): array
    {
        $categories = $this->model_extension_ps_enhanced_measurement_analytics_ps_enhanced_measurement->getCategories($product_id);

        $result = [];

        foreach ($categories as $category_id) {
            $category_info = $this->model_extension_ps_enhanced_measurement_analytics_ps_enhanced_measurement->getCategoryType1($category_id);

            if ($category_info) {
                $result[] = html_entity_decode($category_info['last_category_name'], ENT_QUOTES, 'UTF-8');
            }
        }

        return $result;
    }

    protected function getCategoryType2(int $product_id): array
    {
        $categories = $this->model_extension_ps_enhanced_measurement_analytics_ps_enhanced_measurement->getCategories($product_id);

        $result = [];

        foreach ($categories as $category_id) {
            $category_info = $this->model_extension_ps_enhanced_measurement_analytics_ps_enhanced_measurement->getCategoryType2($category_id);

            if ($category_info) {
                if ($category_info['path']) {
                    $result[] = html_entity_decode($category_info['path'] . ' &gt; ' . $category_info['name'], ENT_QUOTES, 'UTF-8');
                } else {
                    $result[] = html_entity_decode($category_info['name'], ENT_QUOTES, 'UTF-8');
                }
            }
        }

        return $result;
    }

    /**
     * Retrieves the contents of a template file based on the provided route.
     *
     * This method checks if an event template buffer is provided. If so, it returns that buffer.
     * If not, it constructs the template file path based on the current theme settings and checks
     * for the existence of the template file. If the file exists, it reads and returns its contents.
     * It supports loading templates from both the specified theme directory and the default template directory.
     *
     * @param string $route The route for which the template is being retrieved.
     *                      This should match the naming convention for the template files.
     * @param string $event_template_buffer The template buffer that may be passed from an event.
     *                                       If provided, this buffer will be returned directly,
     *                                       bypassing file retrieval.
     *
     * @return mixed Returns the contents of the template file as a string if it exists,
     *               or false if the template file cannot be found or read.
     */
    protected function getTemplateBuffer(string $route, string $event_template_buffer): mixed
    {
        if ($event_template_buffer) {
            return $event_template_buffer;
        }

        if (defined('DIR_CATALOG')) {
            $dir_template = DIR_TEMPLATE;
        } else {
            if ($this->config->get('config_theme') == 'default') {
                $theme = $this->config->get('theme_default_directory');
            } else {
                $theme = $this->config->get('config_theme');
            }

            $dir_template = DIR_TEMPLATE . $theme . '/template/';
        }

        $template_file = $dir_template . $route . '.twig';

        if (file_exists($template_file) && is_file($template_file)) {
            $template_file = $this->modCheck($template_file);

            return file_get_contents($template_file);
        }

        if (defined('DIR_CATALOG')) {
            return false;
        }

        $dir_template = DIR_TEMPLATE . 'default/template/';
        $template_file = $dir_template . $route . '.twig';

        if (file_exists($template_file) && is_file($template_file)) {
            $template_file = $this->modCheck($template_file);

            return file_get_contents($template_file);
        }

        // Support for OC4 catalog
        $dir_template = DIR_TEMPLATE;
        $template_file = $dir_template . $route . '.twig';

        if (file_exists($template_file) && is_file($template_file)) {
            $template_file = $this->modCheck($template_file);

            return file_get_contents($template_file);
        }

        return false;
    }

    /**
     * Checks and modifies the provided file path based on predefined directory constants.
     *
     * This method checks if the file path starts with specific directory constants (`DIR_MODIFICATION`,
     * `DIR_APPLICATION`, and `DIR_SYSTEM`). Depending on these conditions, it modifies the file path to
     * point to the appropriate directory under `DIR_MODIFICATION`.
     *
     * - If the file path starts with `DIR_MODIFICATION`, it checks if it should point to either the
     *   `admin` or `catalog` directory based on the definition of `DIR_CATALOG`.
     * - If `DIR_CATALOG` is defined, the method checks for the file in the `admin` directory.
     *   Otherwise, it checks in the `catalog` directory.
     * - If the file path starts with `DIR_SYSTEM`, it checks for the file in the `system` directory
     *   within `DIR_MODIFICATION`.
     *
     * The method ensures that the returned file path exists before modifying it.
     *
     * @param string $file The original file path to check and modify.
     * @return string|null The modified file path if found, or null if it does not exist.
     */
    protected function modCheck(string $file): mixed
    {
        if (defined('DIR_MODIFICATION')) {
            if ($this->startsWith($file, DIR_MODIFICATION)) {
                if (defined('DIR_CATALOG')) {
                    if (file_exists(DIR_MODIFICATION . 'admin/' . substr($file, strlen(DIR_APPLICATION)))) {
                        $file = DIR_MODIFICATION . 'admin/' . substr($file, strlen(DIR_APPLICATION));
                    }
                } else {
                    if (file_exists(DIR_MODIFICATION . 'catalog/' . substr($file, strlen(DIR_APPLICATION)))) {
                        $file = DIR_MODIFICATION . 'catalog/' . substr($file, strlen(DIR_APPLICATION));
                    }
                }
            } elseif ($this->startsWith($file, DIR_SYSTEM)) {
                if (file_exists(DIR_MODIFICATION . 'system/' . substr($file, strlen(DIR_SYSTEM)))) {
                    $file = DIR_MODIFICATION . 'system/' . substr($file, strlen(DIR_SYSTEM));
                }
            }
        }

        return $file;
    }

    /**
     * Checks if a given string starts with a specified substring.
     *
     * This method determines if the string $haystack begins with the substring $needle.
     *
     * @param string $haystack The string to be checked.
     * @param string $needle The substring to search for at the beginning of $haystack.
     *
     * @return bool Returns true if $haystack starts with $needle; otherwise, false.
     */
    protected function startsWith(string $haystack, string $needle): bool
    {
        if (strlen($haystack) < strlen($needle)) {
            return false;
        }

        return (substr($haystack, 0, strlen($needle)) == $needle);
    }

    /**
     * Replaces specific occurrences of a substring in a string with a new substring.
     *
     * This method searches for all occurrences of a specified substring ($search) in a given string ($string)
     * and replaces the occurrences at the positions specified in the $nthPositions array with a new substring ($replace).
     *
     * @param string $search The substring to search for in the string.
     * @param string $replace The substring to replace the found occurrences with.
     * @param string $string The input string in which replacements will be made.
     * @param array $nthPositions An array of positions (1-based index) indicating which occurrences
     *                            of the search substring to replace.
     *
     * @return mixed The modified string with the specified occurrences replaced, or the original string if no matches are found.
     */
    protected function replaceNth(string $search, string $replace, string $string, array $nthPositions): mixed
    {
        $pattern = '/' . preg_quote($search, '/') . '/';
        $matches = [];
        $count = preg_match_all($pattern, $string, $matches, PREG_OFFSET_CAPTURE);

        if ($count > 0) {
            foreach ($nthPositions as $nth) {
                if ($nth > 0 && $nth <= $count) {
                    $offset = $matches[0][$nth - 1][1];
                    $string = substr_replace($string, $replace, $offset, strlen($search));
                }
            }
        }

        return $string;
    }

    /**
     * Replaces placeholders in a template with corresponding values from the views array.
     *
     * This method retrieves the template content based on the given route and template name,
     * then replaces specified search strings with their corresponding replace strings.
     * If positions are specified, the method performs replacements only at those positions.
     *
     * @param string $route The route associated with the template.
     * @param string $template The name of the template to be processed.
     * @param array $views An array of associative arrays where each associative array contains:
     *                     - string 'search': The string to search for in the template.
     *                     - string 'replace': The string to replace the 'search' string with.
     *                     - array|null 'positions': (Optional) An array of positions
     *                     where replacements should occur. If not provided,
     *                     all occurrences will be replaced.
     *
     * @return mixed The modified template content after performing the replacements.
     */
    protected function replaceViews(string $route, string $template, array $views): mixed
    {
        $output = $this->getTemplateBuffer($route, $template);

        foreach ($views as $view) {
            if (isset($view['positions']) && $view['positions']) {
                $output = $this->replaceNth($view['search'], $view['replace'], $output, $view['positions']);
            } else {
                $output = str_replace($view['search'], $view['replace'], $output);
            }
        }

        return $output;
    }
}
