<?php
namespace Opencart\Catalog\Model\Extension\PsEnhancedMeasurement\Analytics;
/**
 * Class PsEnhancedMeasurement
 *
 * @package Opencart\Catalog\Model\Extension\PsEnhancedMeasurement\Analytics
 */
class PsEnhancedMeasurement extends \Opencart\System\Engine\Model
{
    /**
     * @param array $args
     *
     * @return array
     */
    public function replaceCatalogViewCommonHeaderBefore(array $args): array
    {
        $views = [];

        $views[] = [
            'search' => '<body>',
            'replace' => <<<HTML
    <body>
    {% if ps_enhanced_measurement_status and (ps_enhanced_measurement_implementation == 'gtm') %}
    <!-- Google Tag Manager (noscript) -->
    <noscript>
        <iframe src="https://www.googletagmanager.com/ns.html?id={{ ps_enhanced_measurement_gtm_id }}" height="0" width="0" style="display:none; visibility:hidden;"></iframe>
    </noscript>
    <!-- End Google Tag Manager (noscript) -->
    {% endif %}
    HTML
        ];

        $views[] = [
            'search' => '</head>',
            'replace' => <<<HTML
    {% if ps_enhanced_measurement_status %}
    <script>
        var ps_dataLayer = {
            push: function(eventName, data) {
                {% if ps_enhanced_measurement_implementation == 'gtag' %}
                    gtag('event', eventName, data);
                {% elseif ps_enhanced_measurement_implementation == 'gtm' %}
                    dataLayer.push({ ecommerce: null });
                    dataLayer.push({ event: eventName, ...data });
                {% endif %}
            }
        };
    </script>
    {% endif %}
    </head>
    HTML
        ];

        return $views;
    }
}
