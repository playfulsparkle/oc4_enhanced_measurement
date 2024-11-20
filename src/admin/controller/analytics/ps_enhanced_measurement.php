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
    const EXTENSION_DOC = 'https://github.com/playfulsparkle/oc4_ga4_enhanced_measurement.git';

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
        $data['analytics_ps_enhanced_measurement_item_id'] = $this->config->get('analytics_ps_enhanced_measurement_item_id');
        $data['analytics_ps_enhanced_measurement_item_category_option'] = $this->config->get('analytics_ps_enhanced_measurement_item_category_option');
        $data['analytics_ps_enhanced_measurement_item_price_tax'] = $this->config->get('analytics_ps_enhanced_measurement_item_price_tax');
        $data['analytics_ps_enhanced_measurement_affiliation'] = $this->config->get('analytics_ps_enhanced_measurement_affiliation');
        $data['analytics_ps_enhanced_measurement_location_id'] = $this->config->get('analytics_ps_enhanced_measurement_location_id');
        $data['analytics_ps_enhanced_measurement_currency'] = $this->config->get('analytics_ps_enhanced_measurement_currency');

        $data['gtm_id_visibility'] = $this->config->get('analytics_ps_enhanced_measurement_implementation') === 'gtm';
        $data['google_tag_id_visibility'] = $this->config->get('analytics_ps_enhanced_measurement_implementation') === 'gtag';

        $data['text_contact'] = sprintf($this->language->get('text_contact'), self::EXTENSION_EMAIL, self::EXTENSION_EMAIL, self::EXTENSION_DOC);

        $data['measurement_implementations'] = [
            '' => $this->language->get('text_none'),
            'gtag' => $this->language->get('text_gtag'),
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

        $this->load->model('localisation/currency');

        $currencies = $this->model_localisation_currency->getCurrencies();

        $data['currencies'] = [
            '' => $this->language->get('text_multi_currency')
        ];

        foreach ($currencies as $currency) {
            if ($currency['status']) {
                $data['currencies'][$currency['code']] = sprintf('%s (%s)', $currency['title'], $currency['code']);
            }
        }

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
            if (!isset($this->request->post['analytics_ps_enhanced_measurement_implementation'])) {
                $json['error']['input-measurement-implementation'] = $this->language->get('error_measurement_implementation');
            } else if ($this->request->post['analytics_ps_enhanced_measurement_implementation'] === '') {
                $json['error']['input-measurement-implementation'] = $this->language->get('error_measurement_implementation');
            }
        }

        if (!$json) {
            if ($this->request->post['analytics_ps_enhanced_measurement_implementation'] === 'gtag') {
                if (!isset($this->request->post['analytics_ps_enhanced_measurement_google_tag_id'])) {
                    $json['error']['input-google-tag-id'] = $this->language->get('error_google_tag_id');
                } elseif (preg_match('/^G-[A-Z0-9]+$/', $this->request->post['analytics_ps_enhanced_measurement_google_tag_id']) !== 1) {
                    $json['error']['input-google-tag-id'] = $this->language->get('error_google_tag_id_invalid');
                }
            }

            if ($this->request->post['analytics_ps_enhanced_measurement_implementation'] === 'gtm') {
                if (!isset($this->request->post['analytics_ps_enhanced_measurement_gtm_id'])) {
                    $json['error']['input-gtm-id'] = $this->language->get('error_gtm_id');
                } elseif (preg_match('/^GTM-[A-Z0-9]+$/', $this->request->post['analytics_ps_enhanced_measurement_gtm_id']) !== 1) {
                    $json['error']['input-gtm-id'] = $this->language->get('error_gtm_id_invalid');
                }
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
            ];

            $this->model_setting_setting->editSetting('analytics_ps_enhanced_measurement', $data);

            $this->load->model('setting/event');

            $this->_registerEvents();
        }
    }

    public function uninstall(): void
    {
        if ($this->user->hasPermission('modify', 'extension/ps_enhanced_measurement/analytics/ps_enhanced_measurement')) {
            $this->load->model('setting/event');

            $this->_unregisterEvents();
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
            ['trigger' => 'catalog/view/common/header/before', 'actionName' => 'eventCatalogViewCommonHeaderBefore'],

            ['trigger' => 'catalog/view/product/thumb/before', 'actionName' => 'eventCatalogViewProductThumbBefore'],
            ['trigger' => 'catalog/view/product/category/before', 'actionName' => 'eventCatalogViewProductCategoryBefore'],
            ['trigger' => 'catalog/view/product/search/before', 'actionName' => 'eventCatalogViewProductSearchBefore'],
            ['trigger' => 'catalog/view/product/special/before', 'actionName' => 'eventCatalogViewProductSpecialBefore'],
            ['trigger' => 'catalog/view/product/product/before', 'actionName' => 'eventCatalogViewProductProductBefore'],
            ['trigger' => 'catalog/view/product/compare/before', 'actionName' => 'eventCatalogViewProductCompareBefore'],
            ['trigger' => 'catalog/view/product/manufacturer_info/before', 'actionName' => 'eventCatalogViewProductManufacturerInfoBefore'],

            ['trigger' => 'catalog/view/account/*/before', 'actionName' => 'eventCatalogViewAccountAllBefore'],
            ['trigger' => 'catalog/view/account/wishlist/before', 'actionName' => 'eventCatalogViewAccountWishlistBefore'],
            ['trigger' => 'catalog/view/account/wishlist_list/before', 'actionName' => 'eventCatalogViewAccountWishlistListBefore'],
            ['trigger' => 'catalog/view/account/account/before', 'actionName' => 'eventCatalogViewAccountAccountBefore'],
            ['trigger' => 'catalog/view/common/success/before', 'actionName' => 'eventCatalogViewAccountSuccessBefore'],
            ['trigger' => 'catalog/controller/account/login.login/after', 'actionName' => 'eventCatalogControllerAccountLoginLoginAfter'],
            ['trigger' => 'catalog/controller/account/newsletter.save/after', 'actionName' => 'eventCatalogControllerAccountNewsletterSaveAfter'],

            ['trigger' => 'catalog/view/checkout/payment_method/before', 'actionName' => 'eventCatalogViewCheckoutPaymentMethodBefore'],
            ['trigger' => 'catalog/view/checkout/shipping_method/before', 'actionName' => 'eventCatalogViewCheckoutShippingMethodBefore'],
            ['trigger' => 'catalog/view/checkout/confirm/before', 'actionName' => 'eventCatalogViewCheckoutConfirmBefore'],
            ['trigger' => 'catalog/view/checkout/checkout/before', 'actionName' => 'eventCatalogViewCheckoutCheckoutBefore'],
            ['trigger' => 'catalog/view/common/success/before', 'actionName' => 'eventCatalogViewCheckoutSuccessBefore'],
            ['trigger' => 'catalog/view/checkout/cart/before', 'actionName' => 'eventCatalogViewCheckoutCartBefore'],
            ['trigger' => 'catalog/view/checkout/cart_list/before', 'actionName' => 'eventCatalogViewCheckoutCartListBefore'],
            ['trigger' => 'catalog/view/checkout/cart_info/before', 'actionName' => 'eventCatalogViewCheckoutCartInfoBefore'], // cart button widget
            ['trigger' => 'catalog/view/common/cart/before', 'actionName' => 'eventCatalogViewCheckoutCartInfoBefore'], // standard cart page
            ['trigger' => 'catalog/view/checkout/register/before', 'actionName' => 'eventCatalogViewCheckoutRegisterBefore'],
            ['trigger' => 'catalog/controller/checkout/payment_method.save/after', 'actionName' => 'eventCatalogViewCheckoutPaymentMethodSaveAfter'],
            ['trigger' => 'catalog/controller/checkout/shipping_method.save/after', 'actionName' => 'eventCatalogViewCheckoutShippingMethodSaveAfter'],
            ['trigger' => 'catalog/controller/checkout/cart.add/after', 'actionName' => 'eventCatalogControllerCheckoutCartAddAfter'],
            ['trigger' => 'catalog/controller/checkout/register.save/after', 'actionName' => 'eventCatalogControllerCheckoutRegisterSaveAfter'],

            ['trigger' => 'catalog/controller/extension/opencart/module/bestseller/after', 'actionName' => 'eventCatalogViewExtensionOpencartModuleBestsellerAfter'],
            ['trigger' => 'catalog/controller/extension/opencart/module/featured/after', 'actionName' => 'eventCatalogViewExtensionOpencartModuleFeaturedAfter'],
            ['trigger' => 'catalog/controller/extension/opencart/module/latest/after', 'actionName' => 'eventCatalogViewExtensionOpencartModuleLatestAfter'],
            ['trigger' => 'catalog/controller/extension/opencart/module/special/after', 'actionName' => 'eventCatalogViewExtensionOpencartModuleSpecialAfter'],

            ['trigger' => 'catalog/controller/information/contact.send/after', 'actionName' => 'eventCatalogControllerInformationContactSendAfter'],
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
                    $event['trigger'],
                    'extension/ps_enhanced_measurement/analytics/ps_enhanced_measurement' . $separator . $event['actionName']
                );
            }
        }

        return $result > 0;
    }
}
