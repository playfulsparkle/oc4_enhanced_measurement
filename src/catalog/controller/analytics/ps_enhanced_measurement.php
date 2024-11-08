<?php
namespace Opencart\Catalog\Controller\Extension\PsEnhancedMeasurement\Analytics;
/**
 * Class PsEnhancedMeasurement
 *
 * @package Opencart\Catalog\Controller\Extension\PsEnhancedMeasurement\Analytics
 */
class PsEnhancedMeasurement extends \Opencart\System\Engine\Controller
{
    private $_product_id = [];

    public function index(): string
    {
        if (!$this->config->get('analytics_ps_enhanced_measurement_status')) {
            return '';
        }

        $measurement_implementation = $this->config->get('analytics_ps_enhanced_measurement_implementation');
        $gtm_id = $this->config->get('analytics_ps_enhanced_measurement_gtm_id');
        $google_tag_id = $this->config->get('analytics_ps_enhanced_measurement_google_tag_id');

        if ($measurement_implementation === 'gtag') {
            return <<<HTML
            <!-- Google tag (gtag.js) -->
            <script async src="https://www.googletagmanager.com/gtag/js?id={$google_tag_id}"></script>
            <script>
                window.dataLayer = window.dataLayer || [];
                function gtag() { dataLayer.push(arguments); }

                gtag('js', new Date());
                gtag('config', '{$google_tag_id}', {'cookie_flags': 'SameSite=None;Secure'});
            </script>
            HTML;
        } else if ($measurement_implementation === 'gtm') {
            return <<<HTML
            <!-- Google Tag Manager -->
            <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
            new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
            })(window,document,'script','dataLayer','{$gtm_id}');</script>
            <!-- End Google Tag Manager -->
            HTML;
        }

        return '';
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

        $script = 'extension/ps_enhanced_measurement/catalog/view/javascript/ps-enhanced-measurement.js';

        $args['scripts'][$script] = ['href' => $script];

        if ($this->config->get('analytics_ps_enhanced_measurement_implementation') !== 'gtm') {
            return;
        }

        $this->load->model('extension/ps_enhanced_measurement/analytics/ps_enhanced_measurement');

        $args['ps_enhanced_measurement_gtm_id'] = $this->config->get('analytics_ps_enhanced_measurement_gtm_id');

        $headerViews = $this->model_extension_ps_enhanced_measurement_analytics_ps_enhanced_measurement->replaceHeaderViews($args);

        $template = $this->replaceViews($route, $template, $headerViews);
    }

    public function eventCatalogViewExtensionOpencartModuleBannerBefore(string &$route, array &$args, string &$template): void
    {
        if (!$this->config->get('analytics_ps_enhanced_measurement_status')) {
            return;
        }

        $this->load->model('extension/ps_enhanced_measurement/analytics/ps_enhanced_measurement');

        foreach ($args['banners'] as $key => $banner) {
            $queryParams = [];

            $query = parse_url(str_replace('&amp;', '&', $banner['link']), PHP_URL_QUERY);

            if ($query) {
                parse_str($query, $queryParams);
            }

            if (isset($queryParams['product_id']) && !isset($this->_product_id[(int) $queryParams['product_id']])) {
                $this->_product_id[(int) $queryParams['product_id']] = true;

                $args['banners'][$key]['product_id'] = (int) $queryParams['product_id'];
                $args['banners'][$key]['datalayer'] = json_encode([]);
            } else {
                $args['banners'][$key]['product_id'] = null;
                $args['banners'][$key]['datalayer'] = null;
            }
        }

        $views = $this->model_extension_ps_enhanced_measurement_analytics_ps_enhanced_measurement->replaceBannerViews($args);

        $template = $this->replaceViews($route, $template, $views);
    }

    public function eventCatalogViewCheckoutCartListBefore(string &$route, array &$args, string &$template): void
    {
        if (!$this->config->get('analytics_ps_enhanced_measurement_status')) {
            return;
        }

        $this->load->model('extension/ps_enhanced_measurement/analytics/ps_enhanced_measurement');

        $this->load->model('checkout/cart');

        $products = $this->model_checkout_cart->getProducts();

        $args['product_id_list'] = [];

        foreach ($products as $product) {
            $args['product_id_list'][$product['cart_id']] = $product['product_id'];
        }

        $views = $this->model_extension_ps_enhanced_measurement_analytics_ps_enhanced_measurement->replaceCartItemViews($args);

        $template = $this->replaceViews($route, $template, $views);
    }

    public function eventCatalogViewAccountWishlistListBefore(string &$route, array &$args, string &$template): void
    {
        if (!$this->config->get('analytics_ps_enhanced_measurement_status')) {
            return;
        }

        $this->load->model('extension/ps_enhanced_measurement/analytics/ps_enhanced_measurement');

        $views = $this->model_extension_ps_enhanced_measurement_analytics_ps_enhanced_measurement->replaceCompareAndWishlistViews($args);

        $template = $this->replaceViews($route, $template, $views);
    }

    public function eventCatalogViewProductCategoryBefore(string &$route, array &$args, string &$template): void
    {
        if (!$this->config->get('analytics_ps_enhanced_measurement_status')) {
            return;
        }

        $this->load->model('extension/ps_enhanced_measurement/analytics/ps_enhanced_measurement');
        $this->load->model('catalog/category');
        $this->load->model('catalog/manufacturer');
        $this->load->model('catalog/product');

        if (isset($this->request->get['path'])) {
            $path = (string) $this->request->get['path'];
        } else {
            $path = '';
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

        if (isset($this->request->get['limit']) && (int) $this->request->get['limit']) {
            $limit = (int) $this->request->get['limit'];
        } else {
            $limit = $this->config->get('config_pagination');
        }

        $parts = explode('_', $path);

        $category_id = (int) array_pop($parts);

        $category_info = $this->model_catalog_category->getCategory($category_id);

        if ($category_info) {
            $data = [
                'event' => 'view_item_list',
                'ecommerce' => [],
            ];

            $item_list_id = $this->_formatListId(html_entity_decode($category_info['name'], ENT_QUOTES, 'UTF-8'));
            $item_list_name = html_entity_decode($category_info['name'], ENT_QUOTES, 'UTF-8');

            $item_category_option = (int) $this->config->get('analytics_ps_enhanced_measurement_item_category_option');
            $item_price_tax = $this->config->get('analytics_ps_enhanced_measurement_item_price_tax');
            $location_id = $this->config->get('analytics_ps_enhanced_measurement_location_id');
            $affiliation = $this->config->get('analytics_ps_enhanced_measurement_affiliation');
            $item_id_option = $this->config->get('analytics_ps_enhanced_measurement_item_id');
            $currency = $this->config->get('analytics_ps_enhanced_measurement_currency');

            if (empty($currency)) {
                $currency = $this->session->data['currency'];
            }

            if (empty($affiliation)) {
                $affiliation = $this->config->get('config_name');
            }

            $items = [];

            $filter_data = [
                'filter_category_id' => $category_id,
                'filter_sub_category' => false,
                'filter_filter' => $filter,
                'sort' => $sort,
                'order' => $order,
                'start' => ($page - 1) * $limit,
                'limit' => $limit
            ];

            $products = $this->model_catalog_product->getProducts($filter_data);

            foreach ($products as $key => $product_info) {
                $item = [];
                $item['item_id'] = isset($product_info[$item_id_option]) && !empty($product_info[$item_id_option]) ? $this->_formatListId($product_info[$item_id_option]) : $product_info['product_id'];
                $item['item_name'] = html_entity_decode($product_info['name'], ENT_QUOTES, 'UTF-8');
                $item['affiliation'] = $affiliation;

                if (isset($this->session->data['coupon'])) {
                    $item['coupon'] = $this->session->data['coupon'];
                }

                if ((float) $product_info['special']) {
                    if ($item_price_tax) {
                        $discount = $this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')) - $this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax'));
                    } else {
                        $discount = $product_info['price'] - $product_info['special'];
                    }

                    $item['discount'] = $this->currency->format($discount, $currency, 0, false);
                }

                $item['index'] = $key;

                $manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($product_info['manufacturer_id']);

                if ($manufacturer_info) {
                    $item['item_brand'] = $manufacturer_info['name'];
                }

                if ($item_category_option === 0) {
                    $categories = $this->_getCategoryType1($product_info['product_id']);
                } else if ($item_category_option === 1) {
                    $categories = $this->_getCategoryType2($product_info['product_id']);
                } else if ($item_category_option === 2) {
                    $categories = $this->_getCategoryType3($category_id);
                } else if ($item_category_option === 3) {
                    $categories = $this->_getCategoryType4($category_info);
                } else {
                    $categories = [];
                }

                $total_categories = count($categories);

                foreach ($categories as $category_index => $category_name) {
                    if ($total_categories === 0 || $category_index === 0) {
                        $item['item_category'] = $category_name;
                    } else {
                        $item['item_category' . ($category_index + 1)] = html_entity_decode($category_name, ENT_QUOTES, 'UTF-8');
                    }
                }

                $item['item_list_id'] = $item_list_id;
                $item['item_list_name'] = $item_list_name;
                // $item['item_variant'] = '';

                if ($location_id) {
                    $item['location_id'] = $location_id;
                }

                if ((float) $product_info['special']) {
                    if ($item_price_tax) {
                        $price = $this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax'));
                    } else {
                        $price = $product_info['special'];
                    }
                } else {
                    if ($item_price_tax) {
                        $price = $this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax'));
                    } else {
                        $price = $product_info['price'];
                    }
                }

                $item['price'] = $this->currency->format($price, $currency, 0, false);

                $item['quantity'] = $product_info['quantity'];

                $items[] = $item;
            }

            $data['ecommerce']['item_list_id'] = $item_list_id;
            $data['ecommerce']['item_list_name'] = $item_list_name;
            $data['ecommerce']['items'] = $items;

            if ($this->config->get('analytics_ps_enhanced_measurement_implementation') === 'gtag') {
                $args['ps_enhanced_measurement'] = 'gtag("event", "' . $data['event'] . '", ' . json_encode($data['ecommerce'], JSON_PRETTY_PRINT | JSON_NUMERIC_CHECK) . ')';
            } else if ($this->config->get('analytics_ps_enhanced_measurement_implementation') === 'gtm') {
                $args['ps_enhanced_measurement'] = 'dataLayer.push({ ecommerce: null });' . PHP_EOL . 'dataLayer.push(' . json_encode($data, JSON_PRETTY_PRINT | JSON_NUMERIC_CHECK) . ')';
            }
        } else {
            $args['ps_enhanced_measurement'] = '';
        }

        $views = $this->model_extension_ps_enhanced_measurement_analytics_ps_enhanced_measurement->replaceCatalogViewProductCategoryViews($args);

        $template = $this->replaceViews($route, $template, $views);
    }

    public function eventCatalogViewProductProductBefore(string &$route, array &$args, string &$template): void
    {
        if (!$this->config->get('analytics_ps_enhanced_measurement_status')) {
            return;
        }

        if (isset($this->request->get['product_id'])) {
            $product_id = (int) $this->request->get['product_id'];
        } else {
            $product_id = 0;
        }

        $this->load->model('extension/ps_enhanced_measurement/analytics/ps_enhanced_measurement');
        $this->load->model('catalog/category');
        $this->load->model('catalog/manufacturer');
        $this->load->model('catalog/product');

        $product_info = $this->model_catalog_product->getProduct($product_id);

        if ($product_info) {
            $data = [
                'event' => 'view_item',
                'ecommerce' => [],
            ];

            $item_category_option = (int) $this->config->get('analytics_ps_enhanced_measurement_item_category_option');
            $item_price_tax = $this->config->get('analytics_ps_enhanced_measurement_item_price_tax');
            $location_id = $this->config->get('analytics_ps_enhanced_measurement_location_id');
            $affiliation = $this->config->get('analytics_ps_enhanced_measurement_affiliation');
            $item_id_option = $this->config->get('analytics_ps_enhanced_measurement_item_id');
            $currency = $this->config->get('analytics_ps_enhanced_measurement_currency');

            if (empty($currency)) {
                $currency = $this->session->data['currency'];
            }

            if (empty($affiliation)) {
                $affiliation = $this->config->get('config_name');
            }

            $items = [];

            $item = [];
            $item['item_id'] = isset($product_info[$item_id_option]) && !empty($product_info[$item_id_option]) ? $this->_formatListId($product_info[$item_id_option]) : $product_info['product_id'];
            $item['item_name'] = html_entity_decode($product_info['name'], ENT_QUOTES, 'UTF-8');
            $item['affiliation'] = $affiliation;

            if (isset($this->session->data['coupon'])) {
                $item['coupon'] = $this->session->data['coupon'];
            }

            if ((float) $product_info['special']) {
                if ($item_price_tax) {
                    $discount = $this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')) - $this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax'));
                } else {
                    $discount = $product_info['price'] - $product_info['special'];
                }

                $item['discount'] = $this->currency->format($discount, $currency, 0, false);
            }

            $item['index'] = 0;

            $manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($product_info['manufacturer_id']);

            if ($manufacturer_info) {
                $item['item_brand'] = $manufacturer_info['name'];
            }

            if (isset($this->request->get['path'])) {
                $parts = explode('_', (string) $this->request->get['path']);

                $category_id = (int) array_pop($parts);
            } else if (isset($this->request->get['category_id'])) {
                $category_id = (int) $this->request->get['category_id'];
            } else {
                $category_id = 0;
            }

            $category_info = $this->model_catalog_category->getCategory($category_id);

            if ($item_category_option === 0) {
                $categories = $this->_getCategoryType1($product_info['product_id']);
            } else if ($item_category_option === 1) {
                $categories = $this->_getCategoryType2($product_info['product_id']);
            } else if ($item_category_option === 2 && $category_id) {
                $categories = $this->_getCategoryType3($category_id);
            } else if ($item_category_option === 3 && $category_info) {
                $categories = $this->_getCategoryType4($category_info);
            } else {
                $categories = [];
            }

            $total_categories = count($categories);

            foreach ($categories as $category_index => $category_name) {
                if ($total_categories === 0 || $category_index === 0) {
                    $item['item_category'] = $category_name;
                } else {
                    $item['item_category' . ($category_index + 1)] = html_entity_decode($category_name, ENT_QUOTES, 'UTF-8');
                }
            }

            // $item['item_variant'] = '';

            if ($location_id) {
                $item['location_id'] = $location_id;
            }

            if ((float) $product_info['special']) {
                if ($item_price_tax) {
                    $price = $this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax'));
                } else {
                    $price = $product_info['special'];
                }
            } else {
                if ($item_price_tax) {
                    $price = $this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax'));
                } else {
                    $price = $product_info['price'];
                }
            }

            $item['price'] = $this->currency->format($price, $currency, 0, false);

            $item['quantity'] = $product_info['quantity'];

            $items[] = $item;

            $data['ecommerce']['currency'] = $currency;
            $data['ecommerce']['value'] = $item['price'];
            $data['ecommerce']['items'] = $items;

            if ($this->config->get('analytics_ps_enhanced_measurement_implementation') === 'gtag') {
                $args['ps_enhanced_measurement'] = 'gtag("event", "' . $data['event'] . '", ' . json_encode($data['ecommerce'], JSON_PRETTY_PRINT | JSON_NUMERIC_CHECK) . ')';
            } else if ($this->config->get('analytics_ps_enhanced_measurement_implementation') === 'gtm') {
                $args['ps_enhanced_measurement'] = 'dataLayer.push({ ecommerce: null });' . PHP_EOL . 'dataLayer.push(' . json_encode($data, JSON_PRETTY_PRINT | JSON_NUMERIC_CHECK) . ')';
            }
        } else {
            $args['ps_enhanced_measurement'] = '';
        }

        $views = $this->model_extension_ps_enhanced_measurement_analytics_ps_enhanced_measurement->replaceCatalogViewProductProductViews($args);

        $template = $this->replaceViews($route, $template, $views);
    }

    public function eventCatalogViewProductSearchBefore(string &$route, array &$args, string &$template): void
    {
        if (!$this->config->get('analytics_ps_enhanced_measurement_status')) {
            return;
        }
    }

    public function eventCatalogViewProductCompareBefore(string &$route, array &$args, string &$template): void
    {
        if (!$this->config->get('analytics_ps_enhanced_measurement_status')) {
            return;
        }

        $this->load->model('extension/ps_enhanced_measurement/analytics/ps_enhanced_measurement');

        $views = $this->model_extension_ps_enhanced_measurement_analytics_ps_enhanced_measurement->replaceCompareAndWishlistViews($args);

        $template = $this->replaceViews($route, $template, $views);
    }

    public function eventCatalogViewProductThumbBefore(string &$route, array &$args, string &$template): void
    {
        if (!$this->config->get('analytics_ps_enhanced_measurement_status')) {
            return;
        }

        $this->load->model('extension/ps_enhanced_measurement/analytics/ps_enhanced_measurement');

        $views = $this->model_extension_ps_enhanced_measurement_analytics_ps_enhanced_measurement->replaceProductThumbViews($args);

        $template = $this->replaceViews($route, $template, $views);
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

    /**
     * Summary of _getCategoryType1
     *
     * @param int $product_id  $product_info['product_id']
     * @return array
     */
    private function _getCategoryType1(int $product_id)
    {
        $categories = $this->model_extension_ps_enhanced_measurement_analytics_ps_enhanced_measurement->getCategories($product_id);

        $result = [];

        foreach ($categories as $category_id) {
            $category_info = $this->model_extension_ps_enhanced_measurement_analytics_ps_enhanced_measurement->getCategoryType1($category_id);

            if ($category_info) {
                $result[] = $category_info['last_category_name'];
            }
        }

        return $result;
    }

    /**
     * Summary of _getCategoryType2
     *
     * @param int $product_id  $product_info['product_id']
     * @return array
     */
    private function _getCategoryType2(int $product_id)
    {
        $categories = $this->model_extension_ps_enhanced_measurement_analytics_ps_enhanced_measurement->getCategories($product_id);

        $result = [];

        foreach ($categories as $category_id) {
            $category_info = $this->model_extension_ps_enhanced_measurement_analytics_ps_enhanced_measurement->getCategoryType2($category_id);

            if ($category_info) {
                $result[] = ($category_info['path']) ? $category_info['path'] . ' > ' . $category_info['name'] : $category_info['name'];
            }
        }

        return $result;
    }

    /**
     * Summary of _getCategoryType3
     *
     * @param int $category_id
     * @return array
     */
    private function _getCategoryType3(int $category_id): array
    {
        $result = [];

        $category_infos = $this->model_extension_ps_enhanced_measurement_analytics_ps_enhanced_measurement->getCategoryType3($category_id);

        foreach ($category_infos as $category_info) {
            $result[] = $category_info['name'];
        }

        return $result;
    }

    private function _getCategoryType4(array $category_info): array
    {
        $result = [];

        $result[] = $category_info['name'];

        return $result;
    }

    private function _formatListId(string $string): string
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
}
