<?php
namespace SimaBase;

if (!defined('ABSPATH')) {
    exit; // Accessed directly
}

use Moment\Moment;
use Moment\MomentException;
use SimaBase\Core\Security;
use SimaBase\Core\Traits\Singleton;
use SimaBase\Frontend\Theme;

class SimaBase {

    use Singleton;

    /** @var Plugin */
    protected Plugin $plugin;

    /** @var Theme */
    protected Theme $theme;

    public string $site_url;
    public string $plugin_dir_url;

    protected function __construct(){
        $this->site_url = strtolower(get_site_url());
        $this->plugin_dir_url = plugin_dir_url(__DIR__);
    }

    public function getPlugin(): Plugin
    {
        return Plugin::getInstance();
    }

    public static function getSecurity(): Security
    {
        return Security::getInstance();
    }

    public static function getTheme(): Theme
    {
        return Theme::getInstance();
    }



    /** @throws MomentException */
    public static function Moment(string $dateTime = "now", $timezone = null, $immutableMode = false): Moment
    {
        return new Moment($dateTime, $timezone, $immutableMode);
    }

}