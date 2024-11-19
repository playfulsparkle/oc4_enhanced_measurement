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

        $ps_enhanced_measurement_script = 'extension/ps_enhanced_measurement/catalog/view/javascript/ps-enhanced-measurement.js';

        $args['scripts'][$ps_enhanced_measurement_script] = ['href' => $ps_enhanced_measurement_script];

        $args['ps_enhanced_measurement_status'] = $this->config->get('analytics_ps_enhanced_measurement_status');
        $args['ps_enhanced_measurement_implementation'] = $this->config->get('analytics_ps_enhanced_measurement_implementation');
        $args['ps_enhanced_measurement_gtm_id'] = $this->config->get('analytics_ps_enhanced_measurement_gtm_id');


        $this->load->language('extension/ps_enhanced_measurement/module/ps_enhanced_measurement');

        $this->load->model('extension/ps_enhanced_measurement/analytics/ps_enhanced_measurement');

        $headerViews = $this->model_extension_ps_enhanced_measurement_analytics_ps_enhanced_measurement->replaceCatalogViewCommonHeaderBefore($args);

        $template = $this->replaceViews($route, $template, $headerViews);
    }

    public function eventCatalogViewProductThumbBefore(string &$route, array &$args, string &$template): void
    {
        if (!$this->config->get('analytics_ps_enhanced_measurement_status')) {
            return;
        }


        $this->load->language('extension/ps_enhanced_measurement/module/ps_enhanced_measurement');

        $this->load->model('extension/ps_enhanced_measurement/analytics/ps_enhanced_measurement');

        $args['has_options'] = $this->model_extension_ps_enhanced_measurement_analytics_ps_enhanced_measurement->hasOptions($args['product_id']);

        $headerViews = $this->model_extension_ps_enhanced_measurement_analytics_ps_enhanced_measurement->replaceCatalogViewProductThumbBefore($args);

        $template = $this->replaceViews($route, $template, $headerViews);
    }

    public function eventCatalogViewProductCategoryBefore(string &$route, array &$args, string &$template): void
    {
        if (!$this->config->get('analytics_ps_enhanced_measurement_status')) {
            return;
        }


        $this->load->language('extension/ps_enhanced_measurement/module/ps_enhanced_measurement');

        $this->load->model('extension/ps_enhanced_measurement/analytics/ps_enhanced_measurement');
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
            $item_list_name = sprintf($this->language->get('text_x_products'), html_entity_decode($category_info['name'], ENT_QUOTES, 'UTF-8'));
            $item_list_id = $this->formatListId($item_list_name);

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

            $items = [];

            foreach ($products as $index => $product_info) {
                $item = [];

                $item['item_id'] = isset($product_info[$item_id_option]) && !empty($product_info[$item_id_option]) ? $this->formatListId($product_info[$item_id_option]) : $product_info['product_id'];
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

                $item['index'] = $index;

                $manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($product_info['manufacturer_id']);

                if ($manufacturer_info) {
                    $item['item_brand'] = $manufacturer_info['name'];
                }

                if ($item_category_option === 0) {
                    $categories = $this->getCategoryType1($product_info['product_id']);
                } else if ($item_category_option === 1) {
                    $categories = $this->getCategoryType2($product_info['product_id']);
                } else if ($item_category_option === 2) {
                    $categories = $this->getCategoryType3($category_id);
                } else if ($item_category_option === 3) {
                    $categories = $this->getCategoryType4($category_info);
                } else {
                    $categories = [];
                }

                $total_categories = count($categories);

                foreach ($categories as $category_index => $category_name) {
                    if ($total_categories === 0 || $category_index === 0) {
                        $item['item_category'] = $category_name;
                    } else {
                        $item['item_category' . ($category_index + 1)] = $category_name;
                    }
                }

                $item['item_list_id'] = $item_list_id;
                $item['item_list_name'] = $item_list_name;

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

                if ($product_info['minimum']) {
                    $item['minimum'] = $product_info['minimum'];
                } else {
                    $item['minimum'] = 1;
                }

                $items[(int) $product_info['product_id']] = $item;
            }


            $ps_view_item_list = [
                'ecommerce' => [
                    'item_list_id' => $item_list_id,
                    'item_list_name' => $item_list_name,
                    'items' => array_values($items),
                ],
            ];

            $args['ps_view_item_list'] = $items ? json_encode($ps_view_item_list, JSON_PRETTY_PRINT | JSON_NUMERIC_CHECK) : null;
        } else {
            $args['ps_view_item_list'] = null;
        }


        $ps_merge_items = [];

        foreach ($items as $product_id => $item) {
            unset($item['minimum']);

            $ps_merge_items['select_item_' . $product_id] = [
                'ecommerce' => [
                    'item_list_id' => $item_list_id,
                    'item_list_name' => $item_list_name,
                    'items' => [$item],
                ],
            ];
            $ps_merge_items['add_to_wishlist_' . $product_id] = [
                'ecommerce' => [
                    'currency' => $currency,
                    'value' => $item['price'],
                    'items' => [$item],
                ],
            ];
        }

        foreach ($items as $product_id => $item) {
            $item['quantity'] = $item['minimum'];

            unset($item['minimum']);

            $ps_merge_items['add_to_cart_' . $product_id] = [
                'ecommerce' => [
                    'currency' => $currency,
                    'value' => $item['price'],
                    'items' => [$item],
                ],
            ];
        }

        $args['ps_merge_items'] = $ps_merge_items ? json_encode($ps_merge_items, JSON_PRETTY_PRINT | JSON_NUMERIC_CHECK) : null;


        $views = $this->model_extension_ps_enhanced_measurement_analytics_ps_enhanced_measurement->replaceCatalogViewProductCategoryBefore($args);

        $template = $this->replaceViews($route, $template, $views);
    }

    public function eventCatalogViewProductSearchBefore(string &$route, array &$args, string &$template): void
    {
        if (!$this->config->get('analytics_ps_enhanced_measurement_status')) {
            return;
        }


        $this->load->language('extension/ps_enhanced_measurement/module/ps_enhanced_measurement');

        $this->load->model('extension/ps_enhanced_measurement/analytics/ps_enhanced_measurement');
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
            $sub_category = $this->request->get['sub_category'];
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

        if (isset($this->request->get['limit']) && (int) $this->request->get['limit']) {
            $limit = (int) $this->request->get['limit'];
        } else {
            $limit = $this->config->get('config_pagination');
        }


        if ($search || $tag) {
            if (isset($this->request->get['search'])) {
                $item_list_name = sprintf(
                    $this->language->get('text_x_products'),
                    $this->language->get('heading_title') . ' - ' . $this->request->get['search']
                );
                $item_list_id = $this->formatListId($item_list_name);
            } elseif (isset($this->request->get['tag'])) {
                $item_list_name = sprintf(
                    $this->language->get('text_x_products'),
                    $this->language->get('heading_title') . ' - ' . $this->language->get('heading_tag') . $this->request->get['tag']
                );
                $item_list_id = $this->formatListId($item_list_name);
            } else {
                $item_list_name = sprintf(
                    $this->language->get('text_x_products'),
                    $this->language->get('heading_title')
                );
                $item_list_id = $this->formatListId($item_list_name);
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

            $items = [];

            foreach ($products as $index => $product_info) {
                $item = [];

                $item['item_id'] = isset($product_info[$item_id_option]) && !empty($product_info[$item_id_option]) ? $this->formatListId($product_info[$item_id_option]) : $product_info['product_id'];
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

                $item['index'] = $index;

                $manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($product_info['manufacturer_id']);

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

                $item['item_list_id'] = $item_list_id;
                $item['item_list_name'] = $item_list_name;

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

                if ($product_info['minimum']) {
                    $item['minimum'] = $product_info['minimum'];
                } else {
                    $item['minimum'] = 1;
                }

                $items[(int) $product_info['product_id']] = $item;
            }


            $ps_view_item_list = [
                'ecommerce' => [
                    'item_list_id' => $item_list_id,
                    'item_list_name' => $item_list_name,
                    'items' => array_values($items),
                ],
            ];

            $args['ps_view_item_list'] = $items ? json_encode($ps_view_item_list, JSON_PRETTY_PRINT | JSON_NUMERIC_CHECK) : null;
        } else {
            $args['ps_view_item_list'] = null;
        }


        $ps_merge_items = [];

        foreach ($items as $product_id => $item) {
            unset($item['minimum']);

            $ps_merge_items['select_item_' . $product_id] = [
                'ecommerce' => [
                    'item_list_id' => $item_list_id,
                    'item_list_name' => $item_list_name,
                    'items' => [$item],
                ],
            ];
            $ps_merge_items['add_to_wishlist_' . $product_id] = [
                'ecommerce' => [
                    'currency' => $currency,
                    'value' => $item['price'],
                    'items' => [$item],
                ],
            ];
        }

        foreach ($items as $product_id => $item) {
            $item['quantity'] = $item['minimum'];

            unset($item['minimum']);

            $ps_merge_items['add_to_cart_' . $product_id] = [
                'ecommerce' => [
                    'currency' => $currency,
                    'value' => $item['price'],
                    'items' => [$item],
                ],
            ];
        }

        $args['ps_merge_items'] = $ps_merge_items ? json_encode($ps_merge_items, JSON_PRETTY_PRINT | JSON_NUMERIC_CHECK) : null;


        $views = $this->model_extension_ps_enhanced_measurement_analytics_ps_enhanced_measurement->replaceCatalogViewProductSearchBefore($args);

        $template = $this->replaceViews($route, $template, $views);
    }

    public function eventCatalogViewProductProductBefore(string &$route, array &$args, string &$template): void
    {
        if (!$this->config->get('analytics_ps_enhanced_measurement_status')) {
            return;
        }


        $this->load->language('extension/ps_enhanced_measurement/module/ps_enhanced_measurement');

        $this->load->model('extension/ps_enhanced_measurement/analytics/ps_enhanced_measurement');
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

        if (isset($this->request->get['product_id'])) {
            $product_id = (int) $this->request->get['product_id'];
        } else {
            $product_id = 0;
        }


        if (isset($this->request->get['path'])) {
            $parts = explode('_', (string) $this->request->get['path']);

            $args['ps_category_id'] = (int) array_pop($parts);
        } else if (isset($this->request->get['category_id'])) {
            $args['ps_category_id'] = (int) $this->request->get['category_id'];
        } else {
            $args['ps_category_id'] = 0;
        }


        $ps_merge_items = [];

        $product_info = $this->model_catalog_product->getProduct($product_id);

        if ($product_info) {
            $items = [];

            $item = [];

            $item['item_id'] = isset($product_info[$item_id_option]) && !empty($product_info[$item_id_option]) ? $this->formatListId($product_info[$item_id_option]) : $product_info['product_id'];
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
                $categories = $this->getCategoryType1($product_info['product_id']);
            } else if ($item_category_option === 1) {
                $categories = $this->getCategoryType2($product_info['product_id']);
            } else if ($item_category_option === 2 && $category_id) {
                $categories = $this->getCategoryType3($category_id);
            } else if ($item_category_option === 3 && $category_info) {
                $categories = $this->getCategoryType4($category_info);
            } else {
                $categories = [];
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

            if ($product_info['minimum']) {
                $item['minimum'] = $product_info['minimum'];
            } else {
                $item['minimum'] = 1;
            }

            $items[(int) $product_info['product_id']] = $item;


            $ps_view_item = [
                'ecommerce' => [
                    'currency' => $currency,
                    'value' => $item['price'],
                    'items' => array_values($items),
                ],
            ];

            $args['ps_view_item'] = $items ? json_encode($ps_view_item, JSON_PRETTY_PRINT | JSON_NUMERIC_CHECK) : null;


            foreach ($items as $product_id => $item) { // Add the current product to the merge stack
                unset($item['minimum']);

                $ps_merge_items['add_to_wishlist_' . $product_id] = [
                    'ecommerce' => [
                        'currency' => $currency,
                        'value' => $item['price'],
                        'items' => [$item],
                    ],
                ];
            }

            foreach ($items as $product_id => $item) {
                $item['quantity'] = $item['minimum'];

                unset($item['minimum']);

                $ps_merge_items['add_to_cart_' . $product_id] = [
                    'ecommerce' => [
                        'currency' => $currency,
                        'value' => $item['price'],
                        'items' => [$item],
                    ],
                ];
            }


            $item_list_name = sprintf(
                $this->language->get('text_x_related_products'),
                html_entity_decode($product_info['name'], ENT_QUOTES, 'UTF-8')
            );
            $item_list_id = $this->formatListId($item_list_name);


            $products = $this->model_catalog_product->getRelated($product_id);

            $items = [];

            foreach ($products as $index => $product_info) {
                $item = [];

                $item['item_id'] = isset($product_info[$item_id_option]) && !empty($product_info[$item_id_option]) ? $this->formatListId($product_info[$item_id_option]) : $product_info['product_id'];
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

                $item['index'] = $index;

                $manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($product_info['manufacturer_id']);

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

                $item['item_list_id'] = $item_list_id;
                $item['item_list_name'] = $item_list_name;

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

                if ($product_info['minimum']) {
                    $item['minimum'] = $product_info['minimum'];
                } else {
                    $item['minimum'] = 1;
                }

                $items[(int) $product_info['product_id']] = $item;
            }


            foreach ($items as $product_id => $item) { // Add related prodcuts to the merge stack
                unset($item['minimum']);

                $ps_merge_items['select_item_' . $product_id] = [
                    'ecommerce' => [
                        'item_list_id' => $item_list_id,
                        'item_list_name' => $item_list_name,
                        'items' => [$item],
                    ],
                ];
                $ps_merge_items['add_to_wishlist_' . $product_id] = [
                    'ecommerce' => [
                        'currency' => $currency,
                        'value' => $item['price'],
                        'items' => [$item],
                    ],
                ];
            }

            foreach ($items as $product_id => $item) {
                $item['quantity'] = $item['minimum'];

                unset($item['minimum']);

                $ps_merge_items['add_to_cart_' . $product_id] = [
                    'ecommerce' => [
                        'currency' => $currency,
                        'value' => $item['price'],
                        'items' => [$item],
                    ],
                ];
            }

            $args['ps_merge_items'] = $ps_merge_items ? json_encode($ps_merge_items, JSON_PRETTY_PRINT | JSON_NUMERIC_CHECK) : null;
        } else {
            $args['ps_view_item'] = null;
            $args['ps_merge_items'] = null;
        }


        $views = $this->model_extension_ps_enhanced_measurement_analytics_ps_enhanced_measurement->replaceCatalogViewProductProductBefore($args);

        $template = $this->replaceViews($route, $template, $views);
    }

    public function eventCatalogControllerCheckoutCartAddAfter(string &$route, array &$args, string &$output = null)
    {
        if (!$this->config->get('analytics_ps_enhanced_measurement_status')) {
            return;
        }

        $json_response = json_decode($this->response->getOutput(), true);

        if (empty($json_response) || !isset($json_response['success'])) {
            return;
        }


        $this->load->language('extension/ps_enhanced_measurement/module/ps_enhanced_measurement');
        $this->load->language('checkout/cart');

        $this->load->model('extension/ps_enhanced_measurement/analytics/ps_enhanced_measurement');
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


        $product_info = $this->model_catalog_product->getProduct($this->request->post['product_id']);

        $quantity = isset($this->request->post['quantity']) ? (int) $this->request->post['quantity'] : 1;
        $options = isset($this->request->post['option']) ? array_filter($this->request->post['option']) : [];

        if ($product_info) {
            $item = [];

            $item['item_id'] = isset($product_info[$item_id_option]) && !empty($product_info[$item_id_option]) ? $this->formatListId($product_info[$item_id_option]) : $product_info['product_id'];
            $item['item_name'] = html_entity_decode($product_info['name'], ENT_QUOTES, 'UTF-8');
            $item['affiliation'] = $affiliation;

            if (isset($this->session->data['coupon'])) {
                $item['coupon'] = $this->session->data['coupon'];
            }

            // $item['discount'] = 0;

            $item['index'] = 0;

            $manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($product_info['manufacturer_id']);

            if ($manufacturer_info) {
                $item['item_brand'] = $manufacturer_info['name'];
            }

            if (isset($this->request->post['category_id'])) {
                $category_id = (int) $this->request->post['category_id'];
            } else {
                $category_id = 0;
            }

            $category_info = $this->model_catalog_category->getCategory($category_id);

            if ($item_category_option === 0) {
                $categories = $this->getCategoryType1($product_info['product_id']);
            } else if ($item_category_option === 1) {
                $categories = $this->getCategoryType2($product_info['product_id']);
            } else if ($item_category_option === 2 && $category_id) {
                $categories = $this->getCategoryType3($category_id);
            } else if ($item_category_option === 3 && $category_info) {
                $categories = $this->getCategoryType4($category_info);
            } else {
                $categories = [];
            }

            $total_categories = count($categories);

            foreach ($categories as $category_index => $category_name) {
                if ($total_categories === 0 || $category_index === 0) {
                    $item['item_category'] = $category_name;
                } else {
                    $item['item_category' . ($category_index + 1)] = $category_name;
                }
            }

            if ($product_info['variant']) {
                foreach ($product_info['variant'] as $key => $value) {
                    $options[$key] = $value;
                }
            }


            $product_option_info = $this->model_extension_ps_enhanced_measurement_analytics_ps_enhanced_measurement->getProductOptionInfo($options, $product_info['product_id']);

            $option_price = $product_option_info['option_price'];
            $variant = $product_option_info['variant'];

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
                $subscription_plan_id = (int) $this->request->post['subscription_plan_id'];

                $product_subscription_info = $this->model_extension_ps_enhanced_measurement_analytics_ps_enhanced_measurement->getProductScubscription($product_info['product_id'], $subscription_plan_id);

                if ($product_subscription_info) {
                    $base_price = $product_subscription_info['price'];

                    if ($product_subscription_info['trial_status']) {
                        $base_price = $product_subscription_info['trial_price'];
                    }

                    if ($subscription_description = $this->getProductSubscriptionDescription($product_subscription_info, $product_info['tax_class_id'], $currency)) {
                        $variant[] = $subscription_description;
                    }
                }
            }

            if ($variant) {
                $item['item_variant'] = implode(', ', $variant);
            }

            if ($location_id) {
                $item['location_id'] = $location_id;
            }

            if ($item_price_tax) {
                $price = $this->tax->calculate(($base_price + $option_price), $product_info['tax_class_id'], $this->config->get('config_tax'));
                $total = $this->tax->calculate(($base_price + $option_price) * $quantity, $product_info['tax_class_id'], $this->config->get('config_tax'));
            } else {
                $price = ($base_price + $option_price);
                $total = ($base_price + $option_price) * $quantity;
            }

            $item['price'] = $this->currency->format($price, $currency, 0, false);

            $item['quantity'] = $quantity;

            $json_response['add_to_cart'] = [
                'ecommerce' => [
                    'currency' => $currency,
                    'value' => $this->currency->format($total, $currency, 0, false),
                    'items' => [$item],
                ],
            ];

            $this->response->setOutput(json_encode($json_response, JSON_PRETTY_PRINT | JSON_NUMERIC_CHECK));
        }
    }

    public function eventCatalogViewCheckoutCartBefore(string &$route, array &$args, string &$template): void
    {
        if (!$this->config->get('analytics_ps_enhanced_measurement_status')) {
            return;
        }


        $this->load->language('extension/ps_enhanced_measurement/module/ps_enhanced_measurement');

        $this->load->model('extension/ps_enhanced_measurement/analytics/ps_enhanced_measurement');
        $this->load->model('catalog/category');
        $this->load->model('catalog/manufacturer');
        $this->load->model('catalog/product');
        $this->load->model('checkout/cart');


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


        $products = $this->model_checkout_cart->getProducts();

        $items = [];
        $total_price = 0;

        foreach ($products as $index => $product_info) {
            $item = [];

            $item['item_id'] = isset($product_info[$item_id_option]) && !empty($product_info[$item_id_option]) ? $this->formatListId($product_info[$item_id_option]) : $product_info['product_id'];
            $item['item_name'] = html_entity_decode($product_info['name'], ENT_QUOTES, 'UTF-8');
            $item['affiliation'] = $affiliation;

            if (isset($this->session->data['coupon'])) {
                $item['coupon'] = $this->session->data['coupon'];
            }

            $item['index'] = $index;

            $manufacturer_info = $this->model_extension_ps_enhanced_measurement_analytics_ps_enhanced_measurement->getManufacturerNameByProductId($product_info['product_id']);

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

            $variant = [];

            foreach ($product_info['option'] as $option) {
                $variant[] = html_entity_decode($option['name'] . ': ' . $option['value'], ENT_QUOTES, 'UTF-8');
            }

            if ($product_info['subscription'] && $subscription_description = $this->getProductSubscriptionDescription($product_info['subscription'], $product_info['tax_class_id'], $currency)) {
                $variant[] = $subscription_description;
            }

            if ($variant) {
                $item['item_variant'] = implode(', ', $variant);
            }

            if ($location_id) {
                $item['location_id'] = $location_id;
            }

            if ($item_price_tax) {
                $price = $this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax'));
            } else {
                $price = $product_info['price'];
            }

            if ($item_price_tax) {
                $total_price += $this->tax->calculate($product_info['total'], $product_info['tax_class_id'], $this->config->get('config_tax'));
            } else {
                $total_price += $product_info['total'];
            }

            $item['price'] = $this->currency->format($price, $currency, 0, false);

            $item['quantity'] = $product_info['quantity'];

            $items[(int) $product_info['cart_id']] = $item;
        }


        $ps_view_cart = [
            'ecommerce' => [
                'currency' => $currency,
                'value' => $this->currency->format($total_price, $currency, 0, false),
                'items' => array_values($items),
            ],
        ];

        $args['ps_view_cart'] = $items ? json_encode($ps_view_cart, JSON_PRETTY_PRINT | JSON_NUMERIC_CHECK) : null;


        $views = $this->model_extension_ps_enhanced_measurement_analytics_ps_enhanced_measurement->replaceCatalogViewCheckoutCartBefore($args);

        $template = $this->replaceViews($route, $template, $views);
    }

    public function eventCatalogViewCheckoutCartListBefore(string &$route, array &$args, string &$template): void
    {
        if (!$this->config->get('analytics_ps_enhanced_measurement_status')) {
            return;
        }


        $this->load->language('extension/ps_enhanced_measurement/module/ps_enhanced_measurement');
        $this->load->language('checkout/cart');

        $this->load->model('extension/ps_enhanced_measurement/analytics/ps_enhanced_measurement');
        $this->load->model('catalog/category');
        $this->load->model('catalog/manufacturer');
        $this->load->model('catalog/product');
        $this->load->model('checkout/cart');


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


        $item_list_name = sprintf(
            $this->language->get('text_x_products'),
            $this->language->get('heading_title')
        );
        $item_list_id = $this->formatListId($item_list_name);


        $products = $this->model_checkout_cart->getProducts();

        $items = [];

        foreach ($products as $index => $product_info) {
            $item = [];

            $item['item_id'] = isset($product_info[$item_id_option]) && !empty($product_info[$item_id_option]) ? $this->formatListId($product_info[$item_id_option]) : $product_info['product_id'];
            $item['item_name'] = html_entity_decode($product_info['name'], ENT_QUOTES, 'UTF-8');
            $item['affiliation'] = $affiliation;

            if (isset($this->session->data['coupon'])) {
                $item['coupon'] = $this->session->data['coupon'];
            }

            $item['index'] = $index;

            $manufacturer_info = $this->model_extension_ps_enhanced_measurement_analytics_ps_enhanced_measurement->getManufacturerNameByProductId($product_info['product_id']);

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

            $item['item_list_id'] = $item_list_id;
            $item['item_list_name'] = $item_list_name;

            $variant = [];

            foreach ($product_info['option'] as $option) {
                $variant[] = html_entity_decode($option['name'] . ': ' . $option['value'], ENT_QUOTES, 'UTF-8');
            }

            if ($product_info['subscription'] && $subscription_description = $this->getProductSubscriptionDescription($product_info['subscription'], $product_info['tax_class_id'], $currency)) {
                $variant[] = $subscription_description;
            }

            if ($variant) {
                $item['item_variant'] = implode(', ', $variant);
            }

            if ($location_id) {
                $item['location_id'] = $location_id;
            }

            if ($item_price_tax) {
                $price = $this->tax->calculate($product_info['price'] * $product_info['quantity'], $product_info['tax_class_id'], $this->config->get('config_tax'));
                $single_price = $this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax'));
            } else {
                $price = $product_info['price'] * $product_info['quantity'];
                $single_price = $product_info['price'];
            }

            $item['price'] = $this->currency->format($price, $currency, 0, false);
            $item['single_price'] = $this->currency->format($single_price, $currency, 0, false);

            $item['quantity'] = $product_info['quantity'];

            if ($product_info['minimum']) {
                $item['minimum'] = $product_info['minimum'];
            } else {
                $item['minimum'] = 1;
            }

            $items[(int) $product_info['cart_id']] = $item;
        }


        $ps_merge_items = [];

        foreach ($items as $cart_id => $item) {
            unset($item['single_price'], $item['minimum']);

            $ps_merge_items['select_item_' . $cart_id] = [
                'ecommerce' => [
                    'item_list_id' => $item_list_id,
                    'item_list_name' => $item_list_name,
                    'items' => [$item],
                ],
            ];
            $ps_merge_items['remove_from_cart_' . $cart_id] = [
                'ecommerce' => [
                    'currency' => $currency,
                    'value' => $item['price'],
                    'items' => [$item],
                ],
            ];
        }

        foreach ($items as $cart_id => $item) {
            $item['price'] = $item['single_price'];
            $item['quantity'] = $item['minimum'];

            unset($item['single_price'], $item['minimum']);

            $ps_merge_items['add_to_cart_' . $cart_id] = [
                'ecommerce' => [
                    'currency' => $currency,
                    'value' => $item['price'],
                    'items' => [$item],
                ],
            ];
        }

        $args['ps_merge_items'] = $ps_merge_items ? json_encode($ps_merge_items, JSON_PRETTY_PRINT | JSON_NUMERIC_CHECK) : null;


        $views = $this->model_extension_ps_enhanced_measurement_analytics_ps_enhanced_measurement->replaceCatalogViewCheckoutCartListBefore($args);

        $template = $this->replaceViews($route, $template, $views);
    }

    public function eventCatalogViewCheckoutCartInfoBefore(string &$route, array &$args, string &$template): void
    {
        if (!$this->config->get('analytics_ps_enhanced_measurement_status')) {
            return;
        }


        $this->load->language('extension/ps_enhanced_measurement/module/ps_enhanced_measurement');
        $this->load->language('checkout/cart');

        $this->load->model('extension/ps_enhanced_measurement/analytics/ps_enhanced_measurement');
        $this->load->model('catalog/category');
        $this->load->model('catalog/manufacturer');
        $this->load->model('catalog/product');
        $this->load->model('checkout/cart');


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


        $item_list_name = sprintf(
            $this->language->get('text_x_products'),
            $this->language->get('heading_title')
        );
        $item_list_id = $this->formatListId($item_list_name);


        $products = $this->model_checkout_cart->getProducts();

        $items = [];

        foreach ($products as $index => $product_info) {
            $item = [];

            $item['item_id'] = isset($product_info[$item_id_option]) && !empty($product_info[$item_id_option]) ? $this->formatListId($product_info[$item_id_option]) : $product_info['product_id'];
            $item['item_name'] = html_entity_decode($product_info['name'], ENT_QUOTES, 'UTF-8');
            $item['affiliation'] = $affiliation;

            if (isset($this->session->data['coupon'])) {
                $item['coupon'] = $this->session->data['coupon'];
            }

            $item['index'] = $index;

            $manufacturer_info = $this->model_extension_ps_enhanced_measurement_analytics_ps_enhanced_measurement->getManufacturerNameByProductId($product_info['product_id']);

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

            $item['item_list_id'] = $item_list_id;
            $item['item_list_name'] = $item_list_name;

            $variant = [];

            foreach ($product_info['option'] as $option) {
                $variant[] = html_entity_decode($option['name'] . ': ' . $option['value'], ENT_QUOTES, 'UTF-8');
            }

            if ($product_info['subscription'] && $subscription_description = $this->getProductSubscriptionDescription($product_info['subscription'], $product_info['tax_class_id'], $currency)) {
                $variant[] = $subscription_description;
            }

            if ($variant) {
                $item['item_variant'] = implode(', ', $variant);
            }

            if ($location_id) {
                $item['location_id'] = $location_id;
            }

            if ($item_price_tax) {
                $price = $this->tax->calculate($product_info['price'] * $product_info['quantity'], $product_info['tax_class_id'], $this->config->get('config_tax'));
            } else {
                $price = $product_info['price'] * $product_info['quantity'];
            }

            $item['price'] = $this->currency->format($price, $currency, 0, false);

            $item['quantity'] = $product_info['quantity'];

            $items[(int) $product_info['cart_id']] = $item;
        }


        $ps_merge_items = [];

        foreach ($items as $cart_id => $item) {
            $ps_merge_items['select_item_' . $cart_id] = [
                'ecommerce' => [
                    'item_list_id' => $item_list_id,
                    'item_list_name' => $item_list_name,
                    'items' => [$item],
                ],
            ];
            $ps_merge_items['remove_from_cart_' . $cart_id] = [
                'ecommerce' => [
                    'currency' => $currency,
                    'value' => $item['price'],
                    'items' => [$item],
                ],
            ];
        }

        $args['ps_merge_items'] = $ps_merge_items ? json_encode($ps_merge_items, JSON_PRETTY_PRINT | JSON_NUMERIC_CHECK) : null;


        $views = $this->model_extension_ps_enhanced_measurement_analytics_ps_enhanced_measurement->replaceCatalogViewCheckoutCartInfoBefore($args);

        $template = $this->replaceViews($route, $template, $views);
    }

    public function eventCatalogViewExtensionOpencartModuleBestsellerAfter(string &$route, array &$args, string &$output): void
    {
        if (!$this->config->get('analytics_ps_enhanced_measurement_status')) {
            return;
        }


        $this->load->language('extension/ps_enhanced_measurement/module/ps_enhanced_measurement');

        $this->load->model('extension/opencart/module/bestseller');
        $this->load->model('catalog/manufacturer');


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


        $setting = isset($args[0]) ? $args[0] : ['limit' => 0, 'name' => ''];

        $products = $this->model_extension_opencart_module_bestseller->getBestSellers($setting['limit']);

        if ($products) {
            $item_list_name = sprintf(
                $this->language->get('text_x_products'),
                html_entity_decode($setting['name'], ENT_QUOTES, 'UTF-8')
            );
            $item_list_id = $this->formatListId($item_list_name);

            $items = [];

            foreach ($products as $index => $product_info) {
                $item = [];

                $item['item_id'] = isset($product_info[$item_id_option]) && !empty($product_info[$item_id_option]) ? $this->formatListId($product_info[$item_id_option]) : $product_info['product_id'];
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

                $item['index'] = $index;

                $manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($product_info['manufacturer_id']);

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

                $item['item_list_id'] = $item_list_id;
                $item['item_list_name'] = $item_list_name;

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

                if ($product_info['minimum']) {
                    $item['minimum'] = $product_info['minimum'];
                } else {
                    $item['minimum'] = 1;
                }

                $items[(int) $product_info['product_id']] = $item;
            }


            $ps_merge_items = [];

            foreach ($items as $product_id => $item) {
                unset($item['minimum']);

                $ps_merge_items['select_item_' . $product_id] = [
                    'ecommerce' => [
                        'item_list_id' => $item_list_id,
                        'item_list_name' => $item_list_name,
                        'items' => [$item],
                    ],
                ];
                $ps_merge_items['add_to_wishlist_' . $product_id] = [
                    'ecommerce' => [
                        'currency' => $currency,
                        'value' => $item['price'],
                        'items' => [$item],
                    ],
                ];
            }

            foreach ($items as $product_id => $item) {
                $item['quantity'] = $item['minimum'];

                unset($item['minimum']);

                $ps_merge_items['add_to_cart_' . $product_id] = [
                    'ecommerce' => [
                        'currency' => $currency,
                        'value' => $item['price'],
                        'items' => [$item],
                    ],
                ];
            }

            if ($items) {
                $output = '<script>ps_dataLayer.merge(' . PHP_EOL . json_encode($ps_merge_items, JSON_PRETTY_PRINT | JSON_NUMERIC_CHECK) . PHP_EOL . ');</script>' . PHP_EOL . $output;
            }
        }
    }

    public function eventCatalogViewExtensionOpencartModuleFeaturedAfter(string &$route, array &$args, string &$output): void
    {
        if (!$this->config->get('analytics_ps_enhanced_measurement_status')) {
            return;
        }


        $this->load->language('extension/ps_enhanced_measurement/module/ps_enhanced_measurement');

        $this->load->model('catalog/product');
        $this->load->model('catalog/manufacturer');


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


        $setting = isset($args[0]) ? $args[0] : ['limit' => 0, 'name' => ''];

        if (!empty($setting['product'])) {
            $products = [];

            foreach ($setting['product'] as $product_id) {
                $product_info = $this->model_catalog_product->getProduct($product_id);

                if ($product_info) {
                    $products[] = $product_info;
                }
            }

            $item_list_name = sprintf(
                $this->language->get('text_x_products'),
                html_entity_decode($setting['name'], ENT_QUOTES, 'UTF-8')
            );
            $item_list_id = $this->formatListId($item_list_name);

            $items = [];

            foreach ($products as $index => $product_info) {
                $item = [];

                $item['item_id'] = isset($product_info[$item_id_option]) && !empty($product_info[$item_id_option]) ? $this->formatListId($product_info[$item_id_option]) : $product_info['product_id'];
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

                $item['index'] = $index;

                $manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($product_info['manufacturer_id']);

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

                $item['item_list_id'] = $item_list_id;
                $item['item_list_name'] = $item_list_name;

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

                if ($product_info['minimum']) {
                    $item['minimum'] = $product_info['minimum'];
                } else {
                    $item['minimum'] = 1;
                }

                $items[(int) $product_info['product_id']] = $item;
            }


            $ps_merge_items = [];

            foreach ($items as $product_id => $item) {
                unset($item['minimum']);

                $ps_merge_items['select_item_' . $product_id] = [
                    'ecommerce' => [
                        'item_list_id' => $item_list_id,
                        'item_list_name' => $item_list_name,
                        'items' => [$item],
                    ],
                ];
                $ps_merge_items['add_to_wishlist_' . $product_id] = [
                    'ecommerce' => [
                        'currency' => $currency,
                        'value' => $item['price'],
                        'items' => [$item],
                    ],
                ];
            }

            foreach ($items as $product_id => $item) {
                $item['quantity'] = $item['minimum'];

                unset($item['minimum']);

                $ps_merge_items['add_to_cart_' . $product_id] = [
                    'ecommerce' => [
                        'currency' => $currency,
                        'value' => $item['price'],
                        'items' => [$item],
                    ],
                ];
            }

            if ($items) {
                $output = '<script>ps_dataLayer.merge(' . PHP_EOL . json_encode($ps_merge_items, JSON_PRETTY_PRINT | JSON_NUMERIC_CHECK) . PHP_EOL . ');</script>' . PHP_EOL . $output;
            }
        }
    }

    public function eventCatalogViewExtensionOpencartModuleLatestAfter(string &$route, array &$args, string &$output): void
    {
        if (!$this->config->get('analytics_ps_enhanced_measurement_status')) {
            return;
        }


        $this->load->language('extension/ps_enhanced_measurement/module/ps_enhanced_measurement');

        $this->load->model('extension/opencart/module/latest');
        $this->load->model('catalog/manufacturer');


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


        $setting = isset($args[0]) ? $args[0] : ['limit' => 0, 'name' => ''];

        $products = $this->model_extension_opencart_module_latest->getLatest($setting['limit']);

        if ($products) {
            $item_list_name = sprintf(
                $this->language->get('text_x_products'),
                html_entity_decode($setting['name'], ENT_QUOTES, 'UTF-8')
            );
            $item_list_id = $this->formatListId($item_list_name);

            $items = [];

            foreach ($products as $index => $product_info) {
                $item = [];

                $item['item_id'] = isset($product_info[$item_id_option]) && !empty($product_info[$item_id_option]) ? $this->formatListId($product_info[$item_id_option]) : $product_info['product_id'];
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

                $item['index'] = $index;

                $manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($product_info['manufacturer_id']);

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

                $item['item_list_id'] = $item_list_id;
                $item['item_list_name'] = $item_list_name;

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

                if ($product_info['minimum']) {
                    $item['minimum'] = $product_info['minimum'];
                } else {
                    $item['minimum'] = 1;
                }

                $items[(int) $product_info['product_id']] = $item;
            }


            $ps_merge_items = [];

            foreach ($items as $product_id => $item) {
                unset($item['minimum']);

                $ps_merge_items['select_item_' . $product_id] = [
                    'ecommerce' => [
                        'item_list_id' => $item_list_id,
                        'item_list_name' => $item_list_name,
                        'items' => [$item],
                    ],
                ];
                $ps_merge_items['add_to_wishlist_' . $product_id] = [
                    'ecommerce' => [
                        'currency' => $currency,
                        'value' => $item['price'],
                        'items' => [$item],
                    ],
                ];
            }

            foreach ($items as $product_id => $item) {
                $item['quantity'] = $item['minimum'];

                unset($item['minimum']);

                $ps_merge_items['add_to_cart_' . $product_id] = [
                    'ecommerce' => [
                        'currency' => $currency,
                        'value' => $item['price'],
                        'items' => [$item],
                    ],
                ];
            }

            if ($items) {
                $output = '<script>ps_dataLayer.merge(' . PHP_EOL . json_encode($ps_merge_items, JSON_PRETTY_PRINT | JSON_NUMERIC_CHECK) . PHP_EOL . ');</script>' . PHP_EOL . $output;
            }
        }
    }

    public function eventCatalogViewExtensionOpencartModuleSpecialAfter(string &$route, array &$args, string &$output): void
    {
        if (!$this->config->get('analytics_ps_enhanced_measurement_status')) {
            return;
        }


        $this->load->language('extension/ps_enhanced_measurement/module/ps_enhanced_measurement');

        $this->load->model('catalog/product');
        $this->load->model('catalog/manufacturer');


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


        $setting = isset($args[0]) ? $args[0] : ['limit' => 0, 'name' => ''];

        $specials_filter_data = [
            'sort' => 'pd.name',
            'order' => 'ASC',
            'start' => 0,
            'limit' => $setting['limit']
        ];

        $products = $this->model_catalog_product->getSpecials($specials_filter_data);

        if ($products) {
            $item_list_name = sprintf(
                $this->language->get('text_x_products'),
                html_entity_decode($setting['name'], ENT_QUOTES, 'UTF-8')
            );
            $item_list_id = $this->formatListId($item_list_name);

            $items = [];

            foreach ($products as $index => $product_info) {
                $item = [];

                $item['item_id'] = isset($product_info[$item_id_option]) && !empty($product_info[$item_id_option]) ? $this->formatListId($product_info[$item_id_option]) : $product_info['product_id'];
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

                $item['index'] = $index;

                $manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($product_info['manufacturer_id']);

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

                $item['item_list_id'] = $item_list_id;
                $item['item_list_name'] = $item_list_name;

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

                if ($product_info['minimum']) {
                    $item['minimum'] = $product_info['minimum'];
                } else {
                    $item['minimum'] = 1;
                }

                $items[(int) $product_info['product_id']] = $item;
            }


            $ps_merge_items = [];

            foreach ($items as $product_id => $item) {
                unset($item['minimum']);

                $ps_merge_items['select_item_' . $product_id] = [
                    'ecommerce' => [
                        'item_list_id' => $item_list_id,
                        'item_list_name' => $item_list_name,
                        'items' => [$item],
                    ],
                ];
                $ps_merge_items['add_to_wishlist_' . $product_id] = [
                    'ecommerce' => [
                        'currency' => $currency,
                        'value' => $item['price'],
                        'items' => [$item],
                    ],
                ];
            }

            foreach ($items as $product_id => $item) {
                $item['quantity'] = $item['minimum'];

                unset($item['minimum']);

                $ps_merge_items['add_to_cart_' . $product_id] = [
                    'ecommerce' => [
                        'currency' => $currency,
                        'value' => $item['price'],
                        'items' => [$item],
                    ],
                ];
            }

            if ($items) {
                $output = '<script>ps_dataLayer.merge(' . PHP_EOL . json_encode($ps_merge_items, JSON_PRETTY_PRINT | JSON_NUMERIC_CHECK) . PHP_EOL . ');</script>' . PHP_EOL . $output;
            }
        }
    }

    protected function getProductSubscriptionDescription(array $product_subscription_info, int $taxClassId, string $currency)
    {
        $subscription_description = '';

        if ($product_subscription_info['trial_status']) {
            $trial_price = $this->currency->format($this->tax->calculate($product_subscription_info['trial_price'], $taxClassId, $this->config->get('config_tax')), $currency);
            $trial_cycle = $product_subscription_info['trial_cycle'];
            $trial_frequency = $this->language->get('text_' . $product_subscription_info['trial_frequency']);
            $trial_duration = $product_subscription_info['trial_duration'];

            $subscription_description .= sprintf($this->language->get('text_subscription_trial'), $trial_price, $trial_cycle, $trial_frequency, $trial_duration);
        }

        $subscription_price = $this->currency->format($this->tax->calculate($product_subscription_info['price'], $taxClassId, $this->config->get('config_tax')), $currency);

        $subscription_cycle = $product_subscription_info['cycle'];
        $subscription_frequency = $this->language->get('text_' . $product_subscription_info['frequency']);
        $subscription_duration = $product_subscription_info['duration'];

        if ($subscription_duration) {
            $subscription_description .= sprintf($this->language->get('text_subscription_duration'), $subscription_price, $subscription_cycle, $subscription_frequency, $subscription_duration);
        } else {
            $subscription_description .= sprintf($this->language->get('text_subscription_cancel'), $subscription_price, $subscription_cycle, $subscription_frequency);
        }

        return $subscription_description;
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

    protected function getCategoryType3(int $category_id): array
    {
        $result = [];

        $category_infos = $this->model_extension_ps_enhanced_measurement_analytics_ps_enhanced_measurement->getCategoryType3($category_id);

        foreach ($category_infos as $category_info) {
            $result[] = html_entity_decode($category_info['name'], ENT_QUOTES, 'UTF-8');
        }

        return $result;
    }

    protected function getCategoryType4(array $category_info): array
    {
        $result = [];

        $result[] = html_entity_decode($category_info['name'], ENT_QUOTES, 'UTF-8');

        return $result;
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
