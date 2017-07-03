<?php

/**
 * @author TJ Draper <tj@buzzingpixel.com>
 * @copyright 2017 BuzzingPixel, LLC
 */

use EllisLab\ExpressionEngine\Service\Model\Query\Builder as QueryBuilder;
use EllisLab\ExpressionEngine\Model\Addon\Extension as ExtensionRecord;
use EllisLab\ExpressionEngine\Core\Provider;

/**
 * Class Cp_inject_ext
 */
class Cp_inject_ext
{
    /** @var string $version */
    public $version = CP_INJECT_VER;

    /** @var array $hooks */
    private $hooks = array(
        'cp_css_end',
        'cp_js_end'
    );

    /**
     * Install extension
     */
    public function activate_extension()
    {
        foreach ($this->hooks as $hook) {
            $this->installUpdateHook($hook);
        }
    }

    /**
     * Uninstall extension
     */
    public function disable_extension()
    {
        // Remove all extensions with our class
        /** @var QueryBuilder $extRecords */
        $extRecords = ee('Model')->get('Extension');
        $extRecords->filter('class', __CLASS__);
        $extRecords = $extRecords->all();

        // If there are records, delete them
        if ($extRecords->count()) {
            $extRecords->delete();
        }
    }

    /**
     * Update extension
     * @param string $current
     * @return bool
     */
    public function update_extension($current = '')
    {
        // Get addon info
        /* @var Provider $addOnInfo */
        $addOnInfo = ee('Addon')->get('cp_inject');

        // Check if updating is needed
        if ($current === $addOnInfo->get('version')) {
            return false;
        }


        /**
         * Standard updates
         */

        // Update extension records
        $this->activate_extension();

        // All done
        return true;
    }

    /**
     * Install/Update hook
     * @param string $hook
     * @param string $method
     */
    private function installUpdateHook($hook, $method = '')
    {
        // Set method if not set
        $method = $method ?: $hook;

        // Check for existing hook
        /** @var QueryBuilder $extRecord */
        $extRecord = ee('Model')->get('Extension');
        $extRecord->filter('class', __CLASS__);
        $extRecord->filter('hook', $hook);
        $extRecord->filter('method', $method);
        $extRecord = $extRecord->first();

        // If no extension record, make one
        if (! $extRecord) {
            $extRecord = ee('Model')->make('Extension');
        }

        /** @var ExtensionRecord $extRecord */

        // Set record properties
        $extRecord->set(array(
            'class' => __CLASS__,
            'method' => $method,
            'hook' => $hook,
            'settings' => '',
            'version' => CP_INJECT_VER
        ));

        // Save the extension
        $extRecord->save();
    }

    /**
     * cp_css_end hook
     */
    public function cp_css_end()
    {
        // Get CSS from any other extensions already called on this hook
        // if applicable
        $css = ee()->extensions->last_call ?: '';

        // Get the CSS contents, add it to the CSS, and return it
        return $css . $this->getContentsFromConfig('css');
    }

    /**
     * cp_js_end hook
     */
    public function cp_js_end()
    {
        // Get CSS from any other extensions already called on this hook
        // if applicable
        $js = ee()->extensions->last_call ?: '';

        // Get the JS contents, add it to the JS, and return it
        return $js . $this->getContentsFromConfig('js');
    }

    /**
     * Get file contents from config arrays
     * @param string $type
     * @return string
     */
    private function getContentsFromConfig($type)
    {
        // Set up some paths
        $paths = array(
            'noPath' => '',
            'publicPath' => rtrim($_SERVER['DOCUMENT_ROOT'], '/') . '/',
            'userPath' => rtrim(SYSPATH, '/') . '/user/',
            'pathThirdThemes' => rtrim(PATH_THIRD_THEMES, '/') . '/',
            'sysPath' => rtrim(SYSPATH, '/') . '/',
            'pathThird' => rtrim(PATH_THIRD, '/') . '/',
        );

        // Get the config class
        /** @var \EE_Config $configClass */
        $configClass = ee()->config;

        // Get the config
        $config = $configClass->item($type, 'cp_inject');

        // If no config, return an empty string
        if (! $config) {
            return '';
        }

        // Check if config is string and convert it to array
        if (is_string($config)) {
            $config = [$config];
        }

        // If the config is not an array, return
        if (! is_array($config)) {
            return '';
        }

        // Start contents variable
        $contents = '';

        // Iterate through items in config
        /** @var array $config */
        foreach ($config as $item) {
            // Iterate through paths and check for a match
            foreach ($paths as $path) {
                // Get this path
                $thisPath = "{$path}{$item}";

                // If the path is not a file or not readable, continue to next
                if (! is_file($thisPath) || ! is_readable($thisPath)) {
                    continue;
                }

                // Get the file contents
                $contents .= file_get_contents($thisPath);

                // End path processing
                break;
            }
        }

        // Return the contents
        return $contents;
    }
}
