<?php
namespace SimaBase\Helper\Theme;

use SimaBase\Frontend\Theme;
use SimaBase\Plugin;

class ScriptHelper
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

    public function useJquery(): void
    {
        wp_localize_script('jquery', 'ajaxurl', admin_url('admin-ajax.php'));
        wp_enqueue_script('jquery');
    }

    public function useScrollFlow(): Theme
    {
        return $this->theme->useScript('scroll-flow', $this->plugin->getPluginUrl("/includes/js/ScrollFlow.js"));
    }

    public function useCountUp(): Theme
    {
        return $this->theme->useScript('count-up', $this->plugin->getPluginUrl("/includes/vendor/inorganik/countUp/js/countUp.umd.js"));
    }

}