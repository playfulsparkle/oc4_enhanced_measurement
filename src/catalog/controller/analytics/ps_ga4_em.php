<?php
namespace Opencart\Catalog\Controller\Extension\PsGa4Em\Analytics;
/**
 * Class PsGa4Em
 *
 * @package Opencart\Catalog\Controller\Extension\PsGa4Em\Analytics
 */
class PsGa4Em extends \Opencart\System\Engine\Controller
{
    public function index(): string
    {
        /**
         * Checks if Google Tag Manager (GTM) is enabled in the configuration.
         *
         * If disabled, the function returns an empty string, meaning no GTM script will be added to the page.
         */
        if (!$this->config->get('analytics_ps_ga4_em_status')) {
            return '';
        }

        /**
         * Retrieves GTM and GCM configuration settings from the system configuration.
         *
         * - `$gtm_id`: Unique ID for the Google Tag Manager container.
         * - `$gcm_status`: Boolean indicating if Google Consent Mode (GCM) is enabled.
         * - `$ad_storage`, `$ad_user_data`, `$ad_personalization`, `$analytics_storage`,
         *   `$functionality_storage`, `$personalization_storage`, `$security_storage`:
         *   Booleans determining storage access settings for different types of cookies/data.
         * - `$wait_for_update`: Integer representing delay (in milliseconds) before applying consent settings.
         * - `$ads_data_redaction`: Boolean setting for redacting ads data.
         * - `$url_passthrough`: Boolean to control URL passthrough setting for enhanced link tracking.
         */
        $gtm_id = $this->config->get('analytics_ps_ga4_em_gtm_id');
        $gcm_status = (bool) $this->config->get('analytics_ps_ga4_em_gcm_status');
        $ad_storage = (bool) $this->config->get('analytics_ps_ga4_em_ad_storage');
        $ad_user_data = (bool) $this->config->get('analytics_ps_ga4_em_ad_user_data');
        $ad_personalization = (bool) $this->config->get('analytics_ps_ga4_em_ad_personalization');
        $analytics_storage = (bool) $this->config->get('analytics_ps_ga4_em_analytics_storage');
        $functionality_storage = (bool) $this->config->get('analytics_ps_ga4_em_functionality_storage');
        $personalization_storage = (bool) $this->config->get('analytics_ps_ga4_em_personalization_storage');
        $security_storage = (bool) $this->config->get('analytics_ps_ga4_em_security_storage');
        $wait_for_update = (int) $this->config->get('analytics_ps_ga4_em_wait_for_update');
        $ads_data_redaction = (bool) $this->config->get('analytics_ps_ga4_em_ads_data_redaction');
        $url_passthrough = (bool) $this->config->get('analytics_ps_ga4_em_url_passthrough');

        $html = '';

        /**
         * If Google Consent Mode (GCM) is enabled, configure consent settings.
         *
         * Creates a `gcm_options` array, defining consent preferences for storage and data types. These include:
         * - `ad_storage`, `ad_user_data`, `ad_personalization`, `analytics_storage`,
         *   `functionality_storage`, `personalization_storage`, `security_storage`:
         *   Each is set to either "granted" or "denied" based on the corresponding configuration.
         * - If `$wait_for_update` is greater than 0, it adds a delay before applying these settings.
         *
         * Appends a JavaScript block to `$html` that initializes the `dataLayer`, defines the `gtag` function,
         * and sets consent using the `gcm_options` and `ads_data_redaction` and `url_passthrough` configurations.
         */
        if ($gcm_status) {
            $gcm_options = [
                'ad_storage' => $ad_storage ? 'granted' : 'denied',
                'ad_user_data' => $ad_user_data ? 'granted' : 'denied',
                'ad_personalization' => $ad_personalization ? 'granted' : 'denied',
                'analytics_storage' => $analytics_storage ? 'granted' : 'denied',
                'functionality_storage' => $functionality_storage ? 'granted' : 'denied',
                'personalization_storage' => $personalization_storage ? 'granted' : 'denied',
                'security_storage' => $security_storage ? 'granted' : 'denied',
            ];

            if ($wait_for_update > 0) {
                $gcm_options['wait_for_update'] = $wait_for_update;
            }

            $html .= '<script>
                window.dataLayer = window.dataLayer || [];
                function gtag() { dataLayer.push(arguments); }

                gtag("consent", "default", ' . json_encode($gcm_options, JSON_PRETTY_PRINT) . ');
                gtag("set", "ads_data_redaction", ' . ($ads_data_redaction ? 'true' : 'false') . ');
                gtag("set", "url_passthrough", ' . ($url_passthrough ? 'true' : 'false') . ');
            </script>';
        }

        /**
         * Appends the main GTM script block to `$html`.
         *
         * This block creates and inserts a script element into the page that loads GTM from `googletagmanager.com`.
         * - Uses the GTM ID retrieved from configuration.
         * - Initializes the dataLayer and sends a 'gtm.js' event to trigger GTM processing.
         */
        $html .= '
        <!-- Google Tag Manager -->
        <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({\'gtm.start\':
        new Date().getTime(),event:\'gtm.js\'});var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!=\'dataLayer\'?\'&l=\'+l:\'\';j.async=true;j.src=
        \'https://www.googletagmanager.com/gtm.js?id=\'+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,\'script\',\'dataLayer\',\'' . $gtm_id . '\');</script>
        <!-- End Google Tag Manager -->';

        return $html;
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
        if (!$this->config->get('analytics_ps_ga4_em_status')) {
            return;
        }

        $this->load->model('extension/ps_ga4_em/analytics/ps_ga4_em');

        $args['ps_ga4_em_gtm_id'] = $this->config->get('analytics_ps_ga4_em_gtm_id');

        $headerViews = $this->model_extension_ps_ga4_em_analytics_ps_ga4_em->replaceHeaderViews($args);

        $template = $this->replaceViews($route, $template, $headerViews);
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
