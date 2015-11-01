<?php
namespace Grav\Plugin\Api;
require_once 'resource.php';

use Grav\Common\Config\ConfigFinder;
use Symfony\Component\Yaml\Yaml;

/**
 * Config API
 */
class Config extends Resource
{
    /**
     * Get the config files list
     *
     * Implements:
     *
     * - GET /api/config
     *
     * @todo:
     *
     * @return array the config files list
     */
    public function getList()
    {

        $this->finder = new ConfigFinder;
        $locator = $this->grav['locator'];
        $this->configLookup = $locator->findResources('config://');
        $this->blueprintLookup = $locator->findResources('blueprints://config');
        $this->pluginLookup = $locator->findResources('plugins://');

        $config = $this->finder->locateConfigFiles($this->configLookup, $this->pluginLookup);
        return $config;
    }

    /**
     * Get a single config file
     *
     * Implements:
     *
     * - GET /api/config/:config
     *
     * Examples:
     *
     * - GET /api/config/system
     * - GET /api/config/system.languages
     * - GET /api/config/system/languages
     * - GET /api/config/plugins
     * - GET /api/config/plugins.admin
     * - GET /api/config/plugins/admin
     *
     * @todo:
     *
     * @return array the single config file content
     */
    public function getItem()
    {
        $config = $this->grav['config'];
        return (array)$config->get(str_replace('/', '.', $this->getIdentifier()));
    }
}