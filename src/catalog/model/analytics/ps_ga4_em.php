<?php
namespace Opencart\Catalog\Model\Extension\PsGa4Em\Analytics;
/**
 * Class PsGa4Em
 *
 * @package Opencart\Catalog\Model\Extension\PsGa4Em\Analytics
 */
class PsGa4Em extends \Opencart\System\Engine\Model
{
    /**
     * @param array $args
     *
     * @return array
     */
    public function replaceHeaderViews(array $args): array
    {
        $views = [];

        $views[] = [
            'search' => '<body>',
            'replace' => <<<HTML
<body>
<!-- Google Tag Manager (noscript) -->
<noscript>
    <iframe src="https://www.googletagmanager.com/ns.html?id={{ ps_ga4_em_gtm_id }}" height="0" width="0" style="display:none; visibility:hidden;"></iframe>
</noscript>
<!-- End Google Tag Manager (noscript) -->
HTML
        ];

        return $views;
    }
}
