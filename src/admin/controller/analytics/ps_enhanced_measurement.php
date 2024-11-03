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

        $data['text_contact'] = sprintf($this->language->get('text_contact'), self::EXTENSION_EMAIL, self::EXTENSION_EMAIL, self::EXTENSION_DOC);

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
            $this->load->model('setting/event');

            $separator = version_compare(VERSION, '4.0.2.0', '>=') ? '.' : '|';

            if (version_compare(VERSION, '4.0.1.0', '>=')) {
                $this->model_setting_event->addEvent([
                    'code' => 'analytics_ps_enhanced_measurement',
                    'description' => '',
                    'trigger' => 'catalog/view/common/header/before',
                    'action' => 'extension/ps_enhanced_measurement/analytics/ps_enhanced_measurement' . $separator . 'eventCatalogViewCommonHeaderBefore',
                    'status' => '1',
                    'sort_order' => '0'
                ]);
            } else {
                $this->model_setting_event->addEvent(
                    'analytics_ps_enhanced_measurement',
                    'catalog/view/common/header/before',
                    'extension/ps_enhanced_measurement/analytics/ps_enhanced_measurement' . $separator . 'eventCatalogViewCommonHeaderBefore'
                );
            }
        }
    }

    public function uninstall(): void
    {
        if ($this->user->hasPermission('modify', 'extension/ps_enhanced_measurement/analytics/ps_enhanced_measurement')) {
            $this->load->model('setting/event');

            $this->model_setting_event->deleteEventByCode('analytics_ps_enhanced_measurement');
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

            $this->model_setting_event->deleteEventByCode('analytics_ps_enhanced_measurement');

            $separator = version_compare(VERSION, '4.0.2.0', '>=') ? '.' : '|';

            if (version_compare(VERSION, '4.0.1.0', '>=')) {
                $result = $this->model_setting_event->addEvent([
                    'code' => 'analytics_ps_enhanced_measurement',
                    'description' => '',
                    'trigger' => 'catalog/view/common/header/before',
                    'action' => 'extension/ps_enhanced_measurement/analytics/ps_enhanced_measurement' . $separator . 'eventCatalogViewCommonHeaderBefore',
                    'status' => '1',
                    'sort_order' => '0'
                ]);
            } else {
                $result = $this->model_setting_event->addEvent(
                    'analytics_ps_enhanced_measurement',
                    'catalog/view/common/header/before',
                    'extension/ps_enhanced_measurement/analytics/ps_enhanced_measurement' . $separator . 'eventCatalogViewCommonHeaderBefore'
                );
            }

            if ($result > 0) {
                $json['success'] = $this->language->get('text_success');
            } else {
                $json['error'] = $this->language->get('error_event');
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}
