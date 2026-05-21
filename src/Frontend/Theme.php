<?php
namespace SimaBase\Frontend;

if(!defined('ABSPATH')) {
    exit; // Accessed directly
}

use SimaBase\Admin\Customizer;
use SimaBase\Core\Traits\Singleton;
use SimaBase\Plugin;
use SimaBase\Helper\Theme\ScriptHelper;
use SimaBase\Helper\Theme\StyleHelper;

class Theme {

    use Singleton;

    protected Plugin $plugin;

    protected Customizer $customizer;

    protected ScriptHelper $scriptHelper;
    protected StyleHelper $styleHelper;

    protected array $menus = [];

    public function __construct() {
        $this->plugin = Plugin::getInstance();

        $this->customizer = new Customizer();
        $this->scriptHelper = new ScriptHelper($this->plugin, $this);
        $this->styleHelper = new StyleHelper($this->plugin, $this);

        $this->registerFilters();
        $this->registerActions();
        $this->registerCoreFiles();
        $this->registerCoreFeatures();
    }

    protected function registerFilters(): void
    {
        //
    }

    protected function registerActions(): void
    {

        add_action('after_setup_theme', [$this, '_registerMenus']);

    }

    protected function registerCoreFiles(): void
    {
        $this->onLoadFrontend(function() {

            wp_enqueue_script('main_script');

        });
    }

    protected function registerCoreFeatures(): void
    {

        add_theme_support('title-tag');
        add_theme_support('gallery');
        add_theme_support('post-thumbnails');

    }



    public function scripts(): ScriptHelper
    {
        return $this->scriptHelper;
    }

    public function styles(): StyleHelper
    {
        return $this->styleHelper;
    }

    public function customizer(): Customizer
    {
        return $this->getCustomizer();
    }

    public function getCustomizer(): Customizer
    {
        return $this->customizer;
    }

    public function social(): Customizer\SocialManager
    {
        return $this->getSocial();
    }

    public function getSocial(): Customizer\SocialManager
    {
        return $this->customizer->getSocial();
    }


    public function onLoadFrontend(callable $callback): void
    {
        add_action('init', function() use($callback){
            if(!is_admin() && !is_login_page()){
                $callback($this);
            }
        });
    }



    public function menu($slug, $name): static
    {
        if(count($this->menus) === 0){
            add_theme_support('menus');
        }

        $this->menus[$slug] = $name;

        return $this;
    }

    public function _registerMenus(): void
    {
        register_nav_menus($this->menus);
    }



    public function useScript($handle, $src, $version = false, $deps = [], $args = []): static
    {
        wp_enqueue_script($handle, $src, $deps, $version, $args);

        return $this;
    }

    public function useThemeScript($handle, $themeSrc, $version = false, $deps = [], $args = []): static
    {
        return $this->useScript($handle, asset($themeSrc), $version, $deps, $args);
    }

    public function useStyle($handle, $src, $version = false, $deps = [], $media = 'all'): static
    {
        wp_enqueue_style($handle, $src, $deps, $version, $media);

        return $this;
    }

    public function useThemeStyle($handle, $themeSrc, $version = false, $deps = [], $media = 'all'): static
    {
        return $this->useStyle($handle, asset($themeSrc), $version, $deps, $media);
    }



    public function useTitleBuilder(): static
    {
        add_filter('wp_title', [new TitleBuilder(), 'build'], 10, 2);

        return $this;
    }

    public function useCustomExcerptLength($length): static
    {
        add_filter(
            'excerpt_length',
            function() use($length) {
                return $length;
            },
            99
        );

        return $this;
    }
    public function useAcfGoogleMaps(string $apiKey): static
    {
        add_filter('acf/fields/google_map/api', function($api) use($apiKey){
            $api['key'] = $apiKey;

            return $api;
        });

        return $this;
    }

    public function useWoocommerce(): void
    {
        add_theme_support('woocommerce');
        add_filter('woocommerce_show_page_title', '__return_false');
        add_filter('single_product_archive_thumbnail_size', function(){
            return 'large';
        });
    }

}