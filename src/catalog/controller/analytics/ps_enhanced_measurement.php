<?php
namespace Opencart\Catalog\Controller\Extension\PsEnhancedMeasurement\Analytics;
/**
 * Class PsEnhancedMeasurement
 *
 * @package Opencart\Catalog\Controller\Extension\PsEnhancedMeasurement\Analytics
 */
class PsEnhancedMeasurement extends \Opencart\System\Engine\Controller
{
    public function index(): string
    {
        if (!$this->config->get('analytics_ps_enhanced_measurement_status')) {
            return '';
        }

        $ps_config_adwords_status = (bool) $this->config->get('analytics_ps_enhanced_measurement_adwords_status');
        $ps_config_measurement_implementation = $this->config->get('analytics_ps_enhanced_measurement_implementation');
        $ps_config_google_tag_id = $this->config->get('analytics_ps_enhanced_measurement_google_tag_id');
        $ps_config_gtm_id = $this->config->get('analytics_ps_enhanced_measurement_gtm_id');
        $ps_config_adwords_id = $this->config->get('analytics_ps_enhanced_measurement_adwords_id');

        $ps_config_currency = $this->config->get('analytics_ps_enhanced_measurement_currency');

        if (empty($ps_config_currency)) {
            $ps_config_currency = $this->session->data['currency'];
        }


        $ps_html = '';

        if ($ps_config_adwords_status || $ps_config_measurement_implementation === 'gtag') {
            $ps_html .= '<!-- Google tag (gtag.js) -->' . PHP_EOL;

            if ($ps_config_adwords_status) {
                $ps_html .= '<script async src="https://www.googletagmanager.com/gtag/js?id=' . $ps_config_adwords_id . '"></script>' . PHP_EOL;
            }

            if ($ps_config_measurement_implementation === 'gtag') {
                $ps_html .= '<script async src="https://www.googletagmanager.com/gtag/js?id=' . $ps_config_google_tag_id . '"></script>' . PHP_EOL;
            }
        }


        $ga4_config = [];
        $adwords_config = [];

        if ($this->config->get('analytics_ps_enhanced_measurement_debug_global_site_tag')) {
            $ga4_config['debug_mode'] = true;
        }

        if ($this->config->get('analytics_ps_enhanced_measurement_adwords_enhanced_conversion')) {
            $adwords_config['allow_enhanced_conversions'] = true;
        }

        if ($this->request->server['HTTPS']) {
            $ga4_config['cookie_flags'] = 'SameSite=None;Secure';
            $adwords_config['cookie_flags'] = 'SameSite=None;Secure';
        }

        $json_gtag_config = $ga4_config ? json_encode($ga4_config) : null;
        $json_adwords_config = $adwords_config ? json_encode($adwords_config) : null;


        $ps_html .= "<script>" . PHP_EOL;
        $ps_html .= "window.dataLayer = window.dataLayer || [];" . PHP_EOL;
        $ps_html .= "function gtag() { dataLayer.push(arguments); }" . PHP_EOL . PHP_EOL;
        $ps_html .= "gtag('js', new Date());" . PHP_EOL;


        if ($ps_config_adwords_status) {
            if ($json_adwords_config) {
                $ps_html .= "gtag('config', '" . $ps_config_adwords_id . "', " . $json_adwords_config . ");" . PHP_EOL;
            } else {
                $ps_html .= "gtag('config', '" . $ps_config_adwords_id . "');" . PHP_EOL;
            }
        }

        if ($ps_config_measurement_implementation === 'gtag') {
            if ($json_gtag_config) {
                $ps_html .= "gtag('config', '" . $ps_config_google_tag_id . "', " . $json_gtag_config . ");" . PHP_EOL;
            } else {
                $ps_html .= "gtag('config', '" . $ps_config_google_tag_id . "');" . PHP_EOL;
            }
        }


        if ($ps_config_adwords_status || $ps_config_measurement_implementation === 'gtm') {
            $ps_config_ads_data_redaction = (bool) $this->config->get('analytics_ps_enhanced_measurement_ads_data_redaction');
            $ps_config_url_passthrough = (bool) $this->config->get('analytics_ps_enhanced_measurement_url_passthrough');

            $ps_html .= "gtag('set', 'ads_data_redaction', '" . ($ps_config_ads_data_redaction ? 'granted' : 'denied') . "');" . PHP_EOL;
            $ps_html .= "gtag('set', 'url_passthrough', '" . ($ps_config_url_passthrough ? 'granted' : 'denied') . "');" . PHP_EOL;
        }

        if ($this->config->get('analytics_ps_enhanced_measurement_gcm_status')) {
            $ps_config_ad_storage = (bool) $this->config->get('analytics_ps_enhanced_measurement_ad_storage');
            $ps_config_ad_user_data = (bool) $this->config->get('analytics_ps_enhanced_measurement_ad_user_data');
            $ps_config_ad_personalization = (bool) $this->config->get('analytics_ps_enhanced_measurement_ad_personalization');
            $ps_config_analytics_storage = (bool) $this->config->get('analytics_ps_enhanced_measurement_analytics_storage');
            $ps_config_functionality_storage = (bool) $this->config->get('analytics_ps_enhanced_measurement_functionality_storage');
            $ps_config_personalization_storage = (bool) $this->config->get('analytics_ps_enhanced_measurement_personalization_storage');
            $ps_config_security_storage = (bool) $this->config->get('analytics_ps_enhanced_measurement_security_storage');
            $ps_config_wait_for_update = (int) $this->config->get('analytics_ps_enhanced_measurement_wait_for_update');

            $default_consent = [
                'ad_storage' => $ps_config_ad_storage ? 'granted' : 'denied',
                'ad_user_data' => $ps_config_ad_user_data ? 'granted' : 'denied',
                'ad_personalization' => $ps_config_ad_personalization ? 'granted' : 'denied',
                'analytics_storage' => $ps_config_analytics_storage ? 'granted' : 'denied',
                'functionality_storage' => $ps_config_functionality_storage ? 'granted' : 'denied',
                'personalization_storage' => $ps_config_personalization_storage ? 'granted' : 'denied',
                'security_storage' => $ps_config_security_storage ? 'granted' : 'denied',
            ];

            if ($ps_config_wait_for_update > 0) {
                $default_consent['wait_for_update'] = $ps_config_wait_for_update;
            }

            $ps_html .= PHP_EOL . "gtag('consent', 'default', " . json_encode($default_consent) . ");" . PHP_EOL;
        }


        if ($this->config->get('analytics_ps_enhanced_measurement_track_user_id') && $this->customer->isLogged()) {
            if ($ps_config_measurement_implementation === 'gtag') {
                $ps_html .= "gtag('set', 'user_id', " . $this->customer->getId() . ");" . PHP_EOL;
            } else if ($ps_config_measurement_implementation === 'gtm') {
                $ps_html .= PHP_EOL . "dataLayer.push(" . json_encode(['user_id' => $this->customer->getId()], JSON_NUMERIC_CHECK) . ");" . PHP_EOL;
            }
        }

        $ps_html .= "</script>" . PHP_EOL;

        if ($ps_config_measurement_implementation === 'gtm') {
            $ps_html .= "<!-- Google Tag Manager -->" . PHP_EOL;
            $ps_html .= "<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':" . PHP_EOL;
            $ps_html .= "new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0]," . PHP_EOL;
            $ps_html .= "j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=" . PHP_EOL;
            $ps_html .= "'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);" . PHP_EOL;
            $ps_html .= "})(window,document,'script','dataLayer','" . $ps_config_gtm_id . "');</script>" . PHP_EOL;
            $ps_html .= "<!-- End Google Tag Manager -->" . PHP_EOL;
        }

        return $ps_html;
    }

    /**
     * Event handler for `catalog/view/common/header/before`.
     *
     * @param string $route
     * @param array $args
     * @param string $template
     *
     * @return void
     */
    public function eventCatalogViewCommonHeaderBefore(string &$route, array &$args, string &$template): void
    {
        if (!$this->config->get('analytics_ps_enhanced_measurement_status')) {
            return;
        }


        $ps_enhanced_measurement_script = 'extension/ps_enhanced_measurement/catalog/view/javascript/ps-enhanced-measurement.js';

        if (isset($args['scripts'])) {
            if (version_compare(VERSION, '4.0.1.0', '>=')) {
                $args['scripts'][$ps_enhanced_measurement_script] = ['href' => $ps_enhanced_measurement_script];
            } else {
                $args['scripts'][$ps_enhanced_measurement_script] = $ps_enhanced_measurement_script;
            }
        }


        $args['ps_enhanced_measurement_implementation'] = $this->config->get('analytics_ps_enhanced_measurement_implementation');
        $args['ps_enhanced_measurement_gtm_id'] = $this->config->get('analytics_ps_enhanced_measurement_gtm_id');
        $args['ps_enhanced_measurement_console_log_ga4_events'] = $this->config->get('analytics_ps_enhanced_measurement_console_log_ga4_events');
        $args['ps_enhanced_measurement_console_log_adwords_events'] = $this->config->get('analytics_ps_enhanced_measurement_console_log_adwords_events');
        $args['ps_enhanced_measurement_tracking_delay'] = $this->config->get('analytics_ps_enhanced_measurement_tracking_delay');


        $ps_config_track_file_download_ext = [];

        if ($this->config->get('analytics_ps_enhanced_measurement_track_file_download')) {
            $ps_config_track_file_download_ext = $this->config->get('analytics_ps_enhanced_measurement_track_file_download_ext');
            $ps_config_track_file_download_ext = array_map('trim', explode(',', $ps_config_track_file_download_ext));
            $ps_config_track_file_download_ext = "['" . implode("','", $ps_config_track_file_download_ext) . "']";
        }

        $args['ps_enhanced_measurement_track_file_download_ext'] = $ps_config_track_file_download_ext;


        if ($this->config->get('analytics_ps_enhanced_measurement_track_login')) {
            if (isset($this->session->data['ps_login_event'])) {
                $ps_login = [
                    'method' => 'Website',
                    'user_id' => $this->customer->getId(),
                ];

                unset($this->session->data['ps_login_event']);
            } else {
                $ps_login = null;
            }

            $args['ps_login'] = $ps_login ? json_encode($ps_login, JSON_NUMERIC_CHECK) : null;
        } else {
            $args['ps_login'] = null;
        }


        $ps_config_adwords_status = $this->config->get('analytics_ps_enhanced_measurement_adwords_status');
        $ps_config_adwords_enhanced_conversion = $this->config->get('analytics_ps_enhanced_measurement_adwords_enhanced_conversion');
        $ps_config_adwords_id = $this->config->get('analytics_ps_enhanced_measurement_adwords_id');

        $ps_enhanced_measurement_adwords_tracking = [];
        $ps_enhanced_measurement_adwords_user_data = [];

        if ($ps_config_adwords_status) {
            if ($ps_config_adwords_purchase_label = $this->config->get('analytics_ps_enhanced_measurement_adwords_purchase_label')) {
                $ps_enhanced_measurement_adwords_tracking['purchase'] = $ps_config_adwords_id . '/' . $ps_config_adwords_purchase_label;
            }

            if ($ps_config_adwords_add_to_cart_label = $this->config->get('analytics_ps_enhanced_measurement_adwords_add_to_cart_label')) {
                $ps_enhanced_measurement_adwords_tracking['add_to_cart'] = $ps_config_adwords_id . '/' . $ps_config_adwords_add_to_cart_label;
            }

            if ($ps_config_adwords_begin_checkout_label = $this->config->get('analytics_ps_enhanced_measurement_adwords_begin_checkout_label')) {
                $ps_enhanced_measurement_adwords_tracking['begin_checkout'] = $ps_config_adwords_id . '/' . $ps_config_adwords_begin_checkout_label;
            }

            if ($ps_config_adwords_lead_label = $this->config->get('analytics_ps_enhanced_measurement_adwords_lead_label')) {
                $ps_enhanced_measurement_adwords_tracking['generate_lead'] = $ps_config_adwords_id . '/' . $ps_config_adwords_lead_label;
            }

            if ($ps_config_adwords_sign_up_label = $this->config->get('analytics_ps_enhanced_measurement_adwords_sign_up_label')) {
                $ps_enhanced_measurement_adwords_tracking['sign_up'] = $ps_config_adwords_id . '/' . $ps_config_adwords_sign_up_label;
            }

            if ($ps_config_adwords_enhanced_conversion) {
                $ps_enhanced_measurement_adwords_user_data = $this->getAdwordsUserData();
            }
        }

        $ps_enhanced_measurement_ga4_tracking = [
            'generate_lead' => (bool) $this->config->get('analytics_ps_enhanced_measurement_track_generate_lead'),
            'qualify_lead' => (bool) $this->config->get('analytics_ps_enhanced_measurement_track_qualify_lead'),
            'sign_up' => (bool) $this->config->get('analytics_ps_enhanced_measurement_track_sign_up'),
            'login' => (bool) $this->config->get('analytics_ps_enhanced_measurement_track_login'),
            'add_to_wishlist' => (bool) $this->config->get('analytics_ps_enhanced_measurement_track_add_to_wishlist'),
            'add_to_cart' => (bool) $this->config->get('analytics_ps_enhanced_measurement_track_add_to_cart'),
            'remove_from_cart' => (bool) $this->config->get('analytics_ps_enhanced_measurement_track_remove_from_cart'),
            'search' => (bool) $this->config->get('analytics_ps_enhanced_measurement_track_search'),
            'view_item_list' => (bool) $this->config->get('analytics_ps_enhanced_measurement_track_view_item_list'),
            'select_item' => (bool) $this->config->get('analytics_ps_enhanced_measurement_track_select_item'),
            'view_item' => (bool) $this->config->get('analytics_ps_enhanced_measurement_track_view_item'),
            'select_promotion' => (bool) $this->config->get('analytics_ps_enhanced_measurement_track_select_promotion'),
            'view_promotion' => (bool) $this->config->get('analytics_ps_enhanced_measurement_track_view_promotion'),
            'view_cart' => (bool) $this->config->get('analytics_ps_enhanced_measurement_track_view_cart'),
            'begin_checkout' => (bool) $this->config->get('analytics_ps_enhanced_measurement_track_begin_checkout'),
            'add_payment_info' => (bool) $this->config->get('analytics_ps_enhanced_measurement_track_add_payment_info'),
            'add_shipping_info' => (bool) $this->config->get('analytics_ps_enhanced_measurement_track_add_shipping_info'),
            'purchase' => (bool) $this->config->get('analytics_ps_enhanced_measurement_track_purchase'),
            'file_download' => (bool) $this->config->get('analytics_ps_enhanced_measurement_track_file_download'),
        ];

        $args['ps_enhanced_measurement_adwords_status'] = $ps_config_adwords_status;
        $args['ps_enhanced_measurement_adwords_enhanced_conversion'] = $ps_config_adwords_enhanced_conversion;
        $args['ps_enhanced_measurement_adwords_tracking'] = json_encode($ps_enhanced_measurement_adwords_tracking);
        $args['ps_enhanced_measurement_adwords_user_data'] = json_encode($ps_enhanced_measurement_adwords_user_data);
        $args['ps_enhanced_measurement_ga4_tracking'] = json_encode($ps_enhanced_measurement_ga4_tracking);


        $this->load->model('extension/ps_enhanced_measurement/analytics/ps_enhanced_measurement');

        $headerViews = $this->model_extension_ps_enhanced_measurement_analytics_ps_enhanced_measurement->replaceCatalogViewCommonHeaderBefore($args);

        $template = $this->replaceViews($route, $template, $headerViews);
    }

    public function eventCatalogViewAccountDownloadBefore(string &$route, array &$args, string &$template): void
    {
        if (!$this->config->get('analytics_ps_enhanced_measurement_status')) {
            return;
        }


        $ps_config_track_file_download = $this->config->get('analytics_ps_enhanced_measurement_track_file_download');

        if (!$ps_config_track_file_download) {
            return;
        }


        if (isset($this->request->get['page'])) {
            $page = (int) $this->request->get['page'];
        } else {
            $page = 1;
        }

        $limit = 10;


        $this->load->language('extension/ps_enhanced_measurement/analytics/ps_enhanced_measurement');

        $this->load->model('extension/ps_enhanced_measurement/analytics/ps_enhanced_measurement');
        $this->load->model('account/download');


        $ps_merge_items = [];

        $downloads = $this->model_account_download->getDownloads(($page - 1) * $limit, $limit);

        if ($downloads) {
            foreach ($downloads as $download) {
                $ps_filename = $download['filename'];
                $ps_filename = substr($ps_filename, 0, strrpos($ps_filename, '.'));

                $ps_link_url = $this->url->link('account/download.download', 'language=' . $this->config->get('config_language') . '&customer_token=' . $this->session->data['customer_token'] . '&download_id=' . $download['download_id']);

                $ps_merge_items['file_download_' . $download['order_id']] = [
                    'file_extension' => pathinfo($ps_filename, PATHINFO_EXTENSION),
                    'file_name' => $ps_filename,
                    'link_text' => $download['name'],
                    'link_url' => str_replace('&amp;', '&', $ps_link_url),
                ];
            }

            $args['ps_merge_items'] = $ps_merge_items ? json_encode($ps_merge_items, JSON_NUMERIC_CHECK) : null;
        } else {
            $args['ps_merge_items'] = null;
        }


        $args['ps_track_file_download'] = $ps_config_track_file_download;


        $headerViews = $this->model_extension_ps_enhanced_measurement_analytics_ps_enhanced_measurement->replaceCatalogViewAccountDownloadBefore($args);

        $template = $this->replaceViews($route, $template, $headerViews);
    }

    public function eventCatalogViewProductThumbBefore(string &$route, array &$args, string &$template): void
    {
        if (!$this->config->get('analytics_ps_enhanced_measurement_status')) {
            return;
        }

        $ps_config_track_add_to_wishlist = $this->config->get('analytics_ps_enhanced_measurement_track_add_to_wishlist');
        $ps_config_track_add_to_cart = $this->config->get('analytics_ps_enhanced_measurement_track_add_to_cart') || $this->config->get('analytics_ps_enhanced_measurement_adwords_add_to_cart_label');
        $ps_config_track_select_item = $this->config->get('analytics_ps_enhanced_measurement_track_select_item');
        $ps_config_track_select_promotion = $this->config->get('analytics_ps_enhanced_measurement_track_select_promotion');

        if (
            !$ps_config_track_add_to_wishlist &&
            !$ps_config_track_add_to_cart &&
            !$ps_config_track_select_item &&
            !$ps_config_track_select_promotion
        ) {
            return;
        }


        $this->load->language('extension/ps_enhanced_measurement/analytics/ps_enhanced_measurement');

        $this->load->model('extension/ps_enhanced_measurement/analytics/ps_enhanced_measurement');


        $args['ps_has_options'] = $this->model_extension_ps_enhanced_measurement_analytics_ps_enhanced_measurement->hasOptions($args['product_id']);


        $args['ps_track_add_to_wishlist'] = $ps_config_track_add_to_wishlist;
        $args['ps_track_add_to_cart'] = $ps_config_track_add_to_cart;
        $args['ps_track_select_item'] = $ps_config_track_select_item;
        $args['ps_track_select_promotion'] = $ps_config_track_select_promotion;


        $headerViews = $this->model_extension_ps_enhanced_measurement_analytics_ps_enhanced_measurement->replaceCatalogViewProductThumbBefore($args);

        $template = $this->replaceViews($route, $template, $headerViews);
    }

    public function eventCatalogViewProductCategoryBefore(string &$route, array &$args, string &$template): void
    {
        if (!$this->config->get('analytics_ps_enhanced_measurement_status')) {
            return;
        }


        $ps_config_track_add_to_wishlist = $this->config->get('analytics_ps_enhanced_measurement_track_add_to_wishlist');
        $ps_config_track_add_to_cart = $this->config->get('analytics_ps_enhanced_measurement_track_add_to_cart') || $this->config->get('analytics_ps_enhanced_measurement_adwords_add_to_cart_label');
        $ps_config_track_select_item = $this->config->get('analytics_ps_enhanced_measurement_track_select_item');
        $ps_config_track_select_promotion = $this->config->get('analytics_ps_enhanced_measurement_track_select_promotion');
        $ps_config_track_view_item_list = $this->config->get('analytics_ps_enhanced_measurement_track_view_item_list');

        if (
            !$ps_config_track_add_to_wishlist &&
            !$ps_config_track_add_to_cart &&
            !$ps_config_track_select_item &&
            !$ps_config_track_select_promotion &&
            !$ps_config_track_view_item_list
        ) {
            return;
        }


        $this->load->language('extension/ps_enhanced_measurement/analytics/ps_enhanced_measurement');

        $this->load->model('extension/ps_enhanced_measurement/analytics/ps_enhanced_measurement');
        $this->load->model('catalog/category');
        $this->load->model('catalog/manufacturer');
        $this->load->model('catalog/product');


        $ps_config_item_category_option = (int) $this->config->get('analytics_ps_enhanced_measurement_item_category_option');
        $ps_config_item_price_tax = $this->config->get('analytics_ps_enhanced_measurement_item_price_tax');
        $ps_config_location_id = $this->config->get('analytics_ps_enhanced_measurement_location_id');
        $ps_config_item_id_option = $this->config->get('analytics_ps_enhanced_measurement_item_id');
        $ps_config_affiliation = $this->config->get('analytics_ps_enhanced_measurement_affiliation');

        $ps_config_currency = $this->config->get('analytics_ps_enhanced_measurement_currency');

        if (empty($ps_config_currency)) {
            $ps_config_currency = $this->session->data['currency'];
        }

        if (isset($this->request->get['filter'])) {
            $filter = $this->request->get['filter'];
        } else {
            $filter = '';
        }

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'p.sort_order';
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else {
            $order = 'ASC';
        }

        if (isset($this->request->get['page'])) {
            $page = (int) $this->request->get['page'];
        } else {
            $page = 1;
        }

        if (isset($this->request->get['limit'])) {
            $limit = (int) $this->request->get['limit'];
        } else {
            $limit = $this->config->get('config_pagination');
        }

        if (isset($this->request->get['path'])) {
            $ps_parts = explode('_', (string) $this->request->get['path']);
            $ps_category_id = (int) array_pop($ps_parts);
            $ps_category_info = $ps_category_id > 0 ? $this->model_catalog_category->getCategory($ps_category_id) : null;
        } else {
            $ps_category_id = 0;
            $ps_category_info = null;
        }

        if (isset($this->session->data['coupon'])) {
            $ps_product_coupon = $this->session->data['coupon'];
        } else if (isset($this->session->data['voucher'])) {
            $ps_product_coupon = $this->session->data['voucher'];
        } else {
            $ps_product_coupon = null;
        }


        if ($ps_category_info) {
            $ps_item_list_name = sprintf($this->language->get('text_x_products'), html_entity_decode($ps_category_info['name'], ENT_QUOTES, 'UTF-8'));
            $ps_item_list_id = $this->formatListId($ps_item_list_name);

            $filter_data = [
                'filter_category_id' => $ps_category_id,
                'filter_sub_category' => false,
                'filter_filter' => $filter,
                'sort' => $sort,
                'order' => $order,
                'start' => ($page - 1) * $limit,
                'limit' => $limit
            ];

            $products = $this->model_catalog_product->getProducts($filter_data);

            $ps_items = [];
            $ps_quantity_minimums = [];
            $ps_specials = [];

            foreach ($products as $index => $product_info) {
                $ps_item = [];

                $ps_item['item_id'] = isset($product_info[$ps_config_item_id_option]) && !empty($product_info[$ps_config_item_id_option]) ? $this->formatListId($product_info[$ps_config_item_id_option]) : $product_info['product_id'];
                $ps_item['item_name'] = html_entity_decode($product_info['name'], ENT_QUOTES, 'UTF-8');

                if ($ps_config_affiliation) {
                    $ps_item['affiliation'] = $ps_config_affiliation;
                }

                if ($ps_product_coupon) {
                    $ps_item['coupon'] = $ps_product_coupon;
                }

                if ((float) $product_info['special']) {
                    $ps_discount = $this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $ps_config_item_price_tax) - $this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $ps_config_item_price_tax);

                    $ps_item['discount'] = $this->currency->format($ps_discount, $ps_config_currency, 0, false);
                }

                $ps_item['index'] = $index;

                $ps_manufacturer_info = $this->model_catalog_manufacturer->getManufacturer((int) $product_info['manufacturer_id']);

                if ($ps_manufacturer_info) {
                    $ps_item['item_brand'] = $ps_manufacturer_info['name'];
                }

                if ($ps_config_item_category_option === 0) {
                    $ps_product_categories = $this->getCategoryType1($product_info['product_id']);
                } else if ($ps_config_item_category_option === 1) {
                    $ps_product_categories = $this->getCategoryType2($product_info['product_id']);
                } else if ($ps_config_item_category_option === 2) {
                    $ps_product_categories = $this->getCategoryType3($ps_category_id);
                } else if ($ps_config_item_category_option === 3) {
                    $ps_product_categories = $this->getCategoryType4($ps_category_info);
                } else {
                    $ps_product_categories = [];
                }

                foreach ($ps_product_categories as $category_index => $category_name) {
                    if ($category_index === 0) {
                        $ps_item['item_category'] = $category_name;
                    } else {
                        $ps_item['item_category' . ($category_index + 1)] = $category_name;
                    }
                }

                $ps_item['item_list_id'] = $ps_item_list_id;
                $ps_item['item_list_name'] = $ps_item_list_name;

                if ($ps_config_location_id) {
                    $ps_item['location_id'] = $ps_config_location_id;
                }

                if ((float) $product_info['special']) {
                    $ps_price = $this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $ps_config_item_price_tax);
                } else {
                    $ps_price = $this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $ps_config_item_price_tax);
                }

                $ps_item['price'] = $this->currency->format($ps_price, $ps_config_currency, 0, false);

                $ps_item['quantity'] = $product_info['quantity'];

                if ($product_info['minimum']) {
                    $ps_quantity_minimums[(int) $product_info['product_id']] = $product_info['minimum'];
                } else {
                    $ps_quantity_minimums[(int) $product_info['product_id']] = 1;
                }

                $ps_specials[(int) $product_info['product_id']] = (float) $product_info['special'] > 0;

                $ps_items[(int) $product_info['product_id']] = $ps_item;

                $this->session->data['ps_item_list_info'][(int) $product_info['product_id']] = [
                    'item_list_id' => $ps_item_list_id,
                    'item_list_name' => $ps_item_list_name,
                ];
            }


            if ($ps_config_track_view_item_list) {
                $ps_view_item_list = [
                    'ecommerce' => [
                        'item_list_id' => $ps_item_list_id,
                        'item_list_name' => $ps_item_list_name,
                        'items' => array_values($ps_items),
                    ],
                ];

                $args['ps_view_item_list'] = $ps_items ? json_encode($ps_view_item_list, JSON_NUMERIC_CHECK) : null;
            } else {
                $args['ps_view_item_list'] = null;
            }


            $ps_merge_items = [];

            foreach ($ps_items as $product_id => $ps_item) {
                if ($ps_specials[$product_id]) {
                    if ($ps_config_track_select_promotion) {
                        $ps_merge_items['select_promotion_' . $product_id] = [
                            'ecommerce' => [
                                'item_list_id' => $ps_item_list_id,
                                'item_list_name' => $ps_item_list_name,
                                'items' => [$ps_item],
                            ],
                        ];
                    }
                } else {
                    if ($ps_config_track_select_item) {
                        $ps_merge_items['select_item_' . $product_id] = [
                            'ecommerce' => [
                                'item_list_id' => $ps_item_list_id,
                                'item_list_name' => $ps_item_list_name,
                                'items' => [$ps_item],
                            ],
                        ];
                    }
                }

                if ($ps_config_track_add_to_wishlist) {
                    $ps_merge_items['add_to_wishlist_' . $product_id] = [
                        'ecommerce' => [
                            'currency' => $ps_config_currency,
                            'value' => $ps_item['price'],
                            'items' => [$ps_item],
                        ],
                    ];
                }
            }

            if ($ps_config_track_add_to_cart) {
                foreach ($ps_items as $product_id => $ps_item) {
                    $ps_item['quantity'] = $ps_quantity_minimums[$product_id];

                    $ps_merge_items['add_to_cart_' . $product_id] = [
                        'ecommerce' => [
                            'currency' => $ps_config_currency,
                            'value' => $ps_item['price'] * $ps_quantity_minimums[$product_id],
                            'items' => [$ps_item],
                        ],
                    ];
                }
            }

            $args['ps_merge_items'] = $ps_merge_items ? json_encode($ps_merge_items, JSON_NUMERIC_CHECK) : null;
        } else {
            $args['ps_view_item_list'] = null;
            $args['ps_merge_items'] = null;
        }


        $args['ps_track_view_item_list'] = $ps_config_track_view_item_list;


        $views = $this->model_extension_ps_enhanced_measurement_analytics_ps_enhanced_measurement->replaceCatalogViewProductCategoryBefore($args);

        $template = $this->replaceViews($route, $template, $views);
    }

    public function eventCatalogViewProductSearchBefore(string &$route, array &$args, string &$template): void
    {
        if (!$this->config->get('analytics_ps_enhanced_measurement_status')) {
            return;
        }


        $ps_config_track_add_to_wishlist = $this->config->get('analytics_ps_enhanced_measurement_track_add_to_wishlist');
        $ps_config_track_add_to_cart = $this->config->get('analytics_ps_enhanced_measurement_track_add_to_cart') || $this->config->get('analytics_ps_enhanced_measurement_adwords_add_to_cart_label');
        $ps_config_track_select_item = $this->config->get('analytics_ps_enhanced_measurement_track_select_item');
        $ps_config_track_select_promotion = $this->config->get('analytics_ps_enhanced_measurement_track_select_promotion');
        $ps_config_track_view_item_list = $this->config->get('analytics_ps_enhanced_measurement_track_view_item_list');
        $ps_config_track_search = $this->config->get('analytics_ps_enhanced_measurement_track_search');

        if (
            !$ps_config_track_add_to_wishlist &&
            !$ps_config_track_add_to_cart &&
            !$ps_config_track_select_item &&
            !$ps_config_track_select_promotion &&
            !$ps_config_track_view_item_list &&
            !$ps_config_track_search
        ) {
            return;
        }


        $this->load->language('extension/ps_enhanced_measurement/analytics/ps_enhanced_measurement');

        $this->load->model('extension/ps_enhanced_measurement/analytics/ps_enhanced_measurement');
        $this->load->model('catalog/category');
        $this->load->model('catalog/manufacturer');
        $this->load->model('catalog/product');


        $ps_config_item_category_option = (int) $this->config->get('analytics_ps_enhanced_measurement_item_category_option');
        $ps_config_item_price_tax = $this->config->get('analytics_ps_enhanced_measurement_item_price_tax');
        $ps_config_location_id = $this->config->get('analytics_ps_enhanced_measurement_location_id');
        $ps_config_item_id_option = $this->config->get('analytics_ps_enhanced_measurement_item_id');
        $ps_config_affiliation = $this->config->get('analytics_ps_enhanced_measurement_affiliation');

        $ps_config_currency = $this->config->get('analytics_ps_enhanced_measurement_currency');

        if (empty($ps_config_currency)) {
            $ps_config_currency = $this->session->data['currency'];
        }

        if (isset($this->request->get['search'])) {
            $search = $this->request->get['search'];
        } else {
            $search = '';
        }

        if (isset($this->request->get['tag'])) {
            $tag = $this->request->get['tag'];
        } elseif (isset($this->request->get['search'])) {
            $tag = $this->request->get['search'];
        } else {
            $tag = '';
        }

        if (isset($this->request->get['description'])) {
            $description = $this->request->get['description'];
        } else {
            $description = '';
        }

        if (isset($this->request->get['category_id'])) {
            $category_id = (int) $this->request->get['category_id'];
        } else {
            $category_id = 0;
        }

        if (isset($this->request->get['sub_category'])) {
            $sub_category = (int) $this->request->get['sub_category'];
        } else {
            $sub_category = 0;
        }

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'p.sort_order';
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else {
            $order = 'ASC';
        }

        if (isset($this->request->get['page'])) {
            $page = (int) $this->request->get['page'];
        } else {
            $page = 1;
        }

        if (isset($this->request->get['limit'])) {
            $limit = (int) $this->request->get['limit'];
        } else {
            $limit = $this->config->get('config_pagination');
        }

        if (isset($this->session->data['coupon'])) {
            $ps_product_coupon = $this->session->data['coupon'];
        } else if (isset($this->session->data['voucher'])) {
            $ps_product_coupon = $this->session->data['voucher'];
        } else {
            $ps_product_coupon = null;
        }


        if ($search || $tag) {
            if (isset($this->request->get['search'])) {
                $ps_item_list_name = sprintf(
                    $this->language->get('text_x_search_products'),
                    html_entity_decode($this->request->get['search'], ENT_QUOTES, 'UTF-8')
                );
                $ps_item_list_id = $this->formatListId($ps_item_list_name);
            } elseif (isset($this->request->get['tag'])) {
                $ps_item_list_name = sprintf(
                    $this->language->get('text_x_tag_products'),
                    html_entity_decode($this->request->get['tag'], ENT_QUOTES, 'UTF-8')
                );
                $ps_item_list_id = $this->formatListId($ps_item_list_name);
            } else {
                $ps_item_list_name = $this->language->get('text_search_products');
                $ps_item_list_id = $this->formatListId($ps_item_list_name);
            }

            $filter_data = [
                'filter_search' => $search,
                'filter_tag' => $tag,
                'filter_description' => $description,
                'filter_category_id' => $category_id,
                'filter_sub_category' => $sub_category,
                'sort' => $sort,
                'order' => $order,
                'start' => ($page - 1) * $limit,
                'limit' => $limit
            ];

            $products = $this->model_catalog_product->getProducts($filter_data);

            $ps_items = [];
            $ps_quantity_minimums = [];
            $ps_specials = [];

            foreach ($products as $index => $product_info) {
                $ps_item = [];

                $ps_item['item_id'] = isset($product_info[$ps_config_item_id_option]) && !empty($product_info[$ps_config_item_id_option]) ? $this->formatListId($product_info[$ps_config_item_id_option]) : $product_info['product_id'];
                $ps_item['item_name'] = html_entity_decode($product_info['name'], ENT_QUOTES, 'UTF-8');

                if ($ps_config_affiliation) {
                    $ps_item['affiliation'] = $ps_config_affiliation;
                }

                if ($ps_product_coupon) {
                    $ps_item['coupon'] = $ps_product_coupon;
                }

                if ((float) $product_info['special']) {
                    $ps_discount = $this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $ps_config_item_price_tax) - $this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $ps_config_item_price_tax);

                    $ps_item['discount'] = $this->currency->format($ps_discount, $ps_config_currency, 0, false);
                }

                $ps_item['index'] = $index;

                $ps_manufacturer_info = $this->model_catalog_manufacturer->getManufacturer((int) $product_info['manufacturer_id']);

                if ($ps_manufacturer_info) {
                    $ps_item['item_brand'] = $ps_manufacturer_info['name'];
                }

                switch ($ps_config_item_category_option) {
                    case 0:
                        $ps_product_categories = $this->getCategoryType1($product_info['product_id']);
                        break;
                    case 1:
                        $ps_product_categories = $this->getCategoryType2($product_info['product_id']);
                        break;
                    default:
                        $ps_product_categories = $this->getCategoryType1($product_info['product_id']);
                        break;
                }

                foreach ($ps_product_categories as $category_index => $category_name) {
                    if ($category_index === 0) {
                        $ps_item['item_category'] = $category_name;
                    } else {
                        $ps_item['item_category' . ($category_index + 1)] = $category_name;
                    }
                }

                $ps_item['item_list_id'] = $ps_item_list_id;
                $ps_item['item_list_name'] = $ps_item_list_name;

                if ($ps_config_location_id) {
                    $ps_item['location_id'] = $ps_config_location_id;
                }

                if ((float) $product_info['special']) {
                    $ps_price = $this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $ps_config_item_price_tax);
                } else {
                    $ps_price = $this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $ps_config_item_price_tax);
                }

                $ps_item['price'] = $this->currency->format($ps_price, $ps_config_currency, 0, false);

                $ps_item['quantity'] = $product_info['quantity'];

                if ($product_info['minimum']) {
                    $ps_quantity_minimums[(int) $product_info['product_id']] = $product_info['minimum'];
                } else {
                    $ps_quantity_minimums[(int) $product_info['product_id']] = 1;
                }

                $ps_specials[(int) $product_info['product_id']] = (float) $product_info['special'] > 0;

                $ps_items[(int) $product_info['product_id']] = $ps_item;

                $this->session->data['ps_item_list_info'][(int) $product_info['product_id']] = [
                    'item_list_id' => $ps_item_list_id,
                    'item_list_name' => $ps_item_list_name,
                ];
            }


            if ($ps_config_track_view_item_list) {
                $ps_view_item_list = [
                    'ecommerce' => [
                        'item_list_id' => $ps_item_list_id,
                        'item_list_name' => $ps_item_list_name,
                        'items' => array_values($ps_items),
                    ],
                ];

                $args['ps_view_item_list'] = $ps_items ? json_encode($ps_view_item_list, JSON_NUMERIC_CHECK) : null;
            } else {
                $args['ps_view_item_list'] = null;
            }


            if ($ps_config_track_search) {
                if (isset($this->request->get['tag'])) {
                    $ps_search = [
                        'search_term' => html_entity_decode($this->request->get['tag'], ENT_QUOTES, 'UTF-8'),
                        'search_type' => 'site_search',
                        'search_results' => count($ps_items),
                    ];
                } elseif (isset($this->request->get['search'])) {
                    $ps_search = [
                        'search_term' => html_entity_decode($this->request->get['search'], ENT_QUOTES, 'UTF-8'),
                        'search_type' => 'site_search',
                        'search_results' => count($ps_items),
                    ];
                } else {
                    $ps_search = null;
                }

                $args['ps_search'] = $ps_search ? json_encode($ps_search, JSON_NUMERIC_CHECK) : null;
            } else {
                $args['ps_search'] = null;
            }


            $ps_merge_items = [];

            foreach ($ps_items as $product_id => $ps_item) {
                if ($ps_specials[$product_id]) {
                    if ($ps_config_track_select_promotion) {
                        $ps_merge_items['select_promotion_' . $product_id] = [
                            'ecommerce' => [
                                'item_list_id' => $ps_item_list_id,
                                'item_list_name' => $ps_item_list_name,
                                'items' => [$ps_item],
                            ],
                        ];
                    }
                } else {
                    if ($ps_config_track_select_item) {
                        $ps_merge_items['select_item_' . $product_id] = [
                            'ecommerce' => [
                                'item_list_id' => $ps_item_list_id,
                                'item_list_name' => $ps_item_list_name,
                                'items' => [$ps_item],
                            ],
                        ];
                    }
                }

                if ($ps_config_track_add_to_wishlist) {
                    $ps_merge_items['add_to_wishlist_' . $product_id] = [
                        'ecommerce' => [
                            'currency' => $ps_config_currency,
                            'value' => $ps_item['price'],
                            'items' => [$ps_item],
                        ],
                    ];
                }
            }

            if ($ps_config_track_add_to_cart) {
                foreach ($ps_items as $product_id => $ps_item) {
                    $ps_item['quantity'] = $ps_quantity_minimums[$product_id];

                    $ps_merge_items['add_to_cart_' . $product_id] = [
                        'ecommerce' => [
                            'currency' => $ps_config_currency,
                            'value' => $ps_item['price'] * $ps_quantity_minimums[$product_id],
                            'items' => [$ps_item],
                        ],
                    ];
                }
            }

            $args['ps_merge_items'] = $ps_merge_items ? json_encode($ps_merge_items, JSON_NUMERIC_CHECK) : null;
        } else {
            $args['ps_view_item_list'] = null;
            $args['ps_search'] = null;
            $args['ps_merge_items'] = null;
        }


        $args['ps_track_search'] = $ps_config_track_search;
        $args['ps_track_view_item_list'] = $ps_config_track_view_item_list;


        $views = $this->model_extension_ps_enhanced_measurement_analytics_ps_enhanced_measurement->replaceCatalogViewProductSearchBefore($args);

        $template = $this->replaceViews($route, $template, $views);
    }

    public function eventCatalogViewProductSpecialBefore(string &$route, array &$args, string &$template): void
    {
        if (!$this->config->get('analytics_ps_enhanced_measurement_status')) {
            return;
        }


        $ps_config_track_add_to_wishlist = $this->config->get('analytics_ps_enhanced_measurement_track_add_to_wishlist');
        $ps_config_track_add_to_cart = $this->config->get('analytics_ps_enhanced_measurement_track_add_to_cart') || $this->config->get('analytics_ps_enhanced_measurement_adwords_add_to_cart_label');
        $ps_config_track_select_item = $this->config->get('analytics_ps_enhanced_measurement_track_select_item');
        $ps_config_track_select_promotion = $this->config->get('analytics_ps_enhanced_measurement_track_select_promotion');
        $ps_config_track_view_item_list = $this->config->get('analytics_ps_enhanced_measurement_track_view_item_list');

        if (
            !$ps_config_track_add_to_wishlist &&
            !$ps_config_track_add_to_cart &&
            !$ps_config_track_select_item &&
            !$ps_config_track_select_promotion &&
            !$ps_config_track_view_item_list
        ) {
            return;
        }


        $this->load->language('extension/ps_enhanced_measurement/analytics/ps_enhanced_measurement');

        $this->load->model('extension/ps_enhanced_measurement/analytics/ps_enhanced_measurement');
        $this->load->model('catalog/category');
        $this->load->model('catalog/manufacturer');
        $this->load->model('catalog/product');


        $ps_config_item_category_option = (int) $this->config->get('analytics_ps_enhanced_measurement_item_category_option');
        $ps_config_item_price_tax = $this->config->get('analytics_ps_enhanced_measurement_item_price_tax');
        $ps_config_location_id = $this->config->get('analytics_ps_enhanced_measurement_location_id');
        $ps_config_item_id_option = $this->config->get('analytics_ps_enhanced_measurement_item_id');
        $ps_config_affiliation = $this->config->get('analytics_ps_enhanced_measurement_affiliation');

        $ps_config_currency = $this->config->get('analytics_ps_enhanced_measurement_currency');

        if (empty($ps_config_currency)) {
            $ps_config_currency = $this->session->data['currency'];
        }

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'p.sort_order';
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else {
            $order = 'ASC';
        }

        if (isset($this->request->get['page'])) {
            $page = (int) $this->request->get['page'];
        } else {
            $page = 1;
        }

        if (isset($this->request->get['limit'])) {
            $limit = (int) $this->request->get['limit'];
        } else {
            $limit = $this->config->get('config_pagination');
        }

        if (isset($this->session->data['coupon'])) {
            $ps_product_coupon = $this->session->data['coupon'];
        } else if (isset($this->session->data['voucher'])) {
            $ps_product_coupon = $this->session->data['voucher'];
        } else {
            $ps_product_coupon = null;
        }


        $ps_item_list_name = $this->language->get('text_special_products');
        $ps_item_list_id = $this->formatListId($ps_item_list_name);


        $filter_data = [
            'sort' => $sort,
            'order' => $order,
            'start' => ($page - 1) * $limit,
            'limit' => $limit
        ];

        $products = $this->model_catalog_product->getSpecials($filter_data);

        $ps_items = [];
        $ps_quantity_minimums = [];
        $ps_specials = [];

        foreach ($products as $index => $product_info) {
            $ps_item = [];

            $ps_item['item_id'] = isset($product_info[$ps_config_item_id_option]) && !empty($product_info[$ps_config_item_id_option]) ? $this->formatListId($product_info[$ps_config_item_id_option]) : $product_info['product_id'];
            $ps_item['item_name'] = html_entity_decode($product_info['name'], ENT_QUOTES, 'UTF-8');

            if ($ps_config_affiliation) {
                $ps_item['affiliation'] = $ps_config_affiliation;
            }

            if ($ps_product_coupon) {
                $ps_item['coupon'] = $ps_product_coupon;
            }

            if ((float) $product_info['special']) {
                $ps_discount = $this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $ps_config_item_price_tax) - $this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $ps_config_item_price_tax);

                $ps_item['discount'] = $this->currency->format($ps_discount, $ps_config_currency, 0, false);
            }

            $ps_item['index'] = $index;

            $ps_manufacturer_info = $this->model_catalog_manufacturer->getManufacturer((int) $product_info['manufacturer_id']);

            if ($ps_manufacturer_info) {
                $ps_item['item_brand'] = $ps_manufacturer_info['name'];
            }

            switch ($ps_config_item_category_option) {
                case 0:
                    $ps_product_categories = $this->getCategoryType1($product_info['product_id']);
                    break;
                case 1:
                    $ps_product_categories = $this->getCategoryType2($product_info['product_id']);
                    break;
                default:
                    $ps_product_categories = $this->getCategoryType1($product_info['product_id']);
                    break;
            }

            foreach ($ps_product_categories as $category_index => $category_name) {
                if ($category_index === 0) {
                    $ps_item['item_category'] = $category_name;
                } else {
                    $ps_item['item_category' . ($category_index + 1)] = $category_name;
                }
            }

            $ps_item['item_list_id'] = $ps_item_list_id;
            $ps_item['item_list_name'] = $ps_item_list_name;

            if ($ps_config_location_id) {
                $ps_item['location_id'] = $ps_config_location_id;
            }

            if ((float) $product_info['special']) {
                $ps_price = $this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $ps_config_item_price_tax);
            } else {
                $ps_price = $this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $ps_config_item_price_tax);
            }

            $ps_item['price'] = $this->currency->format($ps_price, $ps_config_currency, 0, false);

            $ps_item['quantity'] = $product_info['quantity'];

            if ($product_info['minimum']) {
                $ps_quantity_minimums[(int) $product_info['product_id']] = $product_info['minimum'];
            } else {
                $ps_quantity_minimums[(int) $product_info['product_id']] = 1;
            }

            $ps_specials[(int) $product_info['product_id']] = (float) $product_info['special'] > 0;

            $ps_items[(int) $product_info['product_id']] = $ps_item;

            $this->session->data['ps_item_list_info'][(int) $product_info['product_id']] = [
                'item_list_id' => $ps_item_list_id,
                'item_list_name' => $ps_item_list_name,
            ];
        }


        if ($ps_config_track_view_item_list) {
            $ps_view_item_list = [
                'ecommerce' => [
                    'item_list_id' => $ps_item_list_id,
                    'item_list_name' => $ps_item_list_name,
                    'items' => array_values($ps_items),
                ],
            ];

            $args['ps_view_item_list'] = $ps_items ? json_encode($ps_view_item_list, JSON_NUMERIC_CHECK) : null;
        } else {
            $args['ps_view_item_list'] = null;
        }


        $ps_merge_items = [];

        foreach ($ps_items as $product_id => $ps_item) {
            if ($ps_specials[$product_id]) {
                if ($ps_config_track_select_promotion) {
                    $ps_merge_items['select_promotion_' . $product_id] = [
                        'ecommerce' => [
                            'item_list_id' => $ps_item_list_id,
                            'item_list_name' => $ps_item_list_name,
                            'items' => [$ps_item],
                        ],
                    ];
                }
            } else {
                if ($ps_config_track_select_item) {
                    $ps_merge_items['select_item_' . $product_id] = [
                        'ecommerce' => [
                            'item_list_id' => $ps_item_list_id,
                            'item_list_name' => $ps_item_list_name,
                            'items' => [$ps_item],
                        ],
                    ];
                }
            }

            if ($ps_config_track_add_to_wishlist) {
                $ps_merge_items['add_to_wishlist_' . $product_id] = [
                    'ecommerce' => [
                        'currency' => $ps_config_currency,
                        'value' => $ps_item['price'],
                        'items' => [$ps_item],
                    ],
                ];
            }
        }

        if ($ps_config_track_add_to_cart) {
            foreach ($ps_items as $product_id => $ps_item) {
                $ps_item['quantity'] = $ps_quantity_minimums[$product_id];

                $ps_merge_items['add_to_cart_' . $product_id] = [
                    'ecommerce' => [
                        'currency' => $ps_config_currency,
                        'value' => $ps_item['price'] * $ps_quantity_minimums[$product_id],
                        'items' => [$ps_item],
                    ],
                ];
            }
        }

        $args['ps_merge_items'] = $ps_merge_items ? json_encode($ps_merge_items, JSON_NUMERIC_CHECK) : null;


        $args['ps_track_view_item_list'] = $ps_config_track_view_item_list;


        $views = $this->model_extension_ps_enhanced_measurement_analytics_ps_enhanced_measurement->replaceCatalogViewProductSpecialBefore($args);

        $template = $this->replaceViews($route, $template, $views);
    }

    public function eventCatalogViewProductCompareBefore(string &$route, array &$args, string &$template): void
    {
        if (!$this->config->get('analytics_ps_enhanced_measurement_status')) {
            return;
        }


        $ps_config_track_add_to_wishlist = $this->config->get('analytics_ps_enhanced_measurement_track_add_to_wishlist');
        $ps_config_track_add_to_cart = $this->config->get('analytics_ps_enhanced_measurement_track_add_to_cart') || $this->config->get('analytics_ps_enhanced_measurement_adwords_add_to_cart_label');
        $ps_config_track_select_item = $this->config->get('analytics_ps_enhanced_measurement_track_select_item');
        $ps_config_track_select_promotion = $this->config->get('analytics_ps_enhanced_measurement_track_select_promotion');
        $ps_config_track_view_item_list = $this->config->get('analytics_ps_enhanced_measurement_track_view_item_list');

        if (
            !$ps_config_track_add_to_wishlist &&
            !$ps_config_track_add_to_cart &&
            !$ps_config_track_select_item &&
            !$ps_config_track_select_promotion &&
            !$ps_config_track_view_item_list
        ) {
            return;
        }


        $this->load->language('extension/ps_enhanced_measurement/analytics/ps_enhanced_measurement');

        $this->load->model('extension/ps_enhanced_measurement/analytics/ps_enhanced_measurement');
        $this->load->model('catalog/category');
        $this->load->model('catalog/manufacturer');
        $this->load->model('catalog/product');


        $ps_config_item_category_option = (int) $this->config->get('analytics_ps_enhanced_measurement_item_category_option');
        $ps_config_item_price_tax = $this->config->get('analytics_ps_enhanced_measurement_item_price_tax');
        $ps_config_location_id = $this->config->get('analytics_ps_enhanced_measurement_location_id');
        $ps_config_item_id_option = $this->config->get('analytics_ps_enhanced_measurement_item_id');
        $ps_config_affiliation = $this->config->get('analytics_ps_enhanced_measurement_affiliation');

        $ps_config_currency = $this->config->get('analytics_ps_enhanced_measurement_currency');

        if (empty($ps_config_currency)) {
            $ps_config_currency = $this->session->data['currency'];
        }

        if (isset($this->session->data['coupon'])) {
            $ps_product_coupon = $this->session->data['coupon'];
        } else if (isset($this->session->data['voucher'])) {
            $ps_product_coupon = $this->session->data['voucher'];
        } else {
            $ps_product_coupon = null;
        }


        $ps_item_list_name = $this->language->get('text_compare_products');
        $ps_item_list_id = $this->formatListId($ps_item_list_name);


        $ps_items = [];
        $ps_quantity_minimums = [];
        $ps_specials = [];

        foreach ($this->session->data['compare'] as $index => $product_id) {
            $product_info = $this->model_catalog_product->getProduct($product_id);

            $ps_item = [];

            $ps_item['item_id'] = isset($product_info[$ps_config_item_id_option]) && !empty($product_info[$ps_config_item_id_option]) ? $this->formatListId($product_info[$ps_config_item_id_option]) : $product_info['product_id'];
            $ps_item['item_name'] = html_entity_decode($product_info['name'], ENT_QUOTES, 'UTF-8');

            if ($ps_config_affiliation) {
                $ps_item['affiliation'] = $ps_config_affiliation;
            }

            if ($ps_product_coupon) {
                $ps_item['coupon'] = $ps_product_coupon;
            }

            if ((float) $product_info['special']) {
                $ps_discount = $this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $ps_config_item_price_tax) - $this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $ps_config_item_price_tax);

                $ps_item['discount'] = $this->currency->format($ps_discount, $ps_config_currency, 0, false);
            }

            $ps_item['index'] = $index;

            $ps_manufacturer_info = $this->model_catalog_manufacturer->getManufacturer((int) $product_info['manufacturer_id']);

            if ($ps_manufacturer_info) {
                $ps_item['item_brand'] = $ps_manufacturer_info['name'];
            }

            switch ($ps_config_item_category_option) {
                case 0:
                    $ps_product_categories = $this->getCategoryType1($product_info['product_id']);
                    break;
                case 1:
                    $ps_product_categories = $this->getCategoryType2($product_info['product_id']);
                    break;
                default:
                    $ps_product_categories = $this->getCategoryType1($product_info['product_id']);
                    break;
            }

            foreach ($ps_product_categories as $category_index => $category_name) {
                if ($category_index === 0) {
                    $ps_item['item_category'] = $category_name;
                } else {
                    $ps_item['item_category' . ($category_index + 1)] = $category_name;
                }
            }

            $ps_item['item_list_id'] = $ps_item_list_id;
            $ps_item['item_list_name'] = $ps_item_list_name;

            if ($ps_config_location_id) {
                $ps_item['location_id'] = $ps_config_location_id;
            }

            if ((float) $product_info['special']) {
                $ps_price = $this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $ps_config_item_price_tax);
            } else {
                $ps_price = $this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $ps_config_item_price_tax);
            }

            $ps_item['price'] = $this->currency->format($ps_price, $ps_config_currency, 0, false);

            $ps_item['quantity'] = $product_info['quantity'];

            if ($product_info['minimum']) {
                $ps_quantity_minimums[(int) $product_info['product_id']] = $product_info['minimum'];
            } else {
                $ps_quantity_minimums[(int) $product_info['product_id']] = 1;
            }

            $ps_specials[(int) $product_info['product_id']] = (float) $product_info['special'] > 0;

            $ps_items[(int) $product_info['product_id']] = $ps_item;

            $this->session->data['ps_item_list_info'][(int) $product_info['product_id']] = [
                'item_list_id' => $ps_item_list_id,
                'item_list_name' => $ps_item_list_name,
            ];
        }


        if ($ps_config_track_view_item_list) {
            $ps_view_item_list = [
                'ecommerce' => [
                    'item_list_id' => $ps_item_list_id,
                    'item_list_name' => $ps_item_list_name,
                    'items' => array_values($ps_items),
                ],
            ];

            $args['ps_view_item_list'] = $ps_items ? json_encode($ps_view_item_list, JSON_NUMERIC_CHECK) : null;
        } else {
            $args['ps_view_item_list'] = null;
        }


        $ps_merge_items = [];

        foreach ($ps_items as $product_id => $ps_item) {
            if ($ps_specials[$product_id]) {
                if ($ps_config_track_select_promotion) {
                    $ps_merge_items['select_promotion_' . $product_id] = [
                        'ecommerce' => [
                            'item_list_id' => $ps_item_list_id,
                            'item_list_name' => $ps_item_list_name,
                            'items' => [$ps_item],
                        ],
                    ];
                }
            } else {
                if ($ps_config_track_select_item) {
                    $ps_merge_items['select_item_' . $product_id] = [
                        'ecommerce' => [
                            'item_list_id' => $ps_item_list_id,
                            'item_list_name' => $ps_item_list_name,
                            'items' => [$ps_item],
                        ],
                    ];
                }
            }

            if ($ps_config_track_add_to_wishlist) {
                $ps_merge_items['add_to_wishlist_' . $product_id] = [
                    'ecommerce' => [
                        'currency' => $ps_config_currency,
                        'value' => $ps_item['price'],
                        'items' => [$ps_item],
                    ],
                ];
            }
        }

        if ($ps_config_track_add_to_cart) {
            foreach ($ps_items as $product_id => $ps_item) {
                $ps_item['quantity'] = $ps_quantity_minimums[$product_id];

                $ps_merge_items['add_to_cart_' . $product_id] = [
                    'ecommerce' => [
                        'currency' => $ps_config_currency,
                        'value' => $ps_item['price'] * $ps_quantity_minimums[$product_id],
                        'items' => [$ps_item],
                    ],
                ];
            }
        }

        $args['ps_merge_items'] = $ps_merge_items ? json_encode($ps_merge_items, JSON_NUMERIC_CHECK) : null;


        foreach ($args['products'] as $index => $product_info) {
            if (isset($args['products'][$index])) {
                $args['products'][$index]['ps_has_options'] = $this->model_extension_ps_enhanced_measurement_analytics_ps_enhanced_measurement->hasOptions($product_info['product_id']);
            }
        }


        $args['ps_track_add_to_cart'] = $ps_config_track_add_to_cart;
        $args['ps_track_select_item'] = $ps_config_track_select_item;
        $args['ps_track_select_promotion'] = $ps_config_track_select_promotion;
        $args['ps_track_view_item_list'] = $ps_config_track_view_item_list;


        $views = $this->model_extension_ps_enhanced_measurement_analytics_ps_enhanced_measurement->replaceCatalogViewProductCompareBefore($args);

        $template = $this->replaceViews($route, $template, $views);
    }

    public function eventCatalogViewProductManufacturerInfoBefore(string &$route, array &$args, string &$template): void
    {
        if (!$this->config->get('analytics_ps_enhanced_measurement_status')) {
            return;
        }


        $ps_config_track_add_to_wishlist = $this->config->get('analytics_ps_enhanced_measurement_track_add_to_wishlist');
        $ps_config_track_add_to_cart = $this->config->get('analytics_ps_enhanced_measurement_track_add_to_cart') || $this->config->get('analytics_ps_enhanced_measurement_adwords_add_to_cart_label');
        $ps_config_track_select_item = $this->config->get('analytics_ps_enhanced_measurement_track_select_item');
        $ps_config_track_select_promotion = $this->config->get('analytics_ps_enhanced_measurement_track_select_promotion');
        $ps_config_track_view_item_list = $this->config->get('analytics_ps_enhanced_measurement_track_view_item_list');

        if (
            !$ps_config_track_add_to_wishlist &&
            !$ps_config_track_add_to_cart &&
            !$ps_config_track_select_item &&
            !$ps_config_track_select_promotion &&
            !$ps_config_track_view_item_list
        ) {
            return;
        }


        $this->load->language('extension/ps_enhanced_measurement/analytics/ps_enhanced_measurement');

        $this->load->model('extension/ps_enhanced_measurement/analytics/ps_enhanced_measurement');
        $this->load->model('catalog/manufacturer');
        $this->load->model('catalog/category');
        $this->load->model('catalog/manufacturer');
        $this->load->model('catalog/product');


        $ps_config_item_category_option = (int) $this->config->get('analytics_ps_enhanced_measurement_item_category_option');
        $ps_config_item_price_tax = $this->config->get('analytics_ps_enhanced_measurement_item_price_tax');
        $ps_config_location_id = $this->config->get('analytics_ps_enhanced_measurement_location_id');
        $ps_config_item_id_option = $this->config->get('analytics_ps_enhanced_measurement_item_id');
        $ps_config_affiliation = $this->config->get('analytics_ps_enhanced_measurement_affiliation');

        $ps_config_currency = $this->config->get('analytics_ps_enhanced_measurement_currency');

        if (empty($ps_config_currency)) {
            $ps_config_currency = $this->session->data['currency'];
        }

        if (isset($this->request->get['manufacturer_id'])) {
            $manufacturer_id = (int) $this->request->get['manufacturer_id'];
        } else {
            $manufacturer_id = 0;
        }

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'p.sort_order';
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else {
            $order = 'ASC';
        }

        if (isset($this->request->get['page'])) {
            $page = (int) $this->request->get['page'];
        } else {
            $page = 1;
        }

        if (isset($this->request->get['limit'])) {
            $limit = (int) $this->request->get['limit'];
        } else {
            $limit = $this->config->get('config_pagination');
        }

        if (isset($this->session->data['coupon'])) {
            $ps_product_coupon = $this->session->data['coupon'];
        } else if (isset($this->session->data['voucher'])) {
            $ps_product_coupon = $this->session->data['voucher'];
        } else {
            $ps_product_coupon = null;
        }


        $ps_item_list_name = $this->language->get('text_manufacturer_products');
        $ps_item_list_id = $this->formatListId($ps_item_list_name);


        $filter_data = [
            'filter_manufacturer_id' => $manufacturer_id,
            'sort' => $sort,
            'order' => $order,
            'start' => ($page - 1) * $limit,
            'limit' => $limit
        ];

        $products = $this->model_catalog_product->getProducts($filter_data);

        $ps_items = [];
        $ps_quantity_minimums = [];
        $ps_specials = [];

        foreach ($products as $index => $product_info) {
            $ps_item = [];

            $ps_item['item_id'] = isset($product_info[$ps_config_item_id_option]) && !empty($product_info[$ps_config_item_id_option]) ? $this->formatListId($product_info[$ps_config_item_id_option]) : $product_info['product_id'];
            $ps_item['item_name'] = html_entity_decode($product_info['name'], ENT_QUOTES, 'UTF-8');

            if ($ps_config_affiliation) {
                $ps_item['affiliation'] = $ps_config_affiliation;
            }

            if ($ps_product_coupon) {
                $ps_item['coupon'] = $ps_product_coupon;
            }

            if ((float) $product_info['special']) {
                $ps_discount = $this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $ps_config_item_price_tax) - $this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $ps_config_item_price_tax);

                $ps_item['discount'] = $this->currency->format($ps_discount, $ps_config_currency, 0, false);
            }

            $ps_item['index'] = $index;

            $ps_manufacturer_info = $this->model_catalog_manufacturer->getManufacturer((int) $product_info['manufacturer_id']);

            if ($ps_manufacturer_info) {
                $ps_item['item_brand'] = $ps_manufacturer_info['name'];
            }

            switch ($ps_config_item_category_option) {
                case 0:
                    $ps_product_categories = $this->getCategoryType1($product_info['product_id']);
                    break;
                case 1:
                    $ps_product_categories = $this->getCategoryType2($product_info['product_id']);
                    break;
                default:
                    $ps_product_categories = $this->getCategoryType1($product_info['product_id']);
                    break;
            }

            foreach ($ps_product_categories as $category_index => $category_name) {
                if ($category_index === 0) {
                    $ps_item['item_category'] = $category_name;
                } else {
                    $ps_item['item_category' . ($category_index + 1)] = $category_name;
                }
            }

            $ps_item['item_list_id'] = $ps_item_list_id;
            $ps_item['item_list_name'] = $ps_item_list_name;

            if ($ps_config_location_id) {
                $ps_item['location_id'] = $ps_config_location_id;
            }

            if ((float) $product_info['special']) {
                $ps_price = $this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $ps_config_item_price_tax);
            } else {
                $ps_price = $this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $ps_config_item_price_tax);
            }

            $ps_item['price'] = $this->currency->format($ps_price, $ps_config_currency, 0, false);

            $ps_item['quantity'] = $product_info['quantity'];

            if ($product_info['minimum']) {
                $ps_quantity_minimums[(int) $product_info['product_id']] = $product_info['minimum'];
            } else {
                $ps_quantity_minimums[(int) $product_info['product_id']] = 1;
            }

            $ps_specials[(int) $product_info['product_id']] = (float) $product_info['special'] > 0;

            $ps_items[(int) $product_info['product_id']] = $ps_item;

            $this->session->data['ps_item_list_info'][(int) $product_info['product_id']] = [
                'item_list_id' => $ps_item_list_id,
                'item_list_name' => $ps_item_list_name,
            ];
        }


        if ($ps_config_track_view_item_list) {
            $ps_view_item_list = [
                'ecommerce' => [
                    'item_list_id' => $ps_item_list_id,
                    'item_list_name' => $ps_item_list_name,
                    'items' => array_values($ps_items),
                ],
            ];

            $args['ps_view_item_list'] = $ps_items ? json_encode($ps_view_item_list, JSON_NUMERIC_CHECK) : null;
        } else {
            $args['ps_view_item_list'] = null;
        }


        $ps_merge_items = [];

        foreach ($ps_items as $product_id => $ps_item) {
            if ($ps_specials[$product_id]) {
                if ($ps_config_track_select_promotion) {
                    $ps_merge_items['select_promotion_' . $product_id] = [
                        'ecommerce' => [
                            'item_list_id' => $ps_item_list_id,
                            'item_list_name' => $ps_item_list_name,
                            'items' => [$ps_item],
                        ],
                    ];
                }
            } else {
                if ($ps_config_track_select_item) {
                    $ps_merge_items['select_item_' . $product_id] = [
                        'ecommerce' => [
                            'item_list_id' => $ps_item_list_id,
                            'item_list_name' => $ps_item_list_name,
                            'items' => [$ps_item],
                        ],
                    ];
                }
            }

            if ($ps_config_track_add_to_wishlist) {
                $ps_merge_items['add_to_wishlist_' . $product_id] = [
                    'ecommerce' => [
                        'currency' => $ps_config_currency,
                        'value' => $ps_item['price'],
                        'items' => [$ps_item],
                    ],
                ];
            }
        }

        if ($ps_config_track_add_to_cart) {
            foreach ($ps_items as $product_id => $ps_item) {
                $ps_item['quantity'] = $ps_quantity_minimums[$product_id];

                $ps_merge_items['add_to_cart_' . $product_id] = [
                    'ecommerce' => [
                        'currency' => $ps_config_currency,
                        'value' => $ps_item['price'] * $ps_quantity_minimums[$product_id],
                        'items' => [$ps_item],
                    ],
                ];
            }
        }

        $args['ps_merge_items'] = $ps_merge_items ? json_encode($ps_merge_items, JSON_NUMERIC_CHECK) : null;


        $args['ps_track_view_item_list'] = $ps_config_track_view_item_list;


        $views = $this->model_extension_ps_enhanced_measurement_analytics_ps_enhanced_measurement->replaceCatalogViewProductManufacturerInfoBefore($args);

        $template = $this->replaceViews($route, $template, $views);
    }

    public function eventCatalogViewAccountOrderInfoBefore(string &$route, array &$args, string &$template): void
    {
        if (!$this->config->get('analytics_ps_enhanced_measurement_status')) {
            return;
        }


        $ps_config_track_add_to_cart = $this->config->get('analytics_ps_enhanced_measurement_track_add_to_cart') || $this->config->get('analytics_ps_enhanced_measurement_adwords_add_to_cart_label');
        $ps_config_track_select_item = $this->config->get('analytics_ps_enhanced_measurement_track_select_item');
        $ps_config_track_select_promotion = $this->config->get('analytics_ps_enhanced_measurement_track_select_promotion');
        $ps_config_track_view_item_list = $this->config->get('analytics_ps_enhanced_measurement_track_view_item_list');

        if (
            !$ps_config_track_add_to_cart &&
            !$ps_config_track_select_item &&
            !$ps_config_track_select_promotion &&
            !$ps_config_track_view_item_list
        ) {
            return;
        }


        $this->load->language('extension/ps_enhanced_measurement/analytics/ps_enhanced_measurement');

        $this->load->model('extension/ps_enhanced_measurement/analytics/ps_enhanced_measurement');
        $this->load->model('account/order');
        $this->load->model('catalog/category');
        $this->load->model('catalog/manufacturer');
        $this->load->model('catalog/product');


        $ps_config_item_category_option = (int) $this->config->get('analytics_ps_enhanced_measurement_item_category_option');
        $ps_config_item_price_tax = $this->config->get('analytics_ps_enhanced_measurement_item_price_tax');
        $ps_config_location_id = $this->config->get('analytics_ps_enhanced_measurement_location_id');
        $ps_config_item_id_option = $this->config->get('analytics_ps_enhanced_measurement_item_id');
        $ps_config_affiliation = $this->config->get('analytics_ps_enhanced_measurement_affiliation');

        $ps_config_currency = $this->config->get('analytics_ps_enhanced_measurement_currency');

        if (empty($ps_config_currency)) {
            $ps_config_currency = $this->session->data['currency'];
        }

        if (isset($this->request->get['order_id'])) {
            $order_id = (int) $this->request->get['order_id'];
        } else {
            $order_id = 0;
        }

        if (isset($this->session->data['coupon'])) {
            $ps_product_coupon = $this->session->data['coupon'];
        } else if (isset($this->session->data['voucher'])) {
            $ps_product_coupon = $this->session->data['voucher'];
        } else {
            $ps_product_coupon = null;
        }


        $ps_item_list_name = $this->language->get('text_purchased_products');
        $ps_item_list_id = $this->formatListId($ps_item_list_name);


        $products = $this->model_account_order->getProducts($order_id);

        $ps_items = [];
        $ps_quantity_minimums = [];
        $ps_specials = [];

        foreach ($products as $index => $product) {
            $product_info = $this->model_catalog_product->getProduct($product['product_id']);

            if ($product_info) {
                if (isset($args['products'][$index])) {
                    $args['products'][$index]['special'] = $product_info['special'];
                    $args['products'][$index]['product_id'] = $product['product_id'];
                    $args['products'][$index]['ps_has_options'] = $this->model_extension_ps_enhanced_measurement_analytics_ps_enhanced_measurement->hasOptions($product['product_id']);
                }

                $ps_item = [];

                $ps_item['item_id'] = isset($product_info[$ps_config_item_id_option]) && !empty($product_info[$ps_config_item_id_option]) ? $this->formatListId($product_info[$ps_config_item_id_option]) : $product_info['product_id'];
                $ps_item['item_name'] = html_entity_decode($product_info['name'], ENT_QUOTES, 'UTF-8');

                if ($ps_config_affiliation) {
                    $ps_item['affiliation'] = $ps_config_affiliation;
                }

                if ($ps_product_coupon) {
                    $ps_item['coupon'] = $ps_product_coupon;
                }

                if ((float) $product_info['special']) {
                    $ps_discount = $this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $ps_config_item_price_tax) - $this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $ps_config_item_price_tax);

                    $ps_item['discount'] = $this->currency->format($ps_discount, $ps_config_currency, 0, false);
                }

                $ps_item['index'] = $index;

                $ps_manufacturer_info = $this->model_catalog_manufacturer->getManufacturer((int) $product_info['manufacturer_id']);

                if ($ps_manufacturer_info) {
                    $ps_item['item_brand'] = $ps_manufacturer_info['name'];
                }

                switch ($ps_config_item_category_option) {
                    case 0:
                        $ps_product_categories = $this->getCategoryType1($product_info['product_id']);
                        break;
                    case 1:
                        $ps_product_categories = $this->getCategoryType2($product_info['product_id']);
                        break;
                    default:
                        $ps_product_categories = $this->getCategoryType1($product_info['product_id']);
                        break;
                }

                foreach ($ps_product_categories as $category_index => $category_name) {
                    if ($category_index === 0) {
                        $ps_item['item_category'] = $category_name;
                    } else {
                        $ps_item['item_category' . ($category_index + 1)] = $category_name;
                    }
                }

                $ps_item['item_list_id'] = $ps_item_list_id;
                $ps_item['item_list_name'] = $ps_item_list_name;

                if ($ps_config_location_id) {
                    $ps_item['location_id'] = $ps_config_location_id;
                }

                if ((float) $product_info['special']) {
                    $ps_price = $this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $ps_config_item_price_tax);
                } else {
                    $ps_price = $this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $ps_config_item_price_tax);
                }

                $ps_item['price'] = $this->currency->format($ps_price, $ps_config_currency, 0, false);

                $ps_item['quantity'] = $product_info['quantity'];

                if ($product_info['minimum']) {
                    $ps_quantity_minimums[(int) $product_info['product_id']] = $product_info['minimum'];
                } else {
                    $ps_quantity_minimums[(int) $product_info['product_id']] = 1;
                }

                $ps_specials[(int) $product_info['product_id']] = (float) $product_info['special'] > 0;

                $ps_items[(int) $product_info['product_id']] = $ps_item;

                $this->session->data['ps_item_list_info'][(int) $product_info['product_id']] = [
                    'item_list_id' => $ps_item_list_id,
                    'item_list_name' => $ps_item_list_name,
                ];
            } else {
                if (isset($args['products'][$index])) {
                    $args['products'][$index]['product_id'] = null;
                    $args['products'][$index]['ps_has_options'] = null;
                }
            }
        }


        if ($ps_config_track_view_item_list) {
            $ps_view_item_list = [
                'ecommerce' => [
                    'item_list_id' => $ps_item_list_id,
                    'item_list_name' => $ps_item_list_name,
                    'items' => array_values($ps_items),
                ],
            ];

            $args['ps_view_item_list'] = $ps_items ? json_encode($ps_view_item_list, JSON_NUMERIC_CHECK) : null;
        } else {
            $args['ps_view_item_list'] = null;
        }


        $ps_merge_items = [];

        foreach ($ps_items as $product_id => $ps_item) {
            if ($ps_specials[$product_id]) {
                if ($ps_config_track_select_promotion) {
                    $ps_merge_items['select_promotion_' . $product_id] = [
                        'ecommerce' => [
                            'item_list_id' => $ps_item_list_id,
                            'item_list_name' => $ps_item_list_name,
                            'items' => [$ps_item],
                        ],
                    ];
                }
            } else {
                if ($ps_config_track_select_item) {
                    $ps_merge_items['select_item_' . $product_id] = [
                        'ecommerce' => [
                            'item_list_id' => $ps_item_list_id,
                            'item_list_name' => $ps_item_list_name,
                            'items' => [$ps_item],
                        ],
                    ];
                }
            }
        }

        if ($ps_config_track_add_to_cart) {
            foreach ($ps_items as $product_id => $ps_item) {
                $ps_item['quantity'] = $ps_quantity_minimums[$product_id];

                $ps_merge_items['add_to_cart_' . $product_id] = [
                    'ecommerce' => [
                        'currency' => $ps_config_currency,
                        'value' => $ps_item['price'] * $ps_quantity_minimums[$product_id],
                        'items' => [$ps_item],
                    ],
                ];
            }
        }

        $args['ps_merge_items'] = $ps_merge_items ? json_encode($ps_merge_items, JSON_NUMERIC_CHECK) : null;


        $args['ps_track_add_to_cart'] = $ps_config_track_add_to_cart;
        $args['ps_track_select_item'] = $ps_config_track_select_item;
        $args['ps_track_select_promotion'] = $ps_config_track_select_promotion;
        $args['ps_track_view_item_list'] = $ps_config_track_view_item_list;


        $views = $this->model_extension_ps_enhanced_measurement_analytics_ps_enhanced_measurement->replaceCatalogViewAccountOrderInfoBefore($args);

        $template = $this->replaceViews($route, $template, $views);
    }

    public function eventCatalogViewAccountWishlistBefore(string &$route, array &$args, string &$template): void
    {
        if (!$this->config->get('analytics_ps_enhanced_measurement_status')) {
            return;
        }


        $ps_config_track_add_to_wishlist = $this->config->get('analytics_ps_enhanced_measurement_track_add_to_wishlist');
        $ps_config_track_add_to_cart = $this->config->get('analytics_ps_enhanced_measurement_track_add_to_cart') || $this->config->get('analytics_ps_enhanced_measurement_adwords_add_to_cart_label');
        $ps_config_track_select_item = $this->config->get('analytics_ps_enhanced_measurement_track_select_item');
        $ps_config_track_select_promotion = $this->config->get('analytics_ps_enhanced_measurement_track_select_promotion');
        $ps_config_track_view_item_list = $this->config->get('analytics_ps_enhanced_measurement_track_view_item_list');

        if (
            !$ps_config_track_add_to_wishlist &&
            !$ps_config_track_add_to_cart &&
            !$ps_config_track_select_item &&
            !$ps_config_track_select_promotion &&
            !$ps_config_track_view_item_list
        ) {
            return;
        }


        $this->load->language('extension/ps_enhanced_measurement/analytics/ps_enhanced_measurement');

        $this->load->model('extension/ps_enhanced_measurement/analytics/ps_enhanced_measurement');
        $this->load->model('account/wishlist');
        $this->load->model('catalog/category');
        $this->load->model('catalog/manufacturer');
        $this->load->model('catalog/product');

        $ps_config_item_category_option = (int) $this->config->get('analytics_ps_enhanced_measurement_item_category_option');
        $ps_config_item_price_tax = $this->config->get('analytics_ps_enhanced_measurement_item_price_tax');
        $ps_config_location_id = $this->config->get('analytics_ps_enhanced_measurement_location_id');
        $ps_config_item_id_option = $this->config->get('analytics_ps_enhanced_measurement_item_id');
        $ps_config_affiliation = $this->config->get('analytics_ps_enhanced_measurement_affiliation');

        $ps_config_currency = $this->config->get('analytics_ps_enhanced_measurement_currency');

        if (empty($ps_config_currency)) {
            $ps_config_currency = $this->session->data['currency'];
        }

        if (isset($this->session->data['coupon'])) {
            $ps_product_coupon = $this->session->data['coupon'];
        } else if (isset($this->session->data['voucher'])) {
            $ps_product_coupon = $this->session->data['voucher'];
        } else {
            $ps_product_coupon = null;
        }


        $ps_item_list_name = $this->language->get('text_wishlist_products');
        $ps_item_list_id = $this->formatListId($ps_item_list_name);


        $wishlist_items = $this->model_account_wishlist->getWishlist($this->customer->getId());

        $ps_items = [];
        $ps_quantity_minimums = [];
        $ps_specials = [];

        foreach ($wishlist_items as $index => $wishlist_item) {
            $product_info = $this->model_catalog_product->getProduct($wishlist_item['product_id']);

            if ($product_info) {
                $ps_item = [];

                $ps_item['item_id'] = isset($product_info[$ps_config_item_id_option]) && !empty($product_info[$ps_config_item_id_option]) ? $this->formatListId($product_info[$ps_config_item_id_option]) : $product_info['product_id'];
                $ps_item['item_name'] = html_entity_decode($product_info['name'], ENT_QUOTES, 'UTF-8');

                if ($ps_config_affiliation) {
                    $ps_item['affiliation'] = $ps_config_affiliation;
                }

                if ($ps_product_coupon) {
                    $ps_item['coupon'] = $ps_product_coupon;
                }

                if ((float) $product_info['special']) {
                    $ps_discount = $this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $ps_config_item_price_tax) - $this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $ps_config_item_price_tax);

                    $ps_item['discount'] = $this->currency->format($ps_discount, $ps_config_currency, 0, false);
                }

                $ps_item['index'] = $index;

                $ps_manufacturer_info = $this->model_catalog_manufacturer->getManufacturer((int) $product_info['manufacturer_id']);

                if ($ps_manufacturer_info) {
                    $ps_item['item_brand'] = $ps_manufacturer_info['name'];
                }

                switch ($ps_config_item_category_option) {
                    case 0:
                        $ps_product_categories = $this->getCategoryType1($product_info['product_id']);
                        break;
                    case 1:
                        $ps_product_categories = $this->getCategoryType2($product_info['product_id']);
                        break;
                    default:
                        $ps_product_categories = $this->getCategoryType1($product_info['product_id']);
                        break;
                }

                foreach ($ps_product_categories as $category_index => $category_name) {
                    if ($category_index === 0) {
                        $ps_item['item_category'] = $category_name;
                    } else {
                        $ps_item['item_category' . ($category_index + 1)] = $category_name;
                    }
                }

                $ps_item['item_list_id'] = $ps_item_list_id;
                $ps_item['item_list_name'] = $ps_item_list_name;

                if ($ps_config_location_id) {
                    $ps_item['location_id'] = $ps_config_location_id;
                }

                if ((float) $product_info['special']) {
                    $ps_price = $this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $ps_config_item_price_tax);
                } else {
                    $ps_price = $this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $ps_config_item_price_tax);
                }

                $ps_item['price'] = $this->currency->format($ps_price, $ps_config_currency, 0, false);

                $ps_item['quantity'] = $product_info['quantity'];

                if ($product_info['minimum']) {
                    $ps_quantity_minimums[(int) $product_info['product_id']] = $product_info['minimum'];
                } else {
                    $ps_quantity_minimums[(int) $product_info['product_id']] = 1;
                }

                $ps_specials[(int) $product_info['product_id']] = (float) $product_info['special'] > 0;

                $ps_items[(int) $product_info['product_id']] = $ps_item;

                $this->session->data['ps_item_list_info'][(int) $product_info['product_id']] = [
                    'item_list_id' => $ps_item_list_id,
                    'item_list_name' => $ps_item_list_name,
                ];
            }
        }


        if ($ps_config_track_view_item_list) {
            $ps_view_item_list = [
                'ecommerce' => [
                    'item_list_id' => $ps_item_list_id,
                    'item_list_name' => $ps_item_list_name,
                    'items' => array_values($ps_items),
                ],
            ];

            $args['ps_view_item_list'] = $ps_items ? json_encode($ps_view_item_list, JSON_NUMERIC_CHECK) : null;
        } else {
            $args['ps_view_item_list'] = null;
        }


        $ps_merge_items = [];

        foreach ($ps_items as $product_id => $ps_item) {
            if ($ps_specials[$product_id]) {
                if ($ps_config_track_select_promotion) {
                    $ps_merge_items['select_promotion_' . $product_id] = [
                        'ecommerce' => [
                            'item_list_id' => $ps_item_list_id,
                            'item_list_name' => $ps_item_list_name,
                            'items' => [$ps_item],
                        ],
                    ];
                }
            } else {
                if ($ps_config_track_select_item) {
                    $ps_merge_items['select_item_' . $product_id] = [
                        'ecommerce' => [
                            'item_list_id' => $ps_item_list_id,
                            'item_list_name' => $ps_item_list_name,
                            'items' => [$ps_item],
                        ],
                    ];
                }
            }

            if ($ps_config_track_add_to_wishlist) {
                $ps_merge_items['add_to_wishlist_' . $product_id] = [
                    'ecommerce' => [
                        'currency' => $ps_config_currency,
                        'value' => $ps_item['price'],
                        'items' => [$ps_item],
                    ],
                ];
            }
        }

        if ($ps_config_track_add_to_cart) {
            foreach ($ps_items as $product_id => $ps_item) {
                $ps_item['quantity'] = $ps_quantity_minimums[$product_id];

                $ps_merge_items['add_to_cart_' . $product_id] = [
                    'ecommerce' => [
                        'currency' => $ps_config_currency,
                        'value' => $ps_item['price'] * $ps_quantity_minimums[$product_id],
                        'items' => [$ps_item],
                    ],
                ];
            }
        }

        $args['ps_merge_items'] = $ps_merge_items ? json_encode($ps_merge_items, JSON_NUMERIC_CHECK) : null;


        $args['ps_track_view_item_list'] = $ps_config_track_view_item_list;


        $views = $this->model_extension_ps_enhanced_measurement_analytics_ps_enhanced_measurement->replaceCatalogViewAccountWishlistBefore($args);

        $template = $this->replaceViews($route, $template, $views);
    }

    public function eventCatalogViewAccountWishlistListBefore(string &$route, array &$args, string &$template): void
    {
        if (!$this->config->get('analytics_ps_enhanced_measurement_status')) {
            return;
        }


        $ps_config_track_add_to_cart = $this->config->get('analytics_ps_enhanced_measurement_track_add_to_cart') || $this->config->get('analytics_ps_enhanced_measurement_adwords_add_to_cart_label');
        $ps_config_track_select_item = $this->config->get('analytics_ps_enhanced_measurement_track_select_item');
        $ps_config_track_select_promotion = $this->config->get('analytics_ps_enhanced_measurement_track_select_promotion');

        if (
            !$ps_config_track_add_to_cart &&
            !$ps_config_track_select_item &&
            !$ps_config_track_select_promotion
        ) {
            return;
        }


        $this->load->model('extension/ps_enhanced_measurement/analytics/ps_enhanced_measurement');

        if ($args['products']) {
            foreach ($args['products'] as $index => $product_info) {
                $args['products'][$index]['ps_has_options'] = $this->model_extension_ps_enhanced_measurement_analytics_ps_enhanced_measurement->hasOptions($product_info['product_id']);
            }
        }


        $args['ps_track_add_to_cart'] = $ps_config_track_add_to_cart;
        $args['ps_track_select_item'] = $ps_config_track_select_item;
        $args['ps_track_select_promotion'] = $ps_config_track_select_promotion;


        $views = $this->model_extension_ps_enhanced_measurement_analytics_ps_enhanced_measurement->replaceCatalogViewAccountWishlistListBefore($args);

        $template = $this->replaceViews($route, $template, $views);
    }

    public function eventCatalogViewProductProductBefore(string &$route, array &$args, string &$template): void
    {
        if (!$this->config->get('analytics_ps_enhanced_measurement_status')) {
            return;
        }


        $ps_config_track_add_to_wishlist = $this->config->get('analytics_ps_enhanced_measurement_track_add_to_wishlist');
        $ps_config_track_add_to_cart = $this->config->get('analytics_ps_enhanced_measurement_track_add_to_cart') || $this->config->get('analytics_ps_enhanced_measurement_adwords_add_to_cart_label');
        $ps_config_track_select_item = $this->config->get('analytics_ps_enhanced_measurement_track_select_item');
        $ps_config_track_select_promotion = $this->config->get('analytics_ps_enhanced_measurement_track_select_promotion');
        $ps_config_track_view_promotion = $this->config->get('analytics_ps_enhanced_measurement_track_view_promotion');
        $ps_config_track_view_item = $this->config->get('analytics_ps_enhanced_measurement_track_view_item');

        if (
            !$ps_config_track_add_to_wishlist &&
            !$ps_config_track_add_to_cart &&
            !$ps_config_track_select_item &&
            !$ps_config_track_select_promotion &&
            !$ps_config_track_view_promotion &&
            !$ps_config_track_view_item
        ) {
            return;
        }


        $this->load->language('extension/ps_enhanced_measurement/analytics/ps_enhanced_measurement');

        $this->load->model('extension/ps_enhanced_measurement/analytics/ps_enhanced_measurement');
        $this->load->model('catalog/category');
        $this->load->model('catalog/manufacturer');
        $this->load->model('catalog/product');

        $ps_config_item_category_option = (int) $this->config->get('analytics_ps_enhanced_measurement_item_category_option');
        $ps_config_item_price_tax = $this->config->get('analytics_ps_enhanced_measurement_item_price_tax');
        $ps_config_location_id = $this->config->get('analytics_ps_enhanced_measurement_location_id');
        $ps_config_item_id_option = $this->config->get('analytics_ps_enhanced_measurement_item_id');
        $ps_config_affiliation = $this->config->get('analytics_ps_enhanced_measurement_affiliation');

        $ps_config_currency = $this->config->get('analytics_ps_enhanced_measurement_currency');

        if (empty($ps_config_currency)) {
            $ps_config_currency = $this->session->data['currency'];
        }

        if (isset($this->request->get['product_id'])) {
            $product_id = (int) $this->request->get['product_id'];
        } else {
            $product_id = 0;
        }

        if (isset($this->session->data['coupon'])) {
            $ps_product_coupon = $this->session->data['coupon'];
        } else if (isset($this->session->data['voucher'])) {
            $ps_product_coupon = $this->session->data['voucher'];
        } else {
            $ps_product_coupon = null;
        }


        if (isset($this->request->get['path'])) {
            $ps_parts = explode('_', (string) $this->request->get['path']);

            $args['ps_category_id'] = (int) array_pop($ps_parts);
        } else if (isset($this->request->get['category_id'])) {
            $args['ps_category_id'] = (int) $this->request->get['category_id'];
        } else {
            $args['ps_category_id'] = 0;
        }


        $ps_merge_items = [];


        if (isset($this->session->data['ps_item_list_info'], $this->session->data['ps_item_list_info'][$product_id])) {
            $ps_item_list_info = $this->session->data['ps_item_list_info'][$product_id];

            $ps_item_list_id = $ps_item_list_info['item_list_id'];
            $ps_item_list_name = $ps_item_list_info['item_list_name'];

            unset($this->session->data['ps_item_list_info']);
        } else {
            $ps_item_list_id = null;
            $ps_item_list_name = null;
        }


        $product_info = $this->model_catalog_product->getProduct($product_id);

        if ($product_info) {
            $ps_items = [];

            $ps_item = [];

            $ps_item['item_id'] = isset($product_info[$ps_config_item_id_option]) && !empty($product_info[$ps_config_item_id_option]) ? $this->formatListId($product_info[$ps_config_item_id_option]) : $product_info['product_id'];
            $ps_item['item_name'] = html_entity_decode($product_info['name'], ENT_QUOTES, 'UTF-8');

            if ($ps_config_affiliation) {
                $ps_item['affiliation'] = $ps_config_affiliation;
            }

            if ($ps_product_coupon) {
                $ps_item['coupon'] = $ps_product_coupon;
            }

            if ((float) $product_info['special']) {
                $ps_discount = $this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $ps_config_item_price_tax) - $this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $ps_config_item_price_tax);

                $ps_item['discount'] = $this->currency->format($ps_discount, $ps_config_currency, 0, false);
            }

            $ps_item['index'] = 0;

            $ps_manufacturer_info = $this->model_catalog_manufacturer->getManufacturer((int) $product_info['manufacturer_id']);

            if ($ps_manufacturer_info) {
                $ps_item['item_brand'] = $ps_manufacturer_info['name'];
            }

            if (isset($this->request->get['path'])) {
                $ps_parts = explode('_', (string) $this->request->get['path']);
                $ps_category_id = (int) array_pop($ps_parts);
                $ps_category_info = $ps_category_id > 0 ? $this->model_catalog_category->getCategory($ps_category_id) : null;
            } else if (isset($this->request->get['category_id'])) {
                $ps_category_id = (int) $this->request->get['category_id'];
                $ps_category_info = $ps_category_id > 0 ? $this->model_catalog_category->getCategory($ps_category_id) : null;
            } else {
                $ps_category_id = 0;
                $ps_category_info = null;
            }

            if ($ps_config_item_category_option === 0) {
                $ps_product_categories = $this->getCategoryType1($product_info['product_id']);
            } else if ($ps_config_item_category_option === 1) {
                $ps_product_categories = $this->getCategoryType2($product_info['product_id']);
            } else if ($ps_config_item_category_option === 2 && $ps_category_id) {
                $ps_product_categories = $this->getCategoryType3($ps_category_id);
            } else if ($ps_config_item_category_option === 3 && $ps_category_info) {
                $ps_product_categories = $this->getCategoryType4($ps_category_info);
            } else {
                $ps_product_categories = [];
            }

            foreach ($ps_product_categories as $category_index => $category_name) {
                if ($category_index === 0) {
                    $ps_item['item_category'] = $category_name;
                } else {
                    $ps_item['item_category' . ($category_index + 1)] = $category_name;
                }
            }

            if ($ps_item_list_id && $ps_item_list_name) {
                $ps_item['item_list_id'] = $ps_item_list_id;
                $ps_item['item_list_name'] = $ps_item_list_name;
            }

            if ($ps_config_location_id) {
                $ps_item['location_id'] = $ps_config_location_id;
            }

            if ((float) $product_info['special']) {
                $ps_price = $this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $ps_config_item_price_tax);
            } else {
                $ps_price = $this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $ps_config_item_price_tax);
            }

            $ps_item['price'] = $this->currency->format($ps_price, $ps_config_currency, 0, false);

            $ps_item['quantity'] = $product_info['quantity'];

            $ps_items[(int) $product_info['product_id']] = $ps_item;


            $ps_view_item = [
                'ecommerce' => [
                    'currency' => $ps_config_currency,
                    'value' => $ps_item['price'],
                    'items' => array_values($ps_items),
                ],
            ];


            $args['ps_view_item'] = null;
            $args['ps_view_promotion'] = null;

            if ($ps_config_track_view_promotion && (float) $product_info['special']) {
                $args['ps_view_promotion'] = $ps_items ? json_encode($ps_view_item, JSON_NUMERIC_CHECK) : null;
            } else if ($ps_config_track_view_item && !(float) $product_info['special']) {
                $args['ps_view_item'] = $ps_items ? json_encode($ps_view_item, JSON_NUMERIC_CHECK) : null;
            }


            foreach ($ps_items as $product_id => $ps_item) { // Add the current product to the merge stack
                if ($ps_config_track_add_to_wishlist) {
                    $ps_merge_items['add_to_wishlist_' . $product_id] = [
                        'ecommerce' => [
                            'currency' => $ps_config_currency,
                            'value' => $ps_item['price'],
                            'items' => [$ps_item],
                        ],
                    ];
                }

                if ($ps_config_track_add_to_cart) {
                    $ps_merge_items['add_to_cart_' . $product_id] = [
                        'ecommerce' => [
                            'currency' => $ps_config_currency,
                            'value' => $ps_item['price'],
                            'items' => [$ps_item],
                        ],
                    ];
                }
            }


            $ps_item_list_name = sprintf(
                $this->language->get('text_x_related_products'),
                html_entity_decode($product_info['name'], ENT_QUOTES, 'UTF-8')
            );
            $ps_item_list_id = $this->formatListId($ps_item_list_name);


            $products = $this->model_catalog_product->getRelated($product_id);

            $ps_items = [];
            $ps_quantity_minimums = [];
            $ps_specials = [];

            foreach ($products as $index => $product_info) {
                $ps_item = [];

                $ps_item['item_id'] = isset($product_info[$ps_config_item_id_option]) && !empty($product_info[$ps_config_item_id_option]) ? $this->formatListId($product_info[$ps_config_item_id_option]) : $product_info['product_id'];
                $ps_item['item_name'] = html_entity_decode($product_info['name'], ENT_QUOTES, 'UTF-8');

                if ($ps_config_affiliation) {
                    $ps_item['affiliation'] = $ps_config_affiliation;
                }

                if ($ps_product_coupon) {
                    $ps_item['coupon'] = $ps_product_coupon;
                }

                if ((float) $product_info['special']) {
                    $ps_discount = $this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $ps_config_item_price_tax) - $this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $ps_config_item_price_tax);

                    $ps_item['discount'] = $this->currency->format($ps_discount, $ps_config_currency, 0, false);
                }

                $ps_item['index'] = $index;

                $ps_manufacturer_info = $this->model_catalog_manufacturer->getManufacturer((int) $product_info['manufacturer_id']);

                if ($ps_manufacturer_info) {
                    $ps_item['item_brand'] = $ps_manufacturer_info['name'];
                }

                switch ($ps_config_item_category_option) {
                    case 0:
                        $ps_product_categories = $this->getCategoryType1($product_info['product_id']);
                        break;
                    case 1:
                        $ps_product_categories = $this->getCategoryType2($product_info['product_id']);
                        break;
                    default:
                        $ps_product_categories = $this->getCategoryType1($product_info['product_id']);
                        break;
                }

                foreach ($ps_product_categories as $category_index => $category_name) {
                    if ($category_index === 0) {
                        $ps_item['item_category'] = $category_name;
                    } else {
                        $ps_item['item_category' . ($category_index + 1)] = $category_name;
                    }
                }

                $ps_item['item_list_id'] = $ps_item_list_id;
                $ps_item['item_list_name'] = $ps_item_list_name;

                if ($ps_config_location_id) {
                    $ps_item['location_id'] = $ps_config_location_id;
                }

                if ((float) $product_info['special']) {
                    $ps_price = $this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $ps_config_item_price_tax);
                } else {
                    $ps_price = $this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $ps_config_item_price_tax);
                }

                $ps_item['price'] = $this->currency->format($ps_price, $ps_config_currency, 0, false);

                $ps_item['quantity'] = $product_info['quantity'];

                if ($product_info['minimum']) {
                    $ps_quantity_minimums[(int) $product_info['product_id']] = $product_info['minimum'];
                } else {
                    $ps_quantity_minimums[(int) $product_info['product_id']] = 1;
                }

                $ps_specials[(int) $product_info['product_id']] = (float) $product_info['special'] > 0;

                $ps_items[(int) $product_info['product_id']] = $ps_item;

                $this->session->data['ps_item_list_info'][(int) $product_info['product_id']] = [
                    'item_list_id' => $ps_item_list_id,
                    'item_list_name' => $ps_item_list_name,
                ];
            }


            foreach ($ps_items as $product_id => $ps_item) { // Add related prodcuts to the merge stack
                if ($ps_specials[$product_id]) {
                    if ($ps_config_track_select_promotion) {
                        $ps_merge_items['select_promotion_' . $product_id] = [
                            'ecommerce' => [
                                'item_list_id' => $ps_item_list_id,
                                'item_list_name' => $ps_item_list_name,
                                'items' => [$ps_item],
                            ],
                        ];
                    }
                } else {
                    if ($ps_config_track_select_item) {
                        $ps_merge_items['select_item_' . $product_id] = [
                            'ecommerce' => [
                                'item_list_id' => $ps_item_list_id,
                                'item_list_name' => $ps_item_list_name,
                                'items' => [$ps_item],
                            ],
                        ];
                    }
                }

                if ($ps_config_track_add_to_wishlist) {
                    $ps_merge_items['add_to_wishlist_' . $product_id] = [
                        'ecommerce' => [
                            'currency' => $ps_config_currency,
                            'value' => $ps_item['price'],
                            'items' => [$ps_item],
                        ],
                    ];
                }
            }

            if ($ps_config_track_add_to_cart) {
                foreach ($ps_items as $product_id => $ps_item) {
                    $ps_item['quantity'] = $ps_quantity_minimums[$product_id];

                    $ps_merge_items['add_to_cart_' . $product_id] = [
                        'ecommerce' => [
                            'currency' => $ps_config_currency,
                            'value' => $ps_item['price'] * $ps_quantity_minimums[$product_id],
                            'items' => [$ps_item],
                        ],
                    ];
                }
            }

            $args['ps_merge_items'] = $ps_merge_items ? json_encode($ps_merge_items, JSON_NUMERIC_CHECK) : null;
        } else {
            $args['ps_view_item'] = null;
            $args['ps_view_promotion'] = null;
            $args['ps_merge_items'] = null;
        }


        $args['ps_track_add_to_wishlist'] = $ps_config_track_add_to_wishlist;
        $args['ps_track_add_to_cart'] = $ps_config_track_add_to_cart;
        $args['ps_track_view_promotion'] = $ps_config_track_view_promotion;
        $args['ps_track_view_item'] = $ps_config_track_view_item;


        $views = $this->model_extension_ps_enhanced_measurement_analytics_ps_enhanced_measurement->replaceCatalogViewProductProductBefore($args);

        $template = $this->replaceViews($route, $template, $views);
    }

    /**
     * Save newsletter state on index.php?route=account/newsletter.save
     * we are going to be redirected to index.php?route=account/account
     *
     * @param string $route
     * @param array $args
     * @param string $output
     * @return void
     */
    public function eventCatalogControllerAccountNewsletterSaveAfter(string &$route, array &$args, string &$output): void
    {
        if (!$this->config->get('analytics_ps_enhanced_measurement_status')) {
            return;
        }

        $ps_config_track_generate_lead = $this->config->get('analytics_ps_enhanced_measurement_track_generate_lead') || $this->config->get('analytics_ps_enhanced_measurement_adwords_lead_label');

        if (!$ps_config_track_generate_lead) {
            return;
        }

        $json_response = json_decode($this->response->getOutput(), true);

        if (!$json_response || !isset($json_response['redirect'])) {
            return;
        }

        if (isset($this->request->post['newsletter']) && $this->request->post['newsletter'] === '1') {
            $this->session->data['ps_generate_lead_newsletter_event'] = 1;
        }
    }

    public function eventCatalogControllerInformationContactSendAfter(string &$route, array &$args, string &$output): void
    {
        if (!$this->config->get('analytics_ps_enhanced_measurement_status')) {
            return;
        }

        $ps_config_track_generate_lead = $this->config->get('analytics_ps_enhanced_measurement_track_generate_lead') || $this->config->get('analytics_ps_enhanced_measurement_adwords_lead_label');

        if (!$ps_config_track_generate_lead) {
            return;
        }

        $json_response = json_decode($this->response->getOutput(), true);

        if (!$json_response || !isset($json_response['redirect'])) {
            return;
        }

        $this->session->data['ps_generate_lead_contact_form_event'] = 1;
    }

    public function eventCatalogViewInformationContactSuccessBefore(string &$route, array &$args, string &$template): void
    {
        if (!$this->config->get('analytics_ps_enhanced_measurement_status')) {
            return;
        }

        $ps_config_track_generate_lead = $this->config->get('analytics_ps_enhanced_measurement_track_generate_lead') || $this->config->get('analytics_ps_enhanced_measurement_adwords_lead_label');

        if (!$ps_config_track_generate_lead) {
            return;
        }

        $separator = version_compare(VERSION, '4.0.2.0', '>=') ? '.' : '|';

        if (!isset($this->request->get['route'])) {
            return;
        } else if ($this->request->get['route'] !== 'information/contact' . $separator . 'success') {
            return;
        }

        if (isset($this->session->data['ps_generate_lead_contact_form_event'])) {
            unset($this->session->data['ps_generate_lead_contact_form_event']);

            $ps_config_currency = $this->config->get('analytics_ps_enhanced_measurement_currency');

            if (empty($ps_config_currency)) {
                $ps_config_currency = $this->session->data['currency'];
            }

            $ps_generate_lead_contact_form = [
                'currency' => $ps_config_currency,
                'value' => $this->currency->format($this->cart->getTotal(), $ps_config_currency, 0, false),
                'lead_source' => 'contact_form',
            ];

            $args['ps_generate_lead_contact_form'] = $ps_generate_lead_contact_form ? json_encode($ps_generate_lead_contact_form, JSON_NUMERIC_CHECK) : null;


            $this->load->model('extension/ps_enhanced_measurement/analytics/ps_enhanced_measurement');


            $args['ps_track_generate_lead'] = $ps_config_track_generate_lead;


            $views = $this->model_extension_ps_enhanced_measurement_analytics_ps_enhanced_measurement->replaceCatalogViewInformationContactSuccessBefore($args);

            $template = $this->replaceViews($route, $template, $views);
        }
    }

    public function eventCatalogControllerAccountLoginLoginAfter(string &$route, array &$args, string &$output): void
    {
        if (!$this->config->get('analytics_ps_enhanced_measurement_status')) {
            return;
        }

        if (!$this->config->get('analytics_ps_enhanced_measurement_track_login')) {
            return;
        }

        $json_response = json_decode($this->response->getOutput(), true);

        if (!$json_response || !isset($json_response['redirect'])) {
            return;
        }

        if ($this->customer->isLogged()) {
            $this->session->data['ps_login_event'] = 1;
        }
    }

    public function eventCatalogViewAccountAccountBefore(string &$route, array &$args, string &$template): void
    {
        if (!$this->config->get('analytics_ps_enhanced_measurement_status')) {
            return;
        }

        $ps_config_track_generate_lead = $this->config->get('analytics_ps_enhanced_measurement_track_generate_lead') || $this->config->get('analytics_ps_enhanced_measurement_adwords_lead_label');

        if (!$ps_config_track_generate_lead) {
            return;
        }

        if (isset($this->session->data['ps_generate_lead_newsletter_event'])) {
            unset($this->session->data['ps_generate_lead_newsletter_event']);

            $ps_config_currency = $this->config->get('analytics_ps_enhanced_measurement_currency');

            if (empty($ps_config_currency)) {
                $ps_config_currency = $this->session->data['currency'];
            }

            $ps_generate_lead_newsletter = [
                'currency' => $ps_config_currency,
                'value' => $this->currency->format($this->cart->getTotal(), $ps_config_currency, 0, false),
                'lead_source' => 'newsletter',
            ];

            $args['ps_generate_lead_newsletter'] = $ps_generate_lead_newsletter ? json_encode($ps_generate_lead_newsletter, JSON_NUMERIC_CHECK) : null;


            $this->load->model('extension/ps_enhanced_measurement/analytics/ps_enhanced_measurement');


            $args['ps_track_generate_lead'] = $ps_config_track_generate_lead;


            $views = $this->model_extension_ps_enhanced_measurement_analytics_ps_enhanced_measurement->replaceCatalogViewAccountAccountBefore($args);

            $template = $this->replaceViews($route, $template, $views);
        }
    }

    public function eventCatalogControllerCheckoutCartAddAfter(string &$route, array &$args, string &$output): void
    {
        if (!$this->config->get('analytics_ps_enhanced_measurement_status')) {
            return;
        }

        $ps_config_track_add_to_cart = $this->config->get('analytics_ps_enhanced_measurement_track_add_to_cart') || $this->config->get('analytics_ps_enhanced_measurement_adwords_add_to_cart_label');

        if (!$ps_config_track_add_to_cart) {
            return;
        }

        $json_response = json_decode($this->response->getOutput(), true);

        if (!$json_response || !isset($json_response['success'])) {
            return;
        }

        if (!isset($this->request->post['product_id'], $this->request->post['quantity'])) {
            return;
        }

        $quantity = (int) $this->request->post['quantity'];

        if ($quantity <= 0 || $quantity > PHP_INT_MAX) {
            return;
        }


        $this->load->model('catalog/product');

        $product_info = $this->model_catalog_product->getProduct((int) $this->request->post['product_id']);

        if ($product_info) {
            $this->load->language('extension/ps_enhanced_measurement/analytics/ps_enhanced_measurement');
            $this->load->language('checkout/cart');

            $this->load->model('extension/ps_enhanced_measurement/analytics/ps_enhanced_measurement');
            $this->load->model('catalog/category');
            $this->load->model('catalog/manufacturer');


            $ps_config_item_category_option = (int) $this->config->get('analytics_ps_enhanced_measurement_item_category_option');
            $ps_config_item_price_tax = $this->config->get('analytics_ps_enhanced_measurement_item_price_tax');
            $ps_config_location_id = $this->config->get('analytics_ps_enhanced_measurement_location_id');
            $ps_config_item_id_option = $this->config->get('analytics_ps_enhanced_measurement_item_id');
            $ps_config_affiliation = $this->config->get('analytics_ps_enhanced_measurement_affiliation');

            $ps_config_currency = $this->config->get('analytics_ps_enhanced_measurement_currency');

            if (empty($ps_config_currency)) {
                $ps_config_currency = $this->session->data['currency'];
            }

            $options = isset($this->request->post['option']) ? array_filter((array) $this->request->post['option']) : [];


            if (isset($this->session->data['coupon'])) {
                $ps_product_coupon = $this->session->data['coupon'];
            } else if (isset($this->session->data['voucher'])) {
                $ps_product_coupon = $this->session->data['voucher'];
            } else {
                $ps_product_coupon = null;
            }


            $ps_item = [];

            $ps_item['item_id'] = isset($product_info[$ps_config_item_id_option]) && !empty($product_info[$ps_config_item_id_option]) ? $this->formatListId($product_info[$ps_config_item_id_option]) : $product_info['product_id'];
            $ps_item['item_name'] = html_entity_decode($product_info['name'], ENT_QUOTES, 'UTF-8');

            if ($ps_config_affiliation) {
                $ps_item['affiliation'] = $ps_config_affiliation;
            }

            if ($ps_product_coupon) {
                $ps_item['coupon'] = $ps_product_coupon;
            }

            if ((float) $product_info['special']) {
                $ps_discount = $this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $ps_config_item_price_tax) - $this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $ps_config_item_price_tax);

                $ps_item['discount'] = $this->currency->format($ps_discount, $ps_config_currency, 0, false);
            }

            $ps_item['index'] = 0;

            $ps_manufacturer_info = $this->model_catalog_manufacturer->getManufacturer((int) $product_info['manufacturer_id']);

            if ($ps_manufacturer_info) {
                $ps_item['item_brand'] = $ps_manufacturer_info['name'];
            }

            if (isset($this->request->post['category_id'])) {
                $ps_category_id = (int) $this->request->post['category_id'];
                $ps_category_info = $ps_category_id > 0 ? $this->model_catalog_category->getCategory($ps_category_id) : null;
            } else {
                $ps_category_id = 0;
                $ps_category_info = null;
            }

            if ($ps_config_item_category_option === 0) {
                $ps_product_categories = $this->getCategoryType1($product_info['product_id']);
            } else if ($ps_config_item_category_option === 1) {
                $ps_product_categories = $this->getCategoryType2($product_info['product_id']);
            } else if ($ps_config_item_category_option === 2 && $ps_category_id) {
                $ps_product_categories = $this->getCategoryType3($ps_category_id);
            } else if ($ps_config_item_category_option === 3 && $ps_category_info) {
                $ps_product_categories = $this->getCategoryType4($ps_category_info);
            } else {
                $ps_product_categories = [];
            }

            foreach ($ps_product_categories as $category_index => $category_name) {
                if ($category_index === 0) {
                    $ps_item['item_category'] = $category_name;
                } else {
                    $ps_item['item_category' . ($category_index + 1)] = $category_name;
                }
            }

            if ($product_info['variant']) {
                foreach ($product_info['variant'] as $key => $value) {
                    $options[$key] = $value;
                }
            }


            $product_option_info = $this->model_extension_ps_enhanced_measurement_analytics_ps_enhanced_measurement->getProductOptionInfo($options, $product_info['product_id']);

            $option_price = $product_option_info['option_price'];
            $ps_variants = $product_option_info['variant'];


            $base_price = $product_info['price'];

            $product_discount_info = $this->model_extension_ps_enhanced_measurement_analytics_ps_enhanced_measurement->getProductDiscount($product_info['product_id'], $quantity);

            if ($product_discount_info) {
                $base_price = $product_discount_info;
            }


            $product_special_info = $this->model_extension_ps_enhanced_measurement_analytics_ps_enhanced_measurement->getProductSpecial($product_info['product_id']);

            if ($product_special_info) {
                $base_price = $product_special_info;
            }

            if (isset($this->request->post['subscription_plan_id'])) {
                $product_subscription_info = $this->model_extension_ps_enhanced_measurement_analytics_ps_enhanced_measurement->getProductScubscription(
                    $product_info['product_id'],
                    (int) $this->request->post['subscription_plan_id']
                );

                if ($product_subscription_info) {
                    $base_price = $product_subscription_info['price'];

                    if ($product_subscription_info['trial_status']) {
                        $base_price = $product_subscription_info['trial_price'];
                    }

                    if ($ps_subscription_description = $this->getProductSubscriptionDescription($product_subscription_info, $product_info['tax_class_id'], $ps_config_currency, $ps_config_item_price_tax)) {
                        $ps_variants[] = $ps_subscription_description;
                    }
                }
            }

            if ($ps_variants) {
                $ps_item['item_variant'] = implode(', ', $ps_variants);
            }

            if ($ps_config_location_id) {
                $ps_item['location_id'] = $ps_config_location_id;
            }

            if ((float) $product_info['special']) {
                $ps_price = $this->tax->calculate($base_price + $option_price, $product_info['tax_class_id'], $ps_config_item_price_tax);
            } else {
                $ps_price = $this->tax->calculate($base_price + $option_price, $product_info['tax_class_id'], $ps_config_item_price_tax);
            }

            $ps_item['price'] = $this->currency->format($ps_price, $ps_config_currency, 0, false);

            $ps_item['quantity'] = $quantity;

            $json_response['ps_add_to_cart'] = [
                'ecommerce' => [
                    'currency' => $ps_config_currency,
                    'value' => $this->currency->format($ps_price * $quantity, $ps_config_currency, 0, false),
                    'items' => [$ps_item],
                ],
            ];

            $this->response->setOutput(json_encode($json_response, JSON_NUMERIC_CHECK));
        }
    }

    public function eventCatalogViewCheckoutPaymentMethodBefore(string &$route, array &$args, string &$template): void
    {
        if (!$this->config->get('analytics_ps_enhanced_measurement_status')) {
            return;
        }


        $ps_config_track_add_payment_info = $this->config->get('analytics_ps_enhanced_measurement_track_add_payment_info');

        if (!$ps_config_track_add_payment_info) {
            return;
        }


        $this->load->model('extension/ps_enhanced_measurement/analytics/ps_enhanced_measurement');


        $args['ps_track_add_payment_info'] = $ps_config_track_add_payment_info;


        $views = $this->model_extension_ps_enhanced_measurement_analytics_ps_enhanced_measurement->replaceCatalogViewCheckoutPaymentMethodBefore($args);

        $template = $this->replaceViews($route, $template, $views);
    }

    public function eventCatalogViewCheckoutPaymentMethodSaveAfter(string &$route, array &$args, string &$output): void
    {
        if (!$this->config->get('analytics_ps_enhanced_measurement_status')) {
            return;
        }

        if (!$this->config->get('analytics_ps_enhanced_measurement_track_add_payment_info')) {
            return;
        }

        $json_response = json_decode($this->response->getOutput(), true);

        if (!$json_response || !isset($json_response['success'])) {
            return;
        }


        $this->load->language('extension/ps_enhanced_measurement/analytics/ps_enhanced_measurement');

        $this->load->model('extension/ps_enhanced_measurement/analytics/ps_enhanced_measurement');
        $this->load->model('catalog/category');
        $this->load->model('catalog/manufacturer');
        $this->load->model('catalog/product');
        $this->load->model('checkout/cart');


        $ps_config_item_category_option = (int) $this->config->get('analytics_ps_enhanced_measurement_item_category_option');
        $ps_config_item_price_tax = $this->config->get('analytics_ps_enhanced_measurement_item_price_tax');
        $ps_config_location_id = $this->config->get('analytics_ps_enhanced_measurement_location_id');
        $ps_config_item_id_option = $this->config->get('analytics_ps_enhanced_measurement_item_id');
        $ps_config_affiliation = $this->config->get('analytics_ps_enhanced_measurement_affiliation');

        $ps_config_currency = $this->config->get('analytics_ps_enhanced_measurement_currency');

        if (empty($ps_config_currency)) {
            $ps_config_currency = $this->session->data['currency'];
        }

        if (isset($this->session->data['coupon'])) {
            $ps_product_coupon = $this->session->data['coupon'];
        } else if (isset($this->session->data['voucher'])) {
            $ps_product_coupon = $this->session->data['voucher'];
        } else {
            $ps_product_coupon = null;
        }


        $ps_item_list_name = $this->language->get('text_checkout_products');
        $ps_item_list_id = $this->formatListId($ps_item_list_name);


        $products = $this->model_checkout_cart->getProducts();

        $ps_items = [];

        foreach ($products as $index => $product_info) {
            $ps_original_product_info = $this->model_catalog_product->getProduct((int) $product_info['product_id']);

            $ps_item = [];

            $ps_item['item_id'] = isset($ps_original_product_info[$ps_config_item_id_option]) && !empty($ps_original_product_info[$ps_config_item_id_option]) ? $this->formatListId($ps_original_product_info[$ps_config_item_id_option]) : $ps_original_product_info['product_id'];
            $ps_item['item_name'] = html_entity_decode($product_info['name'], ENT_QUOTES, 'UTF-8');

            if ($ps_config_affiliation) {
                $ps_item['affiliation'] = $ps_config_affiliation;
            }

            if ($ps_product_coupon) {
                $ps_item['coupon'] = $ps_product_coupon;
            }

            if ((float) $ps_original_product_info['special']) {
                $ps_discount = $this->tax->calculate($ps_original_product_info['price'], $ps_original_product_info['tax_class_id'], $ps_config_item_price_tax) - $this->tax->calculate($ps_original_product_info['special'], $ps_original_product_info['tax_class_id'], $ps_config_item_price_tax);

                $ps_item['discount'] = $this->currency->format($ps_discount, $ps_config_currency, 0, false);
            }

            $ps_item['index'] = $index;

            $ps_manufacturer_info = $this->model_extension_ps_enhanced_measurement_analytics_ps_enhanced_measurement->getManufacturerNameByProductId($product_info['product_id']);

            if ($ps_manufacturer_info) {
                $ps_item['item_brand'] = $ps_manufacturer_info['name'];
            }

            switch ($ps_config_item_category_option) {
                case 0:
                    $ps_product_categories = $this->getCategoryType1($product_info['product_id']);
                    break;
                case 1:
                    $ps_product_categories = $this->getCategoryType2($product_info['product_id']);
                    break;
                default:
                    $ps_product_categories = $this->getCategoryType1($product_info['product_id']);
                    break;
            }

            foreach ($ps_product_categories as $category_index => $category_name) {
                if ($category_index === 0) {
                    $ps_item['item_category'] = $category_name;
                } else {
                    $ps_item['item_category' . ($category_index + 1)] = $category_name;
                }
            }

            $ps_item['item_list_id'] = $ps_item_list_id;
            $ps_item['item_list_name'] = $ps_item_list_name;

            $ps_variants = [];

            foreach ($product_info['option'] as $option) {
                $ps_variants[] = html_entity_decode($option['name'] . ': ' . $option['value'], ENT_QUOTES, 'UTF-8');
            }

            if ($product_info['subscription'] && $ps_subscription_description = $this->getProductSubscriptionDescription($product_info['subscription'], $product_info['tax_class_id'], $ps_config_currency, $ps_config_item_price_tax)) {
                $ps_variants[] = $ps_subscription_description;
            }

            if ($ps_variants) {
                $ps_item['item_variant'] = implode(', ', $ps_variants);
            }

            if ($ps_config_location_id) {
                $ps_item['location_id'] = $ps_config_location_id;
            }

            $ps_price = $this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $ps_config_item_price_tax);

            $ps_item['price'] = $this->currency->format($ps_price, $ps_config_currency, 0, false);

            $ps_item['quantity'] = $product_info['quantity'];

            $ps_items[(int) $product_info['cart_id']] = $ps_item;
        }


        $payment_type = '';

        if (isset($this->request->post['payment_method'])) {
            $this->load->model('setting/extension');

            $code = $this->request->post['payment_method'];

            if ($extension_info = $this->model_setting_extension->getExtensionByCode('payment', $code)) {
                $this->load->language('extension/' . $extension_info['extension'] . '/payment/' . $extension_info['code'], 'extension');

                $payment_type = $this->language->get('extension_heading_title') . ' (' . $code . ')';
            }

            if (!$payment_type) {
                $code = substr($this->request->post['payment_method'], 0, strpos($this->request->post['payment_method'], '.'));

                if ($extension_info = $this->model_setting_extension->getExtensionByCode('payment', $code)) {
                    $this->load->language('extension/' . $extension_info['extension'] . '/payment/' . $extension_info['code'], 'extension');

                    $payment_type = $this->language->get('extension_heading_title') . ' (' . $code . ')';
                }
            }
        }

        $json_response['ps_add_payment_info'] = [
            'ecommerce' => [
                'currency' => $ps_config_currency,
                'value' => $this->currency->format($this->cart->getTotal(), $ps_config_currency, 0, false),
                'coupon' => $ps_product_coupon ? $ps_product_coupon : '',
                'payment_type' => $payment_type,
                'items' => array_values($ps_items),
            ],
        ];

        $this->response->setOutput(json_encode($json_response, JSON_NUMERIC_CHECK));
    }

    public function eventCatalogViewCheckoutShippingMethodBefore(string &$route, array &$args, string &$template): void
    {
        if (!$this->config->get('analytics_ps_enhanced_measurement_status')) {
            return;
        }


        $ps_config_track_add_shipping_info = $this->config->get('analytics_ps_enhanced_measurement_track_add_shipping_info');

        if (!$ps_config_track_add_shipping_info) {
            return;
        }


        $this->load->model('extension/ps_enhanced_measurement/analytics/ps_enhanced_measurement');


        $args['ps_track_add_shipping_info'] = $ps_config_track_add_shipping_info;

        $views = $this->model_extension_ps_enhanced_measurement_analytics_ps_enhanced_measurement->replaceCatalogViewCheckoutShippingMethodBefore($args);

        $template = $this->replaceViews($route, $template, $views);
    }

    public function eventCatalogViewCheckoutShippingMethodSaveAfter(string &$route, array &$args, string &$output): void
    {
        if (!$this->config->get('analytics_ps_enhanced_measurement_status')) {
            return;
        }

        if (!$this->config->get('analytics_ps_enhanced_measurement_track_add_shipping_info')) {
            return;
        }

        $json_response = json_decode($this->response->getOutput(), true);

        if (!$json_response || !isset($json_response['success'])) {
            return;
        }


        $this->load->language('extension/ps_enhanced_measurement/analytics/ps_enhanced_measurement');

        $this->load->model('extension/ps_enhanced_measurement/analytics/ps_enhanced_measurement');
        $this->load->model('catalog/category');
        $this->load->model('catalog/manufacturer');
        $this->load->model('catalog/product');
        $this->load->model('checkout/cart');


        $ps_config_item_category_option = (int) $this->config->get('analytics_ps_enhanced_measurement_item_category_option');
        $ps_config_item_price_tax = $this->config->get('analytics_ps_enhanced_measurement_item_price_tax');
        $ps_config_location_id = $this->config->get('analytics_ps_enhanced_measurement_location_id');
        $ps_config_item_id_option = $this->config->get('analytics_ps_enhanced_measurement_item_id');
        $ps_config_affiliation = $this->config->get('analytics_ps_enhanced_measurement_affiliation');

        $ps_config_currency = $this->config->get('analytics_ps_enhanced_measurement_currency');

        if (empty($ps_config_currency)) {
            $ps_config_currency = $this->session->data['currency'];
        }

        if (isset($this->session->data['coupon'])) {
            $ps_product_coupon = $this->session->data['coupon'];
        } else if (isset($this->session->data['voucher'])) {
            $ps_product_coupon = $this->session->data['voucher'];
        } else {
            $ps_product_coupon = null;
        }


        $ps_item_list_name = $this->language->get('text_checkout_products');
        $ps_item_list_id = $this->formatListId($ps_item_list_name);


        $products = $this->model_checkout_cart->getProducts();

        $ps_items = [];

        foreach ($products as $index => $product_info) {
            $ps_original_product_info = $this->model_catalog_product->getProduct((int) $product_info['product_id']);

            $ps_item = [];

            $ps_item['item_id'] = isset($ps_original_product_info[$ps_config_item_id_option]) && !empty($ps_original_product_info[$ps_config_item_id_option]) ? $this->formatListId($ps_original_product_info[$ps_config_item_id_option]) : $ps_original_product_info['product_id'];
            $ps_item['item_name'] = html_entity_decode($product_info['name'], ENT_QUOTES, 'UTF-8');

            if ($ps_config_affiliation) {
                $ps_item['affiliation'] = $ps_config_affiliation;
            }

            if ($ps_product_coupon) {
                $ps_item['coupon'] = $ps_product_coupon;
            }

            if ((float) $ps_original_product_info['special']) {
                $ps_discount = $this->tax->calculate($ps_original_product_info['price'], $ps_original_product_info['tax_class_id'], $ps_config_item_price_tax) - $this->tax->calculate($ps_original_product_info['special'], $ps_original_product_info['tax_class_id'], $ps_config_item_price_tax);

                $ps_item['discount'] = $this->currency->format($ps_discount, $ps_config_currency, 0, false);
            }

            $ps_item['index'] = $index;

            $ps_manufacturer_info = $this->model_extension_ps_enhanced_measurement_analytics_ps_enhanced_measurement->getManufacturerNameByProductId($product_info['product_id']);

            if ($ps_manufacturer_info) {
                $ps_item['item_brand'] = $ps_manufacturer_info['name'];
            }

            switch ($ps_config_item_category_option) {
                case 0:
                    $ps_product_categories = $this->getCategoryType1($product_info['product_id']);
                    break;
                case 1:
                    $ps_product_categories = $this->getCategoryType2($product_info['product_id']);
                    break;
                default:
                    $ps_product_categories = $this->getCategoryType1($product_info['product_id']);
                    break;
            }

            foreach ($ps_product_categories as $category_index => $category_name) {
                if ($category_index === 0) {
                    $ps_item['item_category'] = $category_name;
                } else {
                    $ps_item['item_category' . ($category_index + 1)] = $category_name;
                }
            }

            $ps_item['item_list_id'] = $ps_item_list_id;
            $ps_item['item_list_name'] = $ps_item_list_name;

            $ps_variants = [];

            foreach ($product_info['option'] as $option) {
                $ps_variants[] = html_entity_decode($option['name'] . ': ' . $option['value'], ENT_QUOTES, 'UTF-8');
            }

            if ($product_info['subscription'] && $ps_subscription_description = $this->getProductSubscriptionDescription($product_info['subscription'], $product_info['tax_class_id'], $ps_config_currency, $ps_config_item_price_tax)) {
                $ps_variants[] = $ps_subscription_description;
            }

            if ($ps_variants) {
                $ps_item['item_variant'] = implode(', ', $ps_variants);
            }

            if ($ps_config_location_id) {
                $ps_item['location_id'] = $ps_config_location_id;
            }

            $ps_price = $this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $ps_config_item_price_tax);

            $ps_item['price'] = $this->currency->format($ps_price, $ps_config_currency, 0, false);

            $ps_item['quantity'] = $product_info['quantity'];

            $ps_items[(int) $product_info['cart_id']] = $ps_item;
        }


        $shipping_tier = '';

        if (isset($this->request->post['shipping_method'])) {
            $this->load->model('setting/extension');

            $code = $this->request->post['shipping_method'];

            if ($extension_info = $this->model_setting_extension->getExtensionByCode('shipping', $code)) {
                $this->load->language('extension/' . $extension_info['extension'] . '/shipping/' . $extension_info['code'], 'extension');

                $shipping_tier = $this->language->get('extension_heading_title') . ' (' . $code . ')';
            }

            if (!$shipping_tier) {
                $code = substr($this->request->post['shipping_method'], 0, strpos($this->request->post['shipping_method'], '.'));

                if ($extension_info = $this->model_setting_extension->getExtensionByCode('shipping', $code)) {
                    $this->load->language('extension/' . $extension_info['extension'] . '/shipping/' . $extension_info['code'], 'extension');

                    $shipping_tier = $this->language->get('extension_heading_title') . ' (' . $code . ')';
                }
            }
        }

        $json_response['ps_add_shipping_info'] = [
            'ecommerce' => [
                'currency' => $ps_config_currency,
                'value' => $this->currency->format($this->cart->getTotal(), $ps_config_currency, 0, false),
                'coupon' => $ps_product_coupon ? $ps_product_coupon : '',
                'shipping_tier' => $shipping_tier,
                'items' => array_values($ps_items),
            ],
        ];

        $this->response->setOutput(json_encode($json_response, JSON_NUMERIC_CHECK));
    }

    public function eventCatalogViewCheckoutConfirmBefore(string &$route, array &$args, string &$template): void
    {
        if (!$this->config->get('analytics_ps_enhanced_measurement_status')) {
            return;
        }


        $ps_config_track_select_item = $this->config->get('analytics_ps_enhanced_measurement_track_select_item');
        $ps_config_track_select_promotion = $this->config->get('analytics_ps_enhanced_measurement_track_select_promotion');

        if (
            !$ps_config_track_select_item &&
            !$ps_config_track_select_promotion
        ) {
            return;
        }


        $this->load->model('extension/ps_enhanced_measurement/analytics/ps_enhanced_measurement');
        $this->load->model('catalog/product');
        $this->load->model('checkout/cart');


        $products = $this->model_checkout_cart->getProducts();

        foreach ($products as $index => $product_info) {
            $ps_original_product_info = $this->model_catalog_product->getProduct((int) $product_info['product_id']);

            if (isset($args['products'][$index])) {
                $args['products'][$index]['special'] = $ps_original_product_info['special'];
            }
        }


        $args['ps_track_select_item'] = $ps_config_track_select_item;
        $args['ps_track_select_promotion'] = $ps_config_track_select_promotion;


        $views = $this->model_extension_ps_enhanced_measurement_analytics_ps_enhanced_measurement->replaceCatalogViewCheckoutConfirmBefore($args);

        $template = $this->replaceViews($route, $template, $views);
    }

    public function eventCatalogViewCheckoutCheckoutBefore(string &$route, array &$args, string &$template): void
    {
        if (!$this->config->get('analytics_ps_enhanced_measurement_status')) {
            return;
        }


        $ps_config_track_begin_checkout = $this->config->get('analytics_ps_enhanced_measurement_track_begin_checkout') || $this->config->get('analytics_ps_enhanced_measurement_adwords_begin_checkout_label');
        $ps_config_track_qualify_lead = $this->config->get('analytics_ps_enhanced_measurement_track_qualify_lead');

        if (
            !$ps_config_track_begin_checkout &&
            !$ps_config_track_qualify_lead
        ) {
            return;
        }


        $this->load->language('extension/ps_enhanced_measurement/analytics/ps_enhanced_measurement');

        $this->load->model('extension/ps_enhanced_measurement/analytics/ps_enhanced_measurement');
        $this->load->model('catalog/category');
        $this->load->model('catalog/manufacturer');
        $this->load->model('catalog/product');
        $this->load->model('checkout/cart');


        $ps_config_item_category_option = (int) $this->config->get('analytics_ps_enhanced_measurement_item_category_option');
        $ps_config_item_price_tax = $this->config->get('analytics_ps_enhanced_measurement_item_price_tax');
        $ps_config_location_id = $this->config->get('analytics_ps_enhanced_measurement_location_id');
        $ps_config_item_id_option = $this->config->get('analytics_ps_enhanced_measurement_item_id');
        $ps_config_affiliation = $this->config->get('analytics_ps_enhanced_measurement_affiliation');

        $ps_config_currency = $this->config->get('analytics_ps_enhanced_measurement_currency');

        if (empty($ps_config_currency)) {
            $ps_config_currency = $this->session->data['currency'];
        }

        if (isset($this->session->data['coupon'])) {
            $ps_product_coupon = $this->session->data['coupon'];
        } else if (isset($this->session->data['voucher'])) {
            $ps_product_coupon = $this->session->data['voucher'];
        } else {
            $ps_product_coupon = null;
        }


        $ps_item_list_name = $this->language->get('text_checkout_products');
        $ps_item_list_id = $this->formatListId($ps_item_list_name);



        $products = $this->model_checkout_cart->getProducts();

        $ps_items = [];

        foreach ($products as $index => $product_info) {
            $ps_original_product_info = $this->model_catalog_product->getProduct((int) $product_info['product_id']);

            $ps_item = [];

            $ps_item['item_id'] = isset($ps_original_product_info[$ps_config_item_id_option]) && !empty($ps_original_product_info[$ps_config_item_id_option]) ? $this->formatListId($ps_original_product_info[$ps_config_item_id_option]) : $ps_original_product_info['product_id'];
            $ps_item['item_name'] = html_entity_decode($product_info['name'], ENT_QUOTES, 'UTF-8');

            if ($ps_config_affiliation) {
                $ps_item['affiliation'] = $ps_config_affiliation;
            }

            if ($ps_product_coupon) {
                $ps_item['coupon'] = $ps_product_coupon;
            }

            if ((float) $ps_original_product_info['special']) {
                $ps_discount = $this->tax->calculate($ps_original_product_info['price'], $ps_original_product_info['tax_class_id'], $ps_config_item_price_tax) - $this->tax->calculate($ps_original_product_info['special'], $ps_original_product_info['tax_class_id'], $ps_config_item_price_tax);

                $ps_item['discount'] = $this->currency->format($ps_discount, $ps_config_currency, 0, false);
            }

            $ps_item['index'] = $index;

            $ps_manufacturer_info = $this->model_extension_ps_enhanced_measurement_analytics_ps_enhanced_measurement->getManufacturerNameByProductId($product_info['product_id']);

            if ($ps_manufacturer_info) {
                $ps_item['item_brand'] = $ps_manufacturer_info['name'];
            }

            switch ($ps_config_item_category_option) {
                case 0:
                    $ps_product_categories = $this->getCategoryType1($product_info['product_id']);
                    break;
                case 1:
                    $ps_product_categories = $this->getCategoryType2($product_info['product_id']);
                    break;
                default:
                    $ps_product_categories = $this->getCategoryType1($product_info['product_id']);
                    break;
            }

            foreach ($ps_product_categories as $category_index => $category_name) {
                if ($category_index === 0) {
                    $ps_item['item_category'] = $category_name;
                } else {
                    $ps_item['item_category' . ($category_index + 1)] = $category_name;
                }
            }

            $ps_item['item_list_id'] = $ps_item_list_id;
            $ps_item['item_list_name'] = $ps_item_list_name;

            $ps_variants = [];

            foreach ($product_info['option'] as $option) {
                $ps_variants[] = html_entity_decode($option['name'] . ': ' . $option['value'], ENT_QUOTES, 'UTF-8');
            }

            if ($product_info['subscription'] && $ps_subscription_description = $this->getProductSubscriptionDescription($product_info['subscription'], $product_info['tax_class_id'], $ps_config_currency, $ps_config_item_price_tax)) {
                $ps_variants[] = $ps_subscription_description;
            }

            if ($ps_variants) {
                $ps_item['item_variant'] = implode(', ', $ps_variants);
            }

            if ($ps_config_location_id) {
                $ps_item['location_id'] = $ps_config_location_id;
            }

            $ps_price = $this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $ps_config_item_price_tax);

            $ps_item['price'] = $this->currency->format($ps_price, $ps_config_currency, 0, false);

            $ps_item['quantity'] = $product_info['quantity'];

            $ps_items[(int) $product_info['cart_id']] = $ps_item;

            $this->session->data['ps_item_list_info'][(int) $product_info['product_id']] = [
                'item_list_id' => $ps_item_list_id,
                'item_list_name' => $ps_item_list_name,
            ];
        }


        if ($ps_config_track_begin_checkout) {
            $ps_begin_checkout = [
                'ecommerce' => [
                    'currency' => $ps_config_currency,
                    'value' => $this->currency->format($this->cart->getTotal(), $ps_config_currency, 0, false),
                    'coupon' => $ps_product_coupon ? $ps_product_coupon : '',
                    'items' => array_values($ps_items),
                ],
            ];

            $args['ps_begin_checkout'] = $ps_items ? json_encode($ps_begin_checkout, JSON_NUMERIC_CHECK) : null;
        } else {
            $args['ps_begin_checkout'] = null;
        }


        if ($ps_config_track_qualify_lead) {
            $ps_qualify_lead = [
                'currency' => $ps_config_currency,
                'value' => $this->currency->format($this->cart->getTotal(), $ps_config_currency, 0, false),
            ];

            $args['ps_qualify_lead'] = $ps_qualify_lead ? json_encode($ps_qualify_lead, JSON_NUMERIC_CHECK) : null;
        } else {
            $args['ps_qualify_lead'] = null;
        }


        $args['ps_track_begin_checkout'] = $ps_config_track_begin_checkout;
        $args['ps_track_qualify_lead'] = $ps_config_track_qualify_lead;


        $views = $this->model_extension_ps_enhanced_measurement_analytics_ps_enhanced_measurement->replaceCatalogViewCheckoutCheckoutBefore($args);

        $template = $this->replaceViews($route, $template, $views);
    }

    public function eventCatalogViewAccountSuccessBefore(string &$route, array &$args, string &$template): void
    {
        if (!$this->config->get('analytics_ps_enhanced_measurement_status')) {
            return;
        }

        if (!isset($this->request->get['route'])) {
            return;
        } else if ($this->request->get['route'] !== 'account/success') {
            return;
        }

        $ps_config_track_sign_up = $this->config->get('analytics_ps_enhanced_measurement_track_sign_up') || $this->config->get('analytics_ps_enhanced_measurement_adwords_sign_up_label');
        $ps_config_track_generate_lead = $this->config->get('analytics_ps_enhanced_measurement_track_generate_lead') || $this->config->get('analytics_ps_enhanced_measurement_adwords_lead_label');

        if (
            !$ps_config_track_sign_up &&
            !$ps_config_track_generate_lead
        ) {
            return;
        }


        if ($ps_config_track_sign_up) {
            if (isset($this->session->data['ps_sign_up_event'])) {
                $ps_sign_up = [
                    'method' => 'Website',
                    'user_id' => $this->customer->getId(),
                ];

                unset($this->session->data['ps_sign_up_event']);
            } else {
                $ps_sign_up = null;
            }

            $args['ps_sign_up'] = $ps_sign_up ? json_encode($ps_sign_up, JSON_NUMERIC_CHECK) : null;
        } else {
            $args['ps_sign_up'] = null;
        }


        if ($ps_config_track_generate_lead) {
            if (isset($this->session->data['ps_generate_lead_newsletter_event'])) {
                $ps_generate_lead_newsletter = ['lead_source' => 'newsletter',];

                unset($this->session->data['ps_generate_lead_newsletter_event']);
            } else {
                $ps_generate_lead_newsletter = null;
            }

            $args['ps_generate_lead_newsletter'] = $ps_generate_lead_newsletter ? json_encode($ps_generate_lead_newsletter, JSON_NUMERIC_CHECK) : null;
        } else {
            $args['ps_generate_lead_newsletter'] = null;
        }


        $this->load->model('extension/ps_enhanced_measurement/analytics/ps_enhanced_measurement');


        $args['ps_track_sign_up'] = $ps_config_track_sign_up;
        $args['ps_track_generate_lead'] = $ps_config_track_generate_lead;


        $views = $this->model_extension_ps_enhanced_measurement_analytics_ps_enhanced_measurement->replaceCatalogViewAccountSuccessBefore($args);

        $template = $this->replaceViews($route, $template, $views);
    }

    public function eventCatalogViewCheckoutRegisterBefore(string &$route, array &$args, string &$template): void
    {
        if (!$this->config->get('analytics_ps_enhanced_measurement_status')) {
            return;
        }

        $ps_config_track_sign_up = $this->config->get('analytics_ps_enhanced_measurement_track_sign_up') || $this->config->get('analytics_ps_enhanced_measurement_adwords_sign_up_label');
        $ps_config_track_generate_lead = $this->config->get('analytics_ps_enhanced_measurement_track_generate_lead') || $this->config->get('analytics_ps_enhanced_measurement_adwords_lead_label');

        if (
            !$ps_config_track_sign_up &&
            !$ps_config_track_generate_lead
        ) {
            return;
        }


        $this->load->model('extension/ps_enhanced_measurement/analytics/ps_enhanced_measurement');


        $args['ps_track_sign_up'] = $ps_config_track_sign_up;
        $args['ps_track_generate_lead'] = $ps_config_track_generate_lead;


        $views = $this->model_extension_ps_enhanced_measurement_analytics_ps_enhanced_measurement->replaceCatalogViewCheckoutRegisterBefore($args);

        $template = $this->replaceViews($route, $template, $views);
    }

    public function eventCatalogControllerCheckoutRegisterSaveAfter(string &$route, array &$args, string &$output): void
    {
        if (!$this->config->get('analytics_ps_enhanced_measurement_status')) {
            return;
        }

        $ps_config_track_sign_up = $this->config->get('analytics_ps_enhanced_measurement_track_sign_up') || $this->config->get('analytics_ps_enhanced_measurement_adwords_sign_up_label');
        $ps_config_track_generate_lead = $this->config->get('analytics_ps_enhanced_measurement_track_generate_lead') || $this->config->get('analytics_ps_enhanced_measurement_adwords_lead_label');

        if (
            !$ps_config_track_sign_up &&
            !$ps_config_track_generate_lead
        ) {
            return;
        }

        $json_response = json_decode($this->response->getOutput(), true);

        if (!$json_response || !isset($json_response['success'])) {
            return;
        }


        if ($ps_config_track_sign_up && $this->customer->isLogged()) {
            $json_response['ps_sign_up'] = [
                'method' => 'Website',
                'user_id' => $this->customer->getId(),
            ];
        }

        if ($ps_config_track_generate_lead && isset($this->request->post['newsletter']) && $this->request->post['newsletter'] === '1') {
            $ps_config_currency = $this->config->get('analytics_ps_enhanced_measurement_currency');

            if (empty($ps_config_currency)) {
                $ps_config_currency = $this->session->data['currency'];
            }

            $json_response['ps_generate_lead_newsletter'] = [
                'currency' => $ps_config_currency,
                'value' => $this->currency->format($this->cart->getTotal(), $ps_config_currency, 0, false),
                'lead_source' => 'newsletter',
            ];
        }


        $this->response->setOutput(json_encode($json_response, JSON_NUMERIC_CHECK));
    }

    public function eventCatalogControllerAccountRegisterRegisterAfter(string &$route, array &$args, string &$output): void
    {
        if (!$this->config->get('analytics_ps_enhanced_measurement_status')) {
            return;
        }

        $ps_config_track_sign_up = $this->config->get('analytics_ps_enhanced_measurement_track_sign_up') || $this->config->get('analytics_ps_enhanced_measurement_adwords_sign_up_label');
        $ps_config_track_generate_lead = $this->config->get('analytics_ps_enhanced_measurement_track_generate_lead') || $this->config->get('analytics_ps_enhanced_measurement_adwords_lead_label');

        if (
            !$ps_config_track_sign_up &&
            !$ps_config_track_generate_lead
        ) {
            return;
        }

        $json_response = json_decode($this->response->getOutput(), true);

        if (!$json_response || !isset($json_response['redirect'])) {
            return;
        }


        if ($ps_config_track_sign_up && $this->customer->isLogged()) {
            $this->session->data['ps_sign_up_event'] = 1;
        }

        if ($ps_config_track_generate_lead && isset($this->request->post['newsletter']) && $this->request->post['newsletter'] === '1') {
            $this->session->data['ps_generate_lead_newsletter_event'] = 1;
        }


        $this->response->setOutput(json_encode($json_response, JSON_NUMERIC_CHECK));
    }

    public function eventCatalogControllerCheckoutSuccessBefore(string &$route, array &$args): void
    {
        if (!$this->config->get('analytics_ps_enhanced_measurement_status')) {
            return;
        }

        $ps_config_track_purchase = $this->config->get('analytics_ps_enhanced_measurement_track_purchase') || $this->config->get('analytics_ps_enhanced_measurement_adwords_purchase_label');

        if (!$ps_config_track_purchase) {
            return;
        }

        if (!isset($this->session->data['order_id'])) {
            return;
        }


        $this->load->language('extension/ps_enhanced_measurement/analytics/ps_enhanced_measurement');

        $this->load->model('extension/ps_enhanced_measurement/analytics/ps_enhanced_measurement');
        $this->load->model('catalog/category');
        $this->load->model('catalog/manufacturer');
        $this->load->model('catalog/product');
        $this->load->model('checkout/cart');


        $ps_config_item_category_option = (int) $this->config->get('analytics_ps_enhanced_measurement_item_category_option');
        $ps_config_item_price_tax = $this->config->get('analytics_ps_enhanced_measurement_item_price_tax');
        $ps_config_location_id = $this->config->get('analytics_ps_enhanced_measurement_location_id');
        $ps_config_item_id_option = $this->config->get('analytics_ps_enhanced_measurement_item_id');
        $ps_config_affiliation = $this->config->get('analytics_ps_enhanced_measurement_affiliation');

        $ps_config_currency = $this->config->get('analytics_ps_enhanced_measurement_currency');

        if (empty($ps_config_currency)) {
            $ps_config_currency = $this->session->data['currency'];
        }

        if (isset($this->session->data['coupon'])) {
            $ps_product_coupon = $this->session->data['coupon'];
        } else if (isset($this->session->data['voucher'])) {
            $ps_product_coupon = $this->session->data['voucher'];
        } else {
            $ps_product_coupon = null;
        }


        $ps_item_list_name = $this->language->get('text_purchased_products');
        $ps_item_list_id = $this->formatListId($ps_item_list_name);



        $products = $this->model_checkout_cart->getProducts();

        $ps_items = [];
        $ps_total_price = 0;

        foreach ($products as $index => $product_info) {
            $ps_original_product_info = $this->model_catalog_product->getProduct((int) $product_info['product_id']);

            $ps_item = [];

            $ps_item['item_id'] = isset($ps_original_product_info[$ps_config_item_id_option]) && !empty($ps_original_product_info[$ps_config_item_id_option]) ? $this->formatListId($ps_original_product_info[$ps_config_item_id_option]) : $ps_original_product_info['product_id'];
            $ps_item['item_name'] = html_entity_decode($product_info['name'], ENT_QUOTES, 'UTF-8');

            if ($ps_config_affiliation) {
                $ps_item['affiliation'] = $ps_config_affiliation;
            }

            if ($ps_product_coupon) {
                $ps_item['coupon'] = $ps_product_coupon;
            }

            if ((float) $ps_original_product_info['special']) {
                $ps_discount = $this->tax->calculate($ps_original_product_info['price'], $ps_original_product_info['tax_class_id'], $ps_config_item_price_tax) - $this->tax->calculate($ps_original_product_info['special'], $ps_original_product_info['tax_class_id'], $ps_config_item_price_tax);

                $ps_item['discount'] = $this->currency->format($ps_discount, $ps_config_currency, 0, false);
            }

            $ps_item['index'] = $index;

            $ps_manufacturer_info = $this->model_extension_ps_enhanced_measurement_analytics_ps_enhanced_measurement->getManufacturerNameByProductId($product_info['product_id']);

            if ($ps_manufacturer_info) {
                $ps_item['item_brand'] = $ps_manufacturer_info['name'];
            }

            switch ($ps_config_item_category_option) {
                case 0:
                    $ps_product_categories = $this->getCategoryType1($product_info['product_id']);
                    break;
                case 1:
                    $ps_product_categories = $this->getCategoryType2($product_info['product_id']);
                    break;
                default:
                    $ps_product_categories = $this->getCategoryType1($product_info['product_id']);
                    break;
            }

            foreach ($ps_product_categories as $category_index => $category_name) {
                if ($category_index === 0) {
                    $ps_item['item_category'] = $category_name;
                } else {
                    $ps_item['item_category' . ($category_index + 1)] = $category_name;
                }
            }

            $ps_item['item_list_id'] = $ps_item_list_id;
            $ps_item['item_list_name'] = $ps_item_list_name;

            $ps_variants = [];

            foreach ($product_info['option'] as $option) {
                $ps_variants[] = html_entity_decode($option['name'] . ': ' . $option['value'], ENT_QUOTES, 'UTF-8');
            }

            if ($product_info['subscription'] && $ps_subscription_description = $this->getProductSubscriptionDescription($product_info['subscription'], $product_info['tax_class_id'], $ps_config_currency, $ps_config_item_price_tax)) {
                $ps_variants[] = $ps_subscription_description;
            }

            if ($ps_variants) {
                $ps_item['item_variant'] = implode(', ', $ps_variants);
            }

            if ($ps_config_location_id) {
                $ps_item['location_id'] = $ps_config_location_id;
            }

            $ps_price = $this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $ps_config_item_price_tax);

            $ps_total_price += $this->tax->calculate($product_info['total'], $product_info['tax_class_id'], $ps_config_item_price_tax);

            $ps_item['price'] = $this->currency->format($ps_price, $ps_config_currency, 0, false);

            $ps_item['quantity'] = $product_info['quantity'];

            $ps_items[(int) $product_info['cart_id']] = $ps_item;

            $this->session->data['ps_item_list_info'][(int) $product_info['product_id']] = [
                'item_list_id' => $ps_item_list_id,
                'item_list_name' => $ps_item_list_name,
            ];
        }


        #region Get totals
        $ps_totals = [];
        $ps_taxes = $this->cart->getTaxes();
        $ps_total = 0;
        $ps_purchase_data = [
            'tax' => 0,
            'shipping' => 0,
            'total' => 0,
        ];

        ($this->model_checkout_cart->getTotals)($ps_totals, $ps_taxes, $ps_total);

        foreach ($ps_totals as $value) {
            if ($value['code'] === 'shipping') {
                $ps_purchase_data['shipping'] = $value['value'];
            } else if ($value['code'] === 'tax') {
                $ps_purchase_data['tax'] = $value['value'];
            } else if ($value['code'] === 'total') {
                $ps_purchase_data['total'] = $value['value'];
            }
        }
        #endregion

        $ps_purchase = [
            'ecommerce' => [
                'transaction_id' => $this->session->data['order_id'],
                'value' => $this->currency->format($ps_purchase_data['total'] - $ps_purchase_data['shipping'] - $ps_purchase_data['tax'], $ps_config_currency, 0, false),
                'tax' => $this->currency->format(($ps_config_item_price_tax ? $ps_purchase_data['tax'] : 0), $ps_config_currency, 0, false),
                'shipping' => $this->currency->format($ps_purchase_data['shipping'], $ps_config_currency, 0, false),
                'currency' => $ps_config_currency,
                'coupon' => $ps_product_coupon ? $ps_product_coupon : '',
                'items' => array_values($ps_items),
            ],
        ];

        $this->session->data['ps_purchase'] = $ps_items ? json_encode($ps_purchase, JSON_NUMERIC_CHECK) : null;


        if (isset($this->request->cookie['_ga'])) {
            $ps_ga_cookie = $this->request->cookie['_ga'];

            $ps_parts = explode('.', $ps_ga_cookie);

            if (count($ps_parts) >= 4) {
                $this->model_extension_ps_enhanced_measurement_analytics_ps_enhanced_measurement->saveGA4ClientId($this->session->data['order_id'], $ps_parts[2] . '.' . $ps_parts[3]);
            }
        }
    }

    public function eventCatalogViewCheckoutSuccessBefore(string &$route, array &$args, string &$template): void
    {
        if (!$this->config->get('analytics_ps_enhanced_measurement_status')) {
            return;
        }


        $ps_config_track_purchase = $this->config->get('analytics_ps_enhanced_measurement_track_purchase') || $this->config->get('analytics_ps_enhanced_measurement_adwords_purchase_label');

        if (!$ps_config_track_purchase) {
            return;
        }

        if (!isset($this->request->get['route'])) {
            return;
        } else if ($this->request->get['route'] !== 'checkout/success') {
            return;
        }


        $args['ps_track_purchase'] = $ps_config_track_purchase;

        $args['ps_purchase'] = isset($this->session->data['ps_purchase']) ? $this->session->data['ps_purchase'] : null;

        unset($this->session->data['ps_purchase']);


        $this->load->model('extension/ps_enhanced_measurement/analytics/ps_enhanced_measurement');

        $views = $this->model_extension_ps_enhanced_measurement_analytics_ps_enhanced_measurement->replaceCatalogViewCheckoutSuccessBefore($args);

        $template = $this->replaceViews($route, $template, $views);
    }

    public function eventCatalogViewCheckoutCartBefore(string &$route, array &$args, string &$template): void
    {
        if (!$this->config->get('analytics_ps_enhanced_measurement_status')) {
            return;
        }


        $ps_config_track_view_cart = $this->config->get('analytics_ps_enhanced_measurement_track_view_cart');

        if (!$ps_config_track_view_cart) {
            return;
        }


        $this->load->language('extension/ps_enhanced_measurement/analytics/ps_enhanced_measurement');

        $this->load->model('extension/ps_enhanced_measurement/analytics/ps_enhanced_measurement');
        $this->load->model('catalog/category');
        $this->load->model('catalog/manufacturer');
        $this->load->model('catalog/product');
        $this->load->model('checkout/cart');


        $ps_config_item_category_option = (int) $this->config->get('analytics_ps_enhanced_measurement_item_category_option');
        $ps_config_item_price_tax = $this->config->get('analytics_ps_enhanced_measurement_item_price_tax');
        $ps_config_location_id = $this->config->get('analytics_ps_enhanced_measurement_location_id');
        $ps_config_item_id_option = $this->config->get('analytics_ps_enhanced_measurement_item_id');
        $ps_config_affiliation = $this->config->get('analytics_ps_enhanced_measurement_affiliation');

        $ps_config_currency = $this->config->get('analytics_ps_enhanced_measurement_currency');

        if (empty($ps_config_currency)) {
            $ps_config_currency = $this->session->data['currency'];
        }

        if (isset($this->session->data['coupon'])) {
            $ps_product_coupon = $this->session->data['coupon'];
        } else if (isset($this->session->data['voucher'])) {
            $ps_product_coupon = $this->session->data['voucher'];
        } else {
            $ps_product_coupon = null;
        }


        $ps_item_list_name = $this->language->get('text_cart_products');
        $ps_item_list_id = $this->formatListId($ps_item_list_name);


        $products = $this->model_checkout_cart->getProducts();

        $ps_items = [];

        foreach ($products as $index => $product_info) {
            $ps_original_product_info = $this->model_catalog_product->getProduct((int) $product_info['product_id']);

            $ps_item = [];

            $ps_item['item_id'] = isset($ps_original_product_info[$ps_config_item_id_option]) && !empty($ps_original_product_info[$ps_config_item_id_option]) ? $this->formatListId($ps_original_product_info[$ps_config_item_id_option]) : $ps_original_product_info['product_id'];
            $ps_item['item_name'] = html_entity_decode($product_info['name'], ENT_QUOTES, 'UTF-8');

            if ($ps_config_affiliation) {
                $ps_item['affiliation'] = $ps_config_affiliation;
            }

            if ($ps_product_coupon) {
                $ps_item['coupon'] = $ps_product_coupon;
            }

            if ((float) $ps_original_product_info['special']) {
                $ps_discount = $this->tax->calculate($ps_original_product_info['price'], $ps_original_product_info['tax_class_id'], $ps_config_item_price_tax) - $this->tax->calculate($ps_original_product_info['special'], $ps_original_product_info['tax_class_id'], $ps_config_item_price_tax);

                $ps_item['discount'] = $this->currency->format($ps_discount, $ps_config_currency, 0, false);
            }

            $ps_item['index'] = $index;

            $ps_manufacturer_info = $this->model_extension_ps_enhanced_measurement_analytics_ps_enhanced_measurement->getManufacturerNameByProductId($product_info['product_id']);

            if ($ps_manufacturer_info) {
                $ps_item['item_brand'] = $ps_manufacturer_info['name'];
            }

            switch ($ps_config_item_category_option) {
                case 0:
                    $ps_product_categories = $this->getCategoryType1($product_info['product_id']);
                    break;
                case 1:
                    $ps_product_categories = $this->getCategoryType2($product_info['product_id']);
                    break;
                default:
                    $ps_product_categories = $this->getCategoryType1($product_info['product_id']);
                    break;
            }

            foreach ($ps_product_categories as $category_index => $category_name) {
                if ($category_index === 0) {
                    $ps_item['item_category'] = $category_name;
                } else {
                    $ps_item['item_category' . ($category_index + 1)] = $category_name;
                }
            }

            $ps_item['item_list_id'] = $ps_item_list_id;
            $ps_item['item_list_name'] = $ps_item_list_name;

            $ps_variants = [];

            foreach ($product_info['option'] as $option) {
                $ps_variants[] = html_entity_decode($option['name'] . ': ' . $option['value'], ENT_QUOTES, 'UTF-8');
            }

            if ($product_info['subscription'] && $ps_subscription_description = $this->getProductSubscriptionDescription($product_info['subscription'], $product_info['tax_class_id'], $ps_config_currency, $ps_config_item_price_tax)) {
                $ps_variants[] = $ps_subscription_description;
            }

            if ($ps_variants) {
                $ps_item['item_variant'] = implode(', ', $ps_variants);
            }

            if ($ps_config_location_id) {
                $ps_item['location_id'] = $ps_config_location_id;
            }

            $ps_price = $this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $ps_config_item_price_tax);

            $ps_item['price'] = $this->currency->format($ps_price, $ps_config_currency, 0, false);

            $ps_item['quantity'] = $product_info['quantity'];

            $ps_items[(int) $product_info['cart_id']] = $ps_item;

            $this->session->data['ps_item_list_info'][(int) $product_info['product_id']] = [
                'item_list_id' => $ps_item_list_id,
                'item_list_name' => $ps_item_list_name,
            ];
        }


        $ps_view_cart = [
            'ecommerce' => [
                'currency' => $ps_config_currency,
                'value' => $this->currency->format($this->cart->getTotal(), $ps_config_currency, 0, false),
                'items' => array_values($ps_items),
            ],
        ];

        $args['ps_view_cart'] = $ps_items ? json_encode($ps_view_cart, JSON_NUMERIC_CHECK) : null;


        $args['ps_track_view_cart'] = $ps_config_track_view_cart;


        $views = $this->model_extension_ps_enhanced_measurement_analytics_ps_enhanced_measurement->replaceCatalogViewCheckoutCartBefore($args);

        $template = $this->replaceViews($route, $template, $views);
    }

    public function eventCatalogViewCheckoutCartListBefore(string &$route, array &$args, string &$template): void
    {
        if (!$this->config->get('analytics_ps_enhanced_measurement_status')) {
            return;
        }


        $ps_config_track_add_to_cart = $this->config->get('analytics_ps_enhanced_measurement_track_add_to_cart') || $this->config->get('analytics_ps_enhanced_measurement_adwords_add_to_cart_label');
        $ps_config_track_remove_from_cart = $this->config->get('analytics_ps_enhanced_measurement_track_remove_from_cart');
        $ps_config_track_select_item = $this->config->get('analytics_ps_enhanced_measurement_track_select_item');
        $ps_config_track_select_promotion = $this->config->get('analytics_ps_enhanced_measurement_track_select_promotion');

        if (
            !$ps_config_track_add_to_cart &&
            !$ps_config_track_remove_from_cart &&
            !$ps_config_track_select_item &&
            !$ps_config_track_select_promotion
        ) {
            return;
        }


        $this->load->language('extension/ps_enhanced_measurement/analytics/ps_enhanced_measurement');

        $this->load->model('extension/ps_enhanced_measurement/analytics/ps_enhanced_measurement');
        $this->load->model('catalog/category');
        $this->load->model('catalog/manufacturer');
        $this->load->model('catalog/product');
        $this->load->model('checkout/cart');

        $ps_config_item_category_option = (int) $this->config->get('analytics_ps_enhanced_measurement_item_category_option');
        $ps_config_item_price_tax = $this->config->get('analytics_ps_enhanced_measurement_item_price_tax');
        $ps_config_location_id = $this->config->get('analytics_ps_enhanced_measurement_location_id');
        $ps_config_item_id_option = $this->config->get('analytics_ps_enhanced_measurement_item_id');
        $ps_config_affiliation = $this->config->get('analytics_ps_enhanced_measurement_affiliation');

        $ps_config_currency = $this->config->get('analytics_ps_enhanced_measurement_currency');

        if (empty($ps_config_currency)) {
            $ps_config_currency = $this->session->data['currency'];
        }

        if (isset($this->session->data['coupon'])) {
            $ps_product_coupon = $this->session->data['coupon'];
        } else if (isset($this->session->data['voucher'])) {
            $ps_product_coupon = $this->session->data['voucher'];
        } else {
            $ps_product_coupon = null;
        }


        $ps_item_list_name = $this->language->get('text_cart_products');
        $ps_item_list_id = $this->formatListId($ps_item_list_name);


        $products = $this->model_checkout_cart->getProducts();

        $ps_items = [];
        $ps_quantity_minimums = [];
        $ps_specials = [];
        $ps_total_prices = [];

        foreach ($products as $index => $product_info) {
            $ps_original_product_info = $this->model_catalog_product->getProduct((int) $product_info['product_id']);

            $ps_item = [];

            $ps_item['item_id'] = isset($ps_original_product_info[$ps_config_item_id_option]) && !empty($ps_original_product_info[$ps_config_item_id_option]) ? $this->formatListId($ps_original_product_info[$ps_config_item_id_option]) : $ps_original_product_info['product_id'];
            $ps_item['item_name'] = html_entity_decode($product_info['name'], ENT_QUOTES, 'UTF-8');

            if ($ps_config_affiliation) {
                $ps_item['affiliation'] = $ps_config_affiliation;
            }

            if ($ps_product_coupon) {
                $ps_item['coupon'] = $ps_product_coupon;
            }

            if ((float) $ps_original_product_info['special']) {
                $ps_discount = $this->tax->calculate($ps_original_product_info['price'], $ps_original_product_info['tax_class_id'], $ps_config_item_price_tax) - $this->tax->calculate($ps_original_product_info['special'], $ps_original_product_info['tax_class_id'], $ps_config_item_price_tax);

                $ps_item['discount'] = $this->currency->format($ps_discount, $ps_config_currency, 0, false);
            }

            $ps_item['index'] = $index;

            $ps_manufacturer_info = $this->model_extension_ps_enhanced_measurement_analytics_ps_enhanced_measurement->getManufacturerNameByProductId($product_info['product_id']);

            if ($ps_manufacturer_info) {
                $ps_item['item_brand'] = $ps_manufacturer_info['name'];
            }

            switch ($ps_config_item_category_option) {
                case 0:
                    $ps_product_categories = $this->getCategoryType1($product_info['product_id']);
                    break;
                case 1:
                    $ps_product_categories = $this->getCategoryType2($product_info['product_id']);
                    break;
                default:
                    $ps_product_categories = $this->getCategoryType1($product_info['product_id']);
                    break;
            }

            foreach ($ps_product_categories as $category_index => $category_name) {
                if ($category_index === 0) {
                    $ps_item['item_category'] = $category_name;
                } else {
                    $ps_item['item_category' . ($category_index + 1)] = $category_name;
                }
            }

            $ps_item['item_list_id'] = $ps_item_list_id;
            $ps_item['item_list_name'] = $ps_item_list_name;

            $ps_variants = [];

            foreach ($product_info['option'] as $option) {
                $ps_variants[] = html_entity_decode($option['name'] . ': ' . $option['value'], ENT_QUOTES, 'UTF-8');
            }

            if ($product_info['subscription'] && $ps_subscription_description = $this->getProductSubscriptionDescription($product_info['subscription'], $product_info['tax_class_id'], $ps_config_currency, $ps_config_item_price_tax)) {
                $ps_variants[] = $ps_subscription_description;
            }

            if ($ps_variants) {
                $ps_item['item_variant'] = implode(', ', $ps_variants);
            }

            if ($ps_config_location_id) {
                $ps_item['location_id'] = $ps_config_location_id;
            }

            $ps_price = $this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $ps_config_item_price_tax);
            $ps_total_price = $this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $ps_config_item_price_tax);

            $ps_item['price'] = $this->currency->format($ps_price, $ps_config_currency, 0, false);

            $ps_total_prices[(int) $product_info['cart_id']] = $this->currency->format($ps_total_price * $product_info['quantity'], $ps_config_currency, 0, false);

            $ps_item['quantity'] = $product_info['quantity'];

            if ($product_info['minimum']) {
                $ps_quantity_minimums[(int) $product_info['cart_id']] = $product_info['minimum'];
            } else {
                $ps_quantity_minimums[(int) $product_info['cart_id']] = 1;
            }

            $ps_specials[(int) $product_info['cart_id']] = (float) $ps_original_product_info['special'] > 0;

            if (isset($args['products'][$index])) {
                $args['products'][$index]['special'] = $ps_original_product_info['special'];
            }

            $ps_items[(int) $product_info['cart_id']] = $ps_item;
        }


        $ps_merge_items = [];

        foreach ($ps_items as $cart_id => $ps_item) {
            if ($ps_specials[$cart_id]) {
                if ($ps_config_track_select_promotion) {
                    $ps_merge_items['select_promotion_' . $cart_id] = [
                        'ecommerce' => [
                            'item_list_id' => $ps_item_list_id,
                            'item_list_name' => $ps_item_list_name,
                            'items' => [$ps_item],
                        ],
                    ];
                }
            } else {
                if ($ps_config_track_select_item) {
                    $ps_merge_items['select_item_' . $cart_id] = [
                        'ecommerce' => [
                            'item_list_id' => $ps_item_list_id,
                            'item_list_name' => $ps_item_list_name,
                            'items' => [$ps_item],
                        ],
                    ];
                }
            }

            if ($ps_config_track_remove_from_cart) {
                $ps_merge_items['remove_from_cart_' . $cart_id] = [
                    'ecommerce' => [
                        'currency' => $ps_config_currency,
                        'value' => $ps_total_prices[$cart_id],
                        'items' => [$ps_item],
                    ],
                ];
            }
        }

        if ($ps_config_track_add_to_cart) {
            foreach ($ps_items as $cart_id => $ps_item) {
                $ps_item['quantity'] = $ps_quantity_minimums[$cart_id];

                $ps_merge_items['add_to_cart_' . $cart_id] = [
                    'ecommerce' => [
                        'currency' => $ps_config_currency,
                        'value' => $ps_item['price'],
                        'items' => [$ps_item],
                    ],
                ];
            }
        }

        $args['ps_merge_items'] = $ps_merge_items ? json_encode($ps_merge_items, JSON_NUMERIC_CHECK) : null;


        $args['ps_track_add_to_cart'] = $ps_config_track_add_to_cart;
        $args['ps_track_remove_from_cart'] = $ps_config_track_remove_from_cart;
        $args['ps_track_select_item'] = $ps_config_track_select_item;
        $args['ps_track_select_promotion'] = $ps_config_track_select_promotion;


        $views = $this->model_extension_ps_enhanced_measurement_analytics_ps_enhanced_measurement->replaceCatalogViewCheckoutCartListBefore($args);

        $template = $this->replaceViews($route, $template, $views);
    }

    public function eventCatalogViewCheckoutCartInfoBefore(string &$route, array &$args, string &$template): void
    {
        if (!$this->config->get('analytics_ps_enhanced_measurement_status')) {
            return;
        }


        $ps_config_track_select_item = $this->config->get('analytics_ps_enhanced_measurement_track_select_item');
        $ps_config_track_select_promotion = $this->config->get('analytics_ps_enhanced_measurement_track_select_promotion');
        $ps_config_track_remove_from_cart = $this->config->get('analytics_ps_enhanced_measurement_track_remove_from_cart');

        if (
            !$ps_config_track_select_item &&
            !$ps_config_track_select_promotion &&
            !$ps_config_track_remove_from_cart
        ) {
            return;
        }


        $this->load->language('extension/ps_enhanced_measurement/analytics/ps_enhanced_measurement');

        $this->load->model('extension/ps_enhanced_measurement/analytics/ps_enhanced_measurement');
        $this->load->model('catalog/category');
        $this->load->model('catalog/manufacturer');
        $this->load->model('catalog/product');
        $this->load->model('checkout/cart');


        $ps_config_item_category_option = (int) $this->config->get('analytics_ps_enhanced_measurement_item_category_option');
        $ps_config_item_price_tax = $this->config->get('analytics_ps_enhanced_measurement_item_price_tax');
        $ps_config_location_id = $this->config->get('analytics_ps_enhanced_measurement_location_id');
        $ps_config_item_id_option = $this->config->get('analytics_ps_enhanced_measurement_item_id');
        $ps_config_affiliation = $this->config->get('analytics_ps_enhanced_measurement_affiliation');

        $ps_config_currency = $this->config->get('analytics_ps_enhanced_measurement_currency');

        if (empty($ps_config_currency)) {
            $ps_config_currency = $this->session->data['currency'];
        }

        if (isset($this->session->data['coupon'])) {
            $ps_product_coupon = $this->session->data['coupon'];
        } else if (isset($this->session->data['voucher'])) {
            $ps_product_coupon = $this->session->data['voucher'];
        } else {
            $ps_product_coupon = null;
        }


        $ps_item_list_name = $this->language->get('text_cart_products');
        $ps_item_list_id = $this->formatListId($ps_item_list_name);


        $products = $this->model_checkout_cart->getProducts();

        $ps_items = [];
        $ps_specials = [];
        $ps_total_prices = [];

        foreach ($products as $index => $product_info) {
            $ps_original_product_info = $this->model_catalog_product->getProduct((int) $product_info['product_id']);

            $ps_item = [];

            $ps_item['item_id'] = isset($ps_original_product_info[$ps_config_item_id_option]) && !empty($ps_original_product_info[$ps_config_item_id_option]) ? $this->formatListId($ps_original_product_info[$ps_config_item_id_option]) : $ps_original_product_info['product_id'];
            $ps_item['item_name'] = html_entity_decode($product_info['name'], ENT_QUOTES, 'UTF-8');

            if ($ps_config_affiliation) {
                $ps_item['affiliation'] = $ps_config_affiliation;
            }

            if ($ps_product_coupon) {
                $ps_item['coupon'] = $ps_product_coupon;
            }

            if ((float) $ps_original_product_info['special']) {
                $ps_discount = $this->tax->calculate($ps_original_product_info['price'], $ps_original_product_info['tax_class_id'], $ps_config_item_price_tax) - $this->tax->calculate($ps_original_product_info['special'], $ps_original_product_info['tax_class_id'], $ps_config_item_price_tax);

                $ps_item['discount'] = $this->currency->format($ps_discount, $ps_config_currency, 0, false);
            }

            $ps_item['index'] = $index;

            $ps_manufacturer_info = $this->model_extension_ps_enhanced_measurement_analytics_ps_enhanced_measurement->getManufacturerNameByProductId($product_info['product_id']);

            if ($ps_manufacturer_info) {
                $ps_item['item_brand'] = $ps_manufacturer_info['name'];
            }

            switch ($ps_config_item_category_option) {
                case 0:
                    $ps_product_categories = $this->getCategoryType1($product_info['product_id']);
                    break;
                case 1:
                    $ps_product_categories = $this->getCategoryType2($product_info['product_id']);
                    break;
                default:
                    $ps_product_categories = $this->getCategoryType1($product_info['product_id']);
                    break;
            }

            foreach ($ps_product_categories as $category_index => $category_name) {
                if ($category_index === 0) {
                    $ps_item['item_category'] = $category_name;
                } else {
                    $ps_item['item_category' . ($category_index + 1)] = $category_name;
                }
            }

            $ps_item['item_list_id'] = $ps_item_list_id;
            $ps_item['item_list_name'] = $ps_item_list_name;

            $ps_variants = [];

            foreach ($product_info['option'] as $option) {
                $ps_variants[] = html_entity_decode($option['name'] . ': ' . $option['value'], ENT_QUOTES, 'UTF-8');
            }

            if ($product_info['subscription'] && $ps_subscription_description = $this->getProductSubscriptionDescription($product_info['subscription'], $product_info['tax_class_id'], $ps_config_currency, $ps_config_item_price_tax)) {
                $ps_variants[] = $ps_subscription_description;
            }

            if ($ps_variants) {
                $ps_item['item_variant'] = implode(', ', $ps_variants);
            }

            if ($ps_config_location_id) {
                $ps_item['location_id'] = $ps_config_location_id;
            }

            $ps_price = $this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $ps_config_item_price_tax);
            $ps_total_price = $this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $ps_config_item_price_tax);

            $ps_item['price'] = $this->currency->format($ps_price, $ps_config_currency, 0, false);

            $ps_total_prices[(int) $product_info['cart_id']] = $this->currency->format($ps_total_price * $product_info['quantity'], $ps_config_currency, 0, false);

            $ps_item['quantity'] = $product_info['quantity'];

            $ps_specials[(int) $product_info['cart_id']] = (float) $ps_original_product_info['special'] > 0;

            if (isset($args['products'][$index])) {
                $args['products'][$index]['special'] = $ps_original_product_info['special'];
            }

            $ps_items[(int) $product_info['cart_id']] = $ps_item;
        }


        $ps_merge_items = [];

        foreach ($ps_items as $cart_id => $ps_item) {
            if ($ps_specials[$cart_id]) {
                if ($ps_config_track_select_promotion) {
                    $ps_merge_items['select_promotion_' . $cart_id] = [
                        'ecommerce' => [
                            'item_list_id' => $ps_item_list_id,
                            'item_list_name' => $ps_item_list_name,
                            'items' => [$ps_item],
                        ],
                    ];
                }
            } else {
                if ($ps_config_track_select_item) {
                    $ps_merge_items['select_item_' . $cart_id] = [
                        'ecommerce' => [
                            'item_list_id' => $ps_item_list_id,
                            'item_list_name' => $ps_item_list_name,
                            'items' => [$ps_item],
                        ],
                    ];
                }
            }

            if ($ps_config_track_remove_from_cart) {
                $ps_merge_items['remove_from_cart_' . $cart_id] = [
                    'ecommerce' => [
                        'currency' => $ps_config_currency,
                        'value' => $ps_total_prices[$cart_id],
                        'items' => [$ps_item],
                    ],
                ];
            }
        }

        $args['ps_merge_items'] = $ps_merge_items ? json_encode($ps_merge_items, JSON_NUMERIC_CHECK) : null;


        $args['ps_track_select_item'] = $ps_config_track_select_item;
        $args['ps_track_select_promotion'] = $ps_config_track_select_promotion;
        $args['ps_track_remove_from_cart'] = $ps_config_track_remove_from_cart;


        $views = $this->model_extension_ps_enhanced_measurement_analytics_ps_enhanced_measurement->replaceCatalogViewCheckoutCartInfoBefore($args);

        $template = $this->replaceViews($route, $template, $views);
    }

    public function eventCatalogViewExtensionOpencartModuleBestsellerAfter(string &$route, array &$args, string &$output): void
    {
        if (!$this->config->get('analytics_ps_enhanced_measurement_status')) {
            return;
        }


        $ps_config_track_add_to_wishlist = $this->config->get('analytics_ps_enhanced_measurement_track_add_to_wishlist');
        $ps_config_track_add_to_cart = $this->config->get('analytics_ps_enhanced_measurement_track_add_to_cart') || $this->config->get('analytics_ps_enhanced_measurement_adwords_add_to_cart_label');
        $ps_config_track_select_item = $this->config->get('analytics_ps_enhanced_measurement_track_select_item');
        $ps_config_track_select_promotion = $this->config->get('analytics_ps_enhanced_measurement_track_select_promotion');

        if (
            !$ps_config_track_add_to_wishlist &&
            !$ps_config_track_add_to_cart &&
            !$ps_config_track_select_item &&
            !$ps_config_track_select_promotion
        ) {
            return;
        }


        $this->load->language('extension/ps_enhanced_measurement/analytics/ps_enhanced_measurement');

        $this->load->model('extension/opencart/module/bestseller');
        $this->load->model('catalog/manufacturer');

        $ps_config_item_category_option = (int) $this->config->get('analytics_ps_enhanced_measurement_item_category_option');
        $ps_config_item_price_tax = $this->config->get('analytics_ps_enhanced_measurement_item_price_tax');
        $ps_config_location_id = $this->config->get('analytics_ps_enhanced_measurement_location_id');
        $ps_config_item_id_option = $this->config->get('analytics_ps_enhanced_measurement_item_id');
        $ps_config_affiliation = $this->config->get('analytics_ps_enhanced_measurement_affiliation');

        $ps_config_currency = $this->config->get('analytics_ps_enhanced_measurement_currency');

        if (empty($ps_config_currency)) {
            $ps_config_currency = $this->session->data['currency'];
        }

        if (isset($this->session->data['coupon'])) {
            $ps_product_coupon = $this->session->data['coupon'];
        } else if (isset($this->session->data['voucher'])) {
            $ps_product_coupon = $this->session->data['voucher'];
        } else {
            $ps_product_coupon = null;
        }


        $setting = isset($args[0]) ? $args[0] : ['limit' => 0, 'name' => ''];

        $products = $this->model_extension_opencart_module_bestseller->getBestSellers($setting['limit']);

        if ($products) {
            $ps_item_list_name = sprintf(
                $this->language->get('text_x_products'),
                html_entity_decode($setting['name'], ENT_QUOTES, 'UTF-8')
            );
            $ps_item_list_id = $this->formatListId($ps_item_list_name);

            $ps_items = [];
            $ps_quantity_minimums = [];
            $ps_specials = [];

            foreach ($products as $index => $product_info) {
                $ps_item = [];

                $ps_item['item_id'] = isset($product_info[$ps_config_item_id_option]) && !empty($product_info[$ps_config_item_id_option]) ? $this->formatListId($product_info[$ps_config_item_id_option]) : $product_info['product_id'];
                $ps_item['item_name'] = html_entity_decode($product_info['name'], ENT_QUOTES, 'UTF-8');

                if ($ps_config_affiliation) {
                    $ps_item['affiliation'] = $ps_config_affiliation;
                }

                if ($ps_product_coupon) {
                    $ps_item['coupon'] = $ps_product_coupon;
                }

                if ((float) $product_info['special']) {
                    $ps_discount = $this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $ps_config_item_price_tax) - $this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $ps_config_item_price_tax);

                    $ps_item['discount'] = $this->currency->format($ps_discount, $ps_config_currency, 0, false);
                }

                $ps_item['index'] = $index;

                $ps_manufacturer_info = $this->model_catalog_manufacturer->getManufacturer((int) $product_info['manufacturer_id']);

                if ($ps_manufacturer_info) {
                    $ps_item['item_brand'] = $ps_manufacturer_info['name'];
                }

                switch ($ps_config_item_category_option) {
                    case 0:
                        $ps_product_categories = $this->getCategoryType1($product_info['product_id']);
                        break;
                    case 1:
                        $ps_product_categories = $this->getCategoryType2($product_info['product_id']);
                        break;
                    default:
                        $ps_product_categories = $this->getCategoryType1($product_info['product_id']);
                        break;
                }

                foreach ($ps_product_categories as $category_index => $category_name) {
                    if ($category_index === 0) {
                        $ps_item['item_category'] = $category_name;
                    } else {
                        $ps_item['item_category' . ($category_index + 1)] = $category_name;
                    }
                }

                $ps_item['item_list_id'] = $ps_item_list_id;
                $ps_item['item_list_name'] = $ps_item_list_name;

                if ($ps_config_location_id) {
                    $ps_item['location_id'] = $ps_config_location_id;
                }

                if ((float) $product_info['special']) {
                    $ps_price = $this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $ps_config_item_price_tax);
                } else {
                    $ps_price = $this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $ps_config_item_price_tax);
                }

                $ps_item['price'] = $this->currency->format($ps_price, $ps_config_currency, 0, false);

                $ps_item['quantity'] = $product_info['quantity'];

                if ($product_info['minimum']) {
                    $ps_quantity_minimums[(int) $product_info['product_id']] = $product_info['minimum'];
                } else {
                    $ps_quantity_minimums[(int) $product_info['product_id']] = 1;
                }

                $ps_specials[(int) $product_info['product_id']] = (float) $product_info['special'] > 0;

                $ps_items[(int) $product_info['product_id']] = $ps_item;

                if (!isset($this->session->data['ps_item_list_info'], $this->session->data['ps_item_list_info'][(int) $product_info['product_id']])) {
                    $this->session->data['ps_item_list_info'][(int) $product_info['product_id']] = [
                        'item_list_id' => $ps_item_list_id,
                        'item_list_name' => $ps_item_list_name,
                    ];
                }
            }


            $ps_merge_items = [];

            foreach ($ps_items as $product_id => $ps_item) {
                if ($ps_specials[$product_id]) {
                    if ($ps_config_track_select_promotion) {
                        $ps_merge_items['select_promotion_' . $product_id] = [
                            'ecommerce' => [
                                'item_list_id' => $ps_item_list_id,
                                'item_list_name' => $ps_item_list_name,
                                'items' => [$ps_item],
                            ],
                        ];
                    }
                } else {
                    if ($ps_config_track_select_item) {
                        $ps_merge_items['select_item_' . $product_id] = [
                            'ecommerce' => [
                                'item_list_id' => $ps_item_list_id,
                                'item_list_name' => $ps_item_list_name,
                                'items' => [$ps_item],
                            ],
                        ];
                    }
                }

                if ($ps_config_track_add_to_wishlist) {
                    $ps_merge_items['add_to_wishlist_' . $product_id] = [
                        'ecommerce' => [
                            'currency' => $ps_config_currency,
                            'value' => $ps_item['price'],
                            'items' => [$ps_item],
                        ],
                    ];
                }
            }

            if ($ps_config_track_add_to_cart) {
                foreach ($ps_items as $product_id => $ps_item) {
                    $ps_item['quantity'] = $ps_quantity_minimums[$product_id];

                    $ps_merge_items['add_to_cart_' . $product_id] = [
                        'ecommerce' => [
                            'currency' => $ps_config_currency,
                            'value' => $ps_item['price'] * $ps_quantity_minimums[$product_id],
                            'items' => [$ps_item],
                        ],
                    ];
                }
            }

            if ($ps_items) {
                $output = '<script>ps_dataLayer.setData(' . PHP_EOL . json_encode($ps_merge_items, JSON_NUMERIC_CHECK) . PHP_EOL . ');</>' . PHP_EOL . $output;
            }
        }
    }

    public function eventCatalogViewExtensionOpencartModuleFeaturedAfter(string &$route, array &$args, string &$output): void
    {
        if (!$this->config->get('analytics_ps_enhanced_measurement_status')) {
            return;
        }

        $ps_config_track_add_to_wishlist = $this->config->get('analytics_ps_enhanced_measurement_track_add_to_wishlist');
        $ps_config_track_add_to_cart = $this->config->get('analytics_ps_enhanced_measurement_track_add_to_cart') || $this->config->get('analytics_ps_enhanced_measurement_adwords_add_to_cart_label');
        $ps_config_track_select_item = $this->config->get('analytics_ps_enhanced_measurement_track_select_item');
        $ps_config_track_select_promotion = $this->config->get('analytics_ps_enhanced_measurement_track_select_promotion');

        if (
            !$ps_config_track_add_to_wishlist &&
            !$ps_config_track_add_to_cart &&
            !$ps_config_track_select_item &&
            !$ps_config_track_select_promotion
        ) {
            return;
        }


        $this->load->language('extension/ps_enhanced_measurement/analytics/ps_enhanced_measurement');

        $this->load->model('catalog/product');
        $this->load->model('catalog/manufacturer');

        $ps_config_item_category_option = (int) $this->config->get('analytics_ps_enhanced_measurement_item_category_option');
        $ps_config_item_price_tax = $this->config->get('analytics_ps_enhanced_measurement_item_price_tax');
        $ps_config_location_id = $this->config->get('analytics_ps_enhanced_measurement_location_id');
        $ps_config_item_id_option = $this->config->get('analytics_ps_enhanced_measurement_item_id');
        $ps_config_affiliation = $this->config->get('analytics_ps_enhanced_measurement_affiliation');

        $ps_config_currency = $this->config->get('analytics_ps_enhanced_measurement_currency');

        if (empty($ps_config_currency)) {
            $ps_config_currency = $this->session->data['currency'];
        }

        if (isset($this->session->data['coupon'])) {
            $ps_product_coupon = $this->session->data['coupon'];
        } else if (isset($this->session->data['voucher'])) {
            $ps_product_coupon = $this->session->data['voucher'];
        } else {
            $ps_product_coupon = null;
        }


        $setting = isset($args[0]) ? $args[0] : ['limit' => 0, 'name' => ''];

        if (!empty($setting['product'])) {
            $products = [];

            foreach ($setting['product'] as $product_id) {
                $product_info = $this->model_catalog_product->getProduct($product_id);

                if ($product_info) {
                    $products[] = $product_info;
                }
            }

            $ps_item_list_name = sprintf(
                $this->language->get('text_x_products'),
                html_entity_decode($setting['name'], ENT_QUOTES, 'UTF-8')
            );
            $ps_item_list_id = $this->formatListId($ps_item_list_name);

            $ps_items = [];
            $ps_quantity_minimums = [];
            $ps_specials = [];

            foreach ($products as $index => $product_info) {
                $ps_item = [];

                $ps_item['item_id'] = isset($product_info[$ps_config_item_id_option]) && !empty($product_info[$ps_config_item_id_option]) ? $this->formatListId($product_info[$ps_config_item_id_option]) : $product_info['product_id'];
                $ps_item['item_name'] = html_entity_decode($product_info['name'], ENT_QUOTES, 'UTF-8');

                if ($ps_config_affiliation) {
                    $ps_item['affiliation'] = $ps_config_affiliation;
                }

                if ($ps_product_coupon) {
                    $ps_item['coupon'] = $ps_product_coupon;
                }

                if ((float) $product_info['special']) {
                    $ps_discount = $this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $ps_config_item_price_tax) - $this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $ps_config_item_price_tax);

                    $ps_item['discount'] = $this->currency->format($ps_discount, $ps_config_currency, 0, false);
                }

                $ps_item['index'] = $index;

                $ps_manufacturer_info = $this->model_catalog_manufacturer->getManufacturer((int) $product_info['manufacturer_id']);

                if ($ps_manufacturer_info) {
                    $ps_item['item_brand'] = $ps_manufacturer_info['name'];
                }

                switch ($ps_config_item_category_option) {
                    case 0:
                        $ps_product_categories = $this->getCategoryType1($product_info['product_id']);
                        break;
                    case 1:
                        $ps_product_categories = $this->getCategoryType2($product_info['product_id']);
                        break;
                    default:
                        $ps_product_categories = $this->getCategoryType1($product_info['product_id']);
                        break;
                }

                foreach ($ps_product_categories as $category_index => $category_name) {
                    if ($category_index === 0) {
                        $ps_item['item_category'] = $category_name;
                    } else {
                        $ps_item['item_category' . ($category_index + 1)] = $category_name;
                    }
                }

                $ps_item['item_list_id'] = $ps_item_list_id;
                $ps_item['item_list_name'] = $ps_item_list_name;

                if ($ps_config_location_id) {
                    $ps_item['location_id'] = $ps_config_location_id;
                }

                if ((float) $product_info['special']) {
                    $ps_price = $this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $ps_config_item_price_tax);
                } else {
                    $ps_price = $this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $ps_config_item_price_tax);
                }

                $ps_item['price'] = $this->currency->format($ps_price, $ps_config_currency, 0, false);

                $ps_item['quantity'] = $product_info['quantity'];

                if ($product_info['minimum']) {
                    $ps_quantity_minimums[(int) $product_info['product_id']] = $product_info['minimum'];
                } else {
                    $ps_quantity_minimums[(int) $product_info['product_id']] = 1;
                }

                $ps_specials[(int) $product_info['product_id']] = (float) $product_info['special'] > 0;

                $ps_items[(int) $product_info['product_id']] = $ps_item;

                if (!isset($this->session->data['ps_item_list_info'], $this->session->data['ps_item_list_info'][(int) $product_info['product_id']])) {
                    $this->session->data['ps_item_list_info'][(int) $product_info['product_id']] = [
                        'item_list_id' => $ps_item_list_id,
                        'item_list_name' => $ps_item_list_name,
                    ];
                }
            }


            $ps_merge_items = [];

            foreach ($ps_items as $product_id => $ps_item) {
                if ($ps_specials[$product_id]) {
                    if ($ps_config_track_select_promotion) {
                        $ps_merge_items['select_promotion_' . $product_id] = [
                            'ecommerce' => [
                                'item_list_id' => $ps_item_list_id,
                                'item_list_name' => $ps_item_list_name,
                                'items' => [$ps_item],
                            ],
                        ];
                    }
                } else {
                    if ($ps_config_track_select_item) {
                        $ps_merge_items['select_item_' . $product_id] = [
                            'ecommerce' => [
                                'item_list_id' => $ps_item_list_id,
                                'item_list_name' => $ps_item_list_name,
                                'items' => [$ps_item],
                            ],
                        ];
                    }
                }

                if ($ps_config_track_add_to_wishlist) {
                    $ps_merge_items['add_to_wishlist_' . $product_id] = [
                        'ecommerce' => [
                            'currency' => $ps_config_currency,
                            'value' => $ps_item['price'],
                            'items' => [$ps_item],
                        ],
                    ];
                }
            }

            if ($ps_config_track_add_to_cart) {
                foreach ($ps_items as $product_id => $ps_item) {
                    $ps_item['quantity'] = $ps_quantity_minimums[$product_id];

                    $ps_merge_items['add_to_cart_' . $product_id] = [
                        'ecommerce' => [
                            'currency' => $ps_config_currency,
                            'value' => $ps_item['price'] * $ps_quantity_minimums[$product_id],
                            'items' => [$ps_item],
                        ],
                    ];
                }
            }

            if ($ps_items) {
                $output = '<script>ps_dataLayer.setData(' . PHP_EOL . json_encode($ps_merge_items, JSON_NUMERIC_CHECK) . PHP_EOL . ');</script>' . PHP_EOL . $output;
            }
        }
    }

    public function eventCatalogViewExtensionOpencartModuleLatestAfter(string &$route, array &$args, string &$output): void
    {
        if (!$this->config->get('analytics_ps_enhanced_measurement_status')) {
            return;
        }

        $ps_config_track_add_to_wishlist = $this->config->get('analytics_ps_enhanced_measurement_track_add_to_wishlist');
        $ps_config_track_add_to_cart = $this->config->get('analytics_ps_enhanced_measurement_track_add_to_cart') || $this->config->get('analytics_ps_enhanced_measurement_adwords_add_to_cart_label');
        $ps_config_track_select_item = $this->config->get('analytics_ps_enhanced_measurement_track_select_item');
        $ps_config_track_select_promotion = $this->config->get('analytics_ps_enhanced_measurement_track_select_promotion');

        if (
            !$ps_config_track_add_to_wishlist &&
            !$ps_config_track_add_to_cart &&
            !$ps_config_track_select_item &&
            !$ps_config_track_select_promotion
        ) {
            return;
        }


        $this->load->language('extension/ps_enhanced_measurement/analytics/ps_enhanced_measurement');

        if (version_compare(VERSION, '4.0.2.3', '>=')) { // Added extension/opencart/module/latest
            $this->load->model('extension/opencart/module/latest');
        } else {
            $this->load->model('catalog/product');
        }

        $this->load->model('catalog/manufacturer');

        $ps_config_item_category_option = (int) $this->config->get('analytics_ps_enhanced_measurement_item_category_option');
        $ps_config_item_price_tax = $this->config->get('analytics_ps_enhanced_measurement_item_price_tax');
        $ps_config_location_id = $this->config->get('analytics_ps_enhanced_measurement_location_id');
        $ps_config_item_id_option = $this->config->get('analytics_ps_enhanced_measurement_item_id');
        $ps_config_affiliation = $this->config->get('analytics_ps_enhanced_measurement_affiliation');

        $ps_config_currency = $this->config->get('analytics_ps_enhanced_measurement_currency');

        if (empty($ps_config_currency)) {
            $ps_config_currency = $this->session->data['currency'];
        }

        if (isset($this->session->data['coupon'])) {
            $ps_product_coupon = $this->session->data['coupon'];
        } else if (isset($this->session->data['voucher'])) {
            $ps_product_coupon = $this->session->data['voucher'];
        } else {
            $ps_product_coupon = null;
        }


        $setting = isset($args[0]) ? $args[0] : ['limit' => 0, 'name' => ''];

        if (version_compare(VERSION, '4.0.2.3', '>=')) { // Added extension/opencart/module/latest
            $products = $this->model_extension_opencart_module_latest->getLatest($setting['limit']);
        } else {
            $products = $this->model_catalog_product->getLatest($setting['limit']);
        }

        if ($products) {
            $ps_item_list_name = sprintf(
                $this->language->get('text_x_products'),
                html_entity_decode($setting['name'], ENT_QUOTES, 'UTF-8')
            );
            $ps_item_list_id = $this->formatListId($ps_item_list_name);

            $ps_items = [];
            $ps_quantity_minimums = [];
            $ps_specials = [];

            foreach ($products as $index => $product_info) {
                $ps_item = [];

                $ps_item['item_id'] = isset($product_info[$ps_config_item_id_option]) && !empty($product_info[$ps_config_item_id_option]) ? $this->formatListId($product_info[$ps_config_item_id_option]) : $product_info['product_id'];
                $ps_item['item_name'] = html_entity_decode($product_info['name'], ENT_QUOTES, 'UTF-8');

                if ($ps_config_affiliation) {
                    $ps_item['affiliation'] = $ps_config_affiliation;
                }

                if ($ps_product_coupon) {
                    $ps_item['coupon'] = $ps_product_coupon;
                }

                if ((float) $product_info['special']) {
                    $ps_discount = $this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $ps_config_item_price_tax) - $this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $ps_config_item_price_tax);

                    $ps_item['discount'] = $this->currency->format($ps_discount, $ps_config_currency, 0, false);
                }

                $ps_item['index'] = $index;

                $ps_manufacturer_info = $this->model_catalog_manufacturer->getManufacturer((int) $product_info['manufacturer_id']);

                if ($ps_manufacturer_info) {
                    $ps_item['item_brand'] = $ps_manufacturer_info['name'];
                }

                switch ($ps_config_item_category_option) {
                    case 0:
                        $ps_product_categories = $this->getCategoryType1($product_info['product_id']);
                        break;
                    case 1:
                        $ps_product_categories = $this->getCategoryType2($product_info['product_id']);
                        break;
                    default:
                        $ps_product_categories = $this->getCategoryType1($product_info['product_id']);
                        break;
                }

                foreach ($ps_product_categories as $category_index => $category_name) {
                    if ($category_index === 0) {
                        $ps_item['item_category'] = $category_name;
                    } else {
                        $ps_item['item_category' . ($category_index + 1)] = $category_name;
                    }
                }

                $ps_item['item_list_id'] = $ps_item_list_id;
                $ps_item['item_list_name'] = $ps_item_list_name;

                if ($ps_config_location_id) {
                    $ps_item['location_id'] = $ps_config_location_id;
                }

                if ((float) $product_info['special']) {
                    $ps_price = $this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $ps_config_item_price_tax);
                } else {
                    $ps_price = $this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $ps_config_item_price_tax);
                }

                $ps_item['price'] = $this->currency->format($ps_price, $ps_config_currency, 0, false);

                $ps_item['quantity'] = $product_info['quantity'];

                if ($product_info['minimum']) {
                    $ps_quantity_minimums[(int) $product_info['product_id']] = $product_info['minimum'];
                } else {
                    $ps_quantity_minimums[(int) $product_info['product_id']] = 1;
                }

                $ps_specials[(int) $product_info['product_id']] = (float) $product_info['special'] > 0;

                $ps_items[(int) $product_info['product_id']] = $ps_item;

                if (!isset($this->session->data['ps_item_list_info'], $this->session->data['ps_item_list_info'][(int) $product_info['product_id']])) {
                    $this->session->data['ps_item_list_info'][(int) $product_info['product_id']] = [
                        'item_list_id' => $ps_item_list_id,
                        'item_list_name' => $ps_item_list_name,
                    ];
                }
            }


            $ps_merge_items = [];

            foreach ($ps_items as $product_id => $ps_item) {
                if ($ps_specials[$product_id]) {
                    if ($ps_config_track_select_promotion) {
                        $ps_merge_items['select_promotion_' . $product_id] = [
                            'ecommerce' => [
                                'item_list_id' => $ps_item_list_id,
                                'item_list_name' => $ps_item_list_name,
                                'items' => [$ps_item],
                            ],
                        ];
                    }
                } else {
                    if ($ps_config_track_select_item) {
                        $ps_merge_items['select_item_' . $product_id] = [
                            'ecommerce' => [
                                'item_list_id' => $ps_item_list_id,
                                'item_list_name' => $ps_item_list_name,
                                'items' => [$ps_item],
                            ],
                        ];
                    }
                }

                if ($ps_config_track_add_to_wishlist) {
                    $ps_merge_items['add_to_wishlist_' . $product_id] = [
                        'ecommerce' => [
                            'currency' => $ps_config_currency,
                            'value' => $ps_item['price'],
                            'items' => [$ps_item],
                        ],
                    ];
                }
            }

            if ($ps_config_track_add_to_cart) {
                foreach ($ps_items as $product_id => $ps_item) {
                    $ps_item['quantity'] = $ps_quantity_minimums[$product_id];

                    $ps_merge_items['add_to_cart_' . $product_id] = [
                        'ecommerce' => [
                            'currency' => $ps_config_currency,
                            'value' => $ps_item['price'] * $ps_quantity_minimums[$product_id],
                            'items' => [$ps_item],
                        ],
                    ];
                }
            }

            if ($ps_items) {
                $output = '<script>ps_dataLayer.setData(' . PHP_EOL . json_encode($ps_merge_items, JSON_NUMERIC_CHECK) . PHP_EOL . ');</script>' . PHP_EOL . $output;
            }
        }
    }

    public function eventCatalogViewExtensionOpencartModuleSpecialAfter(string &$route, array &$args, string &$output): void
    {
        if (!$this->config->get('analytics_ps_enhanced_measurement_status')) {
            return;
        }

        $ps_config_track_add_to_wishlist = $this->config->get('analytics_ps_enhanced_measurement_track_add_to_wishlist');
        $ps_config_track_add_to_cart = $this->config->get('analytics_ps_enhanced_measurement_track_add_to_cart') || $this->config->get('analytics_ps_enhanced_measurement_adwords_add_to_cart_label');
        $ps_config_track_select_item = $this->config->get('analytics_ps_enhanced_measurement_track_select_item');
        $ps_config_track_select_promotion = $this->config->get('analytics_ps_enhanced_measurement_track_select_promotion');

        if (
            !$ps_config_track_add_to_wishlist &&
            !$ps_config_track_add_to_cart &&
            !$ps_config_track_select_item &&
            !$ps_config_track_select_promotion
        ) {
            return;
        }


        $this->load->language('extension/ps_enhanced_measurement/analytics/ps_enhanced_measurement');

        $this->load->model('catalog/product');
        $this->load->model('catalog/manufacturer');

        $ps_config_item_category_option = (int) $this->config->get('analytics_ps_enhanced_measurement_item_category_option');
        $ps_config_item_price_tax = $this->config->get('analytics_ps_enhanced_measurement_item_price_tax');
        $ps_config_location_id = $this->config->get('analytics_ps_enhanced_measurement_location_id');
        $ps_config_item_id_option = $this->config->get('analytics_ps_enhanced_measurement_item_id');
        $ps_config_affiliation = $this->config->get('analytics_ps_enhanced_measurement_affiliation');

        $ps_config_currency = $this->config->get('analytics_ps_enhanced_measurement_currency');

        if (empty($ps_config_currency)) {
            $ps_config_currency = $this->session->data['currency'];
        }

        if (isset($this->session->data['coupon'])) {
            $ps_product_coupon = $this->session->data['coupon'];
        } else if (isset($this->session->data['voucher'])) {
            $ps_product_coupon = $this->session->data['voucher'];
        } else {
            $ps_product_coupon = null;
        }


        $setting = isset($args[0]) ? $args[0] : ['limit' => 0, 'name' => ''];

        $specials_filter_data = [
            'sort' => 'pd.name',
            'order' => 'ASC',
            'start' => 0,
            'limit' => $setting['limit']
        ];

        $products = $this->model_catalog_product->getSpecials($specials_filter_data);

        if ($products) {
            $ps_item_list_name = sprintf(
                $this->language->get('text_x_products'),
                html_entity_decode($setting['name'], ENT_QUOTES, 'UTF-8')
            );
            $ps_item_list_id = $this->formatListId($ps_item_list_name);

            $ps_items = [];
            $ps_quantity_minimums = [];
            $ps_specials = [];

            foreach ($products as $index => $product_info) {
                $ps_item = [];

                $ps_item['item_id'] = isset($product_info[$ps_config_item_id_option]) && !empty($product_info[$ps_config_item_id_option]) ? $this->formatListId($product_info[$ps_config_item_id_option]) : $product_info['product_id'];
                $ps_item['item_name'] = html_entity_decode($product_info['name'], ENT_QUOTES, 'UTF-8');

                if ($ps_config_affiliation) {
                    $ps_item['affiliation'] = $ps_config_affiliation;
                }

                if ($ps_product_coupon) {
                    $ps_item['coupon'] = $ps_product_coupon;
                }

                if ((float) $product_info['special']) {
                    $ps_discount = $this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $ps_config_item_price_tax) - $this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $ps_config_item_price_tax);

                    $ps_item['discount'] = $this->currency->format($ps_discount, $ps_config_currency, 0, false);
                }

                $ps_item['index'] = $index;

                $ps_manufacturer_info = $this->model_catalog_manufacturer->getManufacturer((int) $product_info['manufacturer_id']);

                if ($ps_manufacturer_info) {
                    $ps_item['item_brand'] = $ps_manufacturer_info['name'];
                }

                switch ($ps_config_item_category_option) {
                    case 0:
                        $ps_product_categories = $this->getCategoryType1($product_info['product_id']);
                        break;
                    case 1:
                        $ps_product_categories = $this->getCategoryType2($product_info['product_id']);
                        break;
                    default:
                        $ps_product_categories = $this->getCategoryType1($product_info['product_id']);
                        break;
                }

                foreach ($ps_product_categories as $category_index => $category_name) {
                    if ($category_index === 0) {
                        $ps_item['item_category'] = $category_name;
                    } else {
                        $ps_item['item_category' . ($category_index + 1)] = $category_name;
                    }
                }

                $ps_item['item_list_id'] = $ps_item_list_id;
                $ps_item['item_list_name'] = $ps_item_list_name;

                if ($ps_config_location_id) {
                    $ps_item['location_id'] = $ps_config_location_id;
                }

                if ((float) $product_info['special']) {
                    $ps_price = $this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $ps_config_item_price_tax);
                } else {
                    $ps_price = $this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $ps_config_item_price_tax);
                }

                $ps_item['price'] = $this->currency->format($ps_price, $ps_config_currency, 0, false);

                $ps_item['quantity'] = $product_info['quantity'];

                if ($product_info['minimum']) {
                    $ps_quantity_minimums[(int) $product_info['product_id']] = $product_info['minimum'];
                } else {
                    $ps_quantity_minimums[(int) $product_info['product_id']] = 1;
                }

                $ps_specials[(int) $product_info['product_id']] = (float) $product_info['special'] > 0;

                $ps_items[(int) $product_info['product_id']] = $ps_item;

                if (!isset($this->session->data['ps_item_list_info'], $this->session->data['ps_item_list_info'][(int) $product_info['product_id']])) {
                    $this->session->data['ps_item_list_info'][(int) $product_info['product_id']] = [
                        'item_list_id' => $ps_item_list_id,
                        'item_list_name' => $ps_item_list_name,
                    ];
                }
            }


            $ps_merge_items = [];

            foreach ($ps_items as $product_id => $ps_item) {
                if ($ps_specials[$product_id]) {
                    if ($ps_config_track_select_promotion) {
                        $ps_merge_items['select_promotion_' . $product_id] = [
                            'ecommerce' => [
                                'item_list_id' => $ps_item_list_id,
                                'item_list_name' => $ps_item_list_name,
                                'items' => [$ps_item],
                            ],
                        ];
                    }
                } else {
                    if ($ps_config_track_select_item) {
                        $ps_merge_items['select_item_' . $product_id] = [
                            'ecommerce' => [
                                'item_list_id' => $ps_item_list_id,
                                'item_list_name' => $ps_item_list_name,
                                'items' => [$ps_item],
                            ],
                        ];
                    }
                }

                if ($ps_config_track_add_to_wishlist) {
                    $ps_merge_items['add_to_wishlist_' . $product_id] = [
                        'ecommerce' => [
                            'currency' => $ps_config_currency,
                            'value' => $ps_item['price'],
                            'items' => [$ps_item],
                        ],
                    ];
                }
            }

            if ($ps_config_track_add_to_cart) {
                foreach ($ps_items as $product_id => $ps_item) {
                    $ps_item['quantity'] = $ps_quantity_minimums[$product_id];

                    $ps_merge_items['add_to_cart_' . $product_id] = [
                        'ecommerce' => [
                            'currency' => $ps_config_currency,
                            'value' => $ps_item['price'] * $ps_quantity_minimums[$product_id],
                            'items' => [$ps_item],
                        ],
                    ];
                }
            }

            if ($ps_items) {
                $output = '<script>ps_dataLayer.setData(' . PHP_EOL . json_encode($ps_merge_items, JSON_NUMERIC_CHECK) . PHP_EOL . ');</script>' . PHP_EOL . $output;
            }
        }
    }

    protected function getProductSubscriptionDescription(array $product_subscription_info, int $tax_class_id, string $ps_config_currency, bool $ps_config_item_price_tax)
    {
        $ps_subscription_description = '';

        if ($product_subscription_info['trial_status']) {
            $ps_trial_price = $this->currency->format($this->tax->calculate($product_subscription_info['trial_price'], $tax_class_id, $ps_config_item_price_tax), $ps_config_currency);
            $ps_trial_cycle = $product_subscription_info['trial_cycle'];
            $ps_trial_frequency = $this->language->get('text_' . $product_subscription_info['trial_frequency']);
            $ps_trial_duration = $product_subscription_info['trial_duration'];

            $ps_subscription_description .= sprintf($this->language->get('text_subscription_trial'), $ps_trial_price, $ps_trial_cycle, $ps_trial_frequency, $ps_trial_duration);
        }

        $ps_subscription_price = $this->currency->format($this->tax->calculate($product_subscription_info['price'], $tax_class_id, $ps_config_item_price_tax), $ps_config_currency);

        $ps_subscription_cycle = $product_subscription_info['cycle'];
        $ps_subscription_frequency = $this->language->get('text_' . $product_subscription_info['frequency']);
        $ps_subscription_duration = $product_subscription_info['duration'];

        if ($ps_subscription_duration) {
            $ps_subscription_description .= sprintf($this->language->get('text_subscription_duration'), $ps_subscription_price, $ps_subscription_cycle, $ps_subscription_frequency, $ps_subscription_duration);
        } else {
            $ps_subscription_description .= sprintf($this->language->get('text_subscription_cancel'), $ps_subscription_price, $ps_subscription_cycle, $ps_subscription_frequency);
        }

        return $ps_subscription_description;
    }

    public function getAdwordsUserData(): array
    {
        $ps_is_logged = $this->customer->isLogged();

        $ps_user_info = [];

        if (isset($this->session->data['customer'])) {
            $ps_user_info = array_merge($ps_user_info, array_filter($this->session->data['customer']));
        }

        if (isset($this->session->data['payment_address'])) {
            $ps_user_info = array_merge($ps_user_info, array_filter($this->session->data['payment_address']));
        }

        if (isset($this->session->data['shipping_address'])) {
            $ps_user_info = array_merge($ps_user_info, array_filter($this->session->data['shipping_address']));
        }

        if ($ps_is_logged) {
            if ($value = $this->customer->getEmail()) {
                $ps_user_info['email'] = $value;
            }

            if ($value = $this->customer->getTelephone()) {
                $ps_user_info['telephone'] = $value;
            }

            if ($value = $this->customer->getFirstName()) {
                $ps_user_info['firstname'] = $value;
            }

            if ($value = $this->customer->getLastName()) {
                $ps_user_info['lastname'] = $value;
            }


            $ps_cached_address = (array) $this->cache->get('adwords_user_data.' . $this->customer->getId());

            if (!$ps_cached_address) {
                $this->load->model('account/address');

                $ps_all_address = $this->model_account_address->getAddresses($this->customer->getId());

                $ps_cached_address = array_filter((array) current($ps_all_address));

                $this->cache->set('adwords_user_data.' . $this->customer->getId(), $ps_cached_address);
            }

            $ps_user_info = array_merge($ps_user_info, $ps_cached_address);
        }


        $ps_result = [];

        if (isset($ps_user_info['email']) && $ps_user_info['email']) {
            $ps_result['email'] = hash('sha256', $ps_user_info['email']);
        }

        if (isset($ps_user_info['telephone']) && $ps_user_info['telephone']) {
            $ps_result['phone_number'] = hash('sha256', $ps_user_info['telephone']);
        }

        if (isset($ps_user_info['firstname']) && $ps_user_info['firstname']) {
            $ps_result['address']['first_name'] = hash('sha256', $ps_user_info['firstname']);
        }

        if (isset($ps_user_info['lastname']) && $ps_user_info['lastname']) {
            $ps_result['address']['last_name'] = hash('sha256', $ps_user_info['lastname']);
        }

        if (isset($ps_user_info['address_1']) && $ps_user_info['address_1']) {
            $ps_result['address']['street'] = hash('sha256', $ps_user_info['address_1']);
        } else if (isset($ps_user_info['address_2']) && $ps_user_info['address_2']) {
            $ps_result['address']['street'] = hash('sha256', $ps_user_info['address_2']);
        }

        if (isset($ps_user_info['city']) && $ps_user_info['city']) {
            $ps_result['address']['city'] = hash('sha256', $ps_user_info['city']);
        }

        if (isset($ps_user_info['zone']) && $ps_user_info['zone']) {
            $ps_result['address']['region'] = hash('sha256', $ps_user_info['zone']);
        }

        if (isset($ps_user_info['postcode']) && $ps_user_info['postcode']) {
            $ps_result['address']['postal_code'] = hash('sha256', $ps_user_info['postcode']);
        }

        if (isset($ps_user_info['country']) && $ps_user_info['country']) {
            $ps_result['address']['country'] = hash('sha256', $ps_user_info['country']);
        }

        return $ps_result;
    }

    protected function getCategoryType1(int $product_id): array
    {
        $ps_product_categories = $this->model_extension_ps_enhanced_measurement_analytics_ps_enhanced_measurement->getCategories($product_id);

        $ps_result = [];

        foreach ($ps_product_categories as $ps_category_id) {
            $ps_category_info = $this->model_extension_ps_enhanced_measurement_analytics_ps_enhanced_measurement->getCategoryType1($ps_category_id);

            if ($ps_category_info) {
                $ps_result[] = html_entity_decode($ps_category_info['last_category_name'], ENT_QUOTES, 'UTF-8');
            }
        }

        return $ps_result;
    }

    protected function getCategoryType2(int $product_id): array
    {
        $ps_product_categories = $this->model_extension_ps_enhanced_measurement_analytics_ps_enhanced_measurement->getCategories($product_id);

        $ps_result = [];

        foreach ($ps_product_categories as $ps_category_id) {
            $ps_category_info = $this->model_extension_ps_enhanced_measurement_analytics_ps_enhanced_measurement->getCategoryType2($ps_category_id);

            if ($ps_category_info) {
                if ($ps_category_info['path']) {
                    $ps_result[] = html_entity_decode($ps_category_info['path'] . ' &gt; ' . $ps_category_info['name'], ENT_QUOTES, 'UTF-8');
                } else {
                    $ps_result[] = html_entity_decode($ps_category_info['name'], ENT_QUOTES, 'UTF-8');
                }
            }
        }

        return $ps_result;
    }

    protected function getCategoryType3(int $ps_category_id): array
    {
        $ps_result = [];

        $ps_category_infos = $this->model_extension_ps_enhanced_measurement_analytics_ps_enhanced_measurement->getCategoryType3($ps_category_id);

        foreach ($ps_category_infos as $ps_category_info) {
            $ps_result[] = html_entity_decode($ps_category_info['name'], ENT_QUOTES, 'UTF-8');
        }

        return $ps_result;
    }

    protected function getCategoryType4(array $ps_category_info): array
    {
        $ps_result = [];

        $ps_result[] = html_entity_decode($ps_category_info['name'], ENT_QUOTES, 'UTF-8');

        return $ps_result;
    }

    protected function formatListId(string $string): string
    {
        if (function_exists('iconv')) {
            $ps_new_string = iconv('UTF-8', 'ASCII//TRANSLIT', $string);
        } elseif (function_exists('mb_convert_encoding')) {
            $ps_new_string = mb_convert_encoding($string, 'ASCII');
        } else {
            $ps_new_string = false;
        }

        if ($ps_new_string === false) {
            $ps_new_string = $string;
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

        // Support for OC4 extension
        $firstSlash = strpos($route, '/');
        $secondSlash = strpos($route, '/', $firstSlash + 1);
        $template_file = DIR_OPENCART . substr($route, 0, $secondSlash + 1) . 'catalog/view/template/' . substr($route, $secondSlash + 1) . '.twig';

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
