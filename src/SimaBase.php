<?php
namespace SimaBase;

if(!defined('ABSPATH')) {
    exit; // Accessed directly
}

use SimaBase\Core\Security;
use SimaBase\Core\Traits\Singleton;
use SimaBase\Frontend\Theme;

class SimaBase {

    use Singleton;

    /** @var Plugin */
    protected Plugin $plugin;

    /** @var Security */
    protected Security $security;

    /** @var Theme */
    protected Theme $theme;

    public string $site_url;

    protected function __construct(){
        $this->plugin = Plugin::getInstance();
        $this->security = Security::getInstance();
        $this->theme = Theme::getInstance();

        $this->site_url = strtolower(get_site_url());
    }

    public function plugin(): Plugin
    {
        return $this->plugin;
    }

    public function security(): Security
    {
        return $this->security;
    }

    public function theme(): Theme
    {
        return $this->theme;
    }

}