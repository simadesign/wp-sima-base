<?php
namespace SimaBase\Helper\Theme;

use Composer\InstalledVersions;
use SimaBase\Frontend\Theme;
use SimaBase\Plugin;

class StyleHelper
{

    /** @var Plugin */
    protected Plugin $plugin;

    /** @var Theme */
    protected Theme $theme;

    public function __construct(Plugin $plugin, Theme $theme)
    {
        $this->plugin = $plugin;
        $this->theme = $theme;
    }

    public function useUtils(){
        return $this->theme->useStyle('sima-style', $this->plugin->getPluginUrl("/includes/css/style.css"), $this->plugin->getVersion());
    }

    public function useBootstrapGrid(){
        return $this->theme->useStyle('bootstrap-grid', $this->plugin->getPluginUrl("/vendor/twbs/bootstrap/dist/css/bootstrap-grid.min.css"), InstalledVersions::getVersion('twbs/bootstrap'));
    }

    public function useBootstrapUtils(){
        return $this->theme->useStyle('bootstrap-utilities', $this->plugin->getPluginUrl("/vendor/twbs/bootstrap/dist/css/bootstrap-utilities.min.css"), InstalledVersions::getVersion('twbs/bootstrap'));
    }

}