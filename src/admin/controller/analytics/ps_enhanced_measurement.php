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

    public function sendRefund(): void
    {
        $this->load->model('sale/order');


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


        if (isset($this->request->get['order_id'])) {
            $order_id = $this->request->get['order_id'];
        } else {
            $order_id = 0;
        }

        if (isset($this->request->get['quantity'])) {
            $quantity = $this->request->get['quantity'];
        } else {
            $quantity = 1;
        }

        if (isset($this->request->get['order_product_id'])) {
            $order_product_id = $this->request->get['order_product_id'];
        } else {
            $order_product_id = 0;
        }


        $json = [];


        if ($order_id && $order_product_id) {
            $products = $this->model_sale_order->getProducts($order_id);

            $items = [];

            foreach ($products as $index => $product_info) {
                print_r($product_info);
                $item = [];

            }
        }


        // $purchase_data = [
        //     'tax' => 0,
        //     'shipping' => 0,
        //     'total' => 0,
        // ];

        // $order_totals = $this->model_sale_order->getTotals($order_id);

        // foreach ($order_totals as $total) {
        //     if ($total['code'] === 'shipping') {
        //         $purchase_data['shipping'] = $total['value'];
        //     } else if ($total['code'] === 'tax') {
        //         $purchase_data['tax'] = $total['value'];
        //     } else if ($total['code'] === 'total') {
        //         $purchase_data['total'] = $total['value'];
        //     }
        // }


        // $json['ps_refund'] = [
        //     'ecommerce' => [
        //         'currency' => $currency,
        //         'transaction_id' => $order_id,
        //         'value' => $this->currency->format($purchase_data['total'] - $purchase_data['shipping'] - $purchase_data['tax'], $currency, 0, false),
        //         'tax' => $this->currency->format(($item_price_tax ? $purchase_data['tax'] : 0), $currency, 0, false),
        //         'shipping' => $this->currency->format($purchase_data['shipping'], $currency, 0, false),
        //         // 'coupon' => $product_coupon ? $product_coupon : '',
        //         'items' => array_values($items),
        //     ],
        // ];

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function eventAdminViewSaleOrderInfoBefore(string &$route, array &$args, string &$template): void
    {
        if (!$this->config->get('analytics_ps_enhanced_measurement_status')) {
            return;
        }


        $this->load->language('extension/ps_enhanced_measurement/analytics/ps_enhanced_measurement', 'ps');

        $args['ps_text_refund_quantity'] = $this->language->get('ps_text_refund_quantity');
        $args['ps_column_refund_quantity'] = $this->language->get('ps_column_refund_quantity');
        $args['ps_button_refund'] = $this->language->get('ps_button_refund');

        $this->load->model('extension/ps_enhanced_measurement/analytics/ps_enhanced_measurement');

        $views = $this->model_extension_ps_enhanced_measurement_analytics_ps_enhanced_measurement->replaceAdminViewSaleOrderInfoBefore($args);

        $template = $this->replaceViews($route, $template, $views);
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
