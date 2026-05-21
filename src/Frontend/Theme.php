<?php
namespace SimaBase\Frontend;

use Composer\InstalledVersions;
use SimaBase\Admin\Customizer;
use SimaBase\Core\Traits\Singleton;
use SimaBase\Plugin;
use SimaBase\SimaBase;

class Theme {

    use Singleton;

    protected SimaBase $simaBase;
    protected Plugin $plugin;

    protected Customizer $customizer;

    protected array $menus = [];

    public function __construct() {

        $this->simaBase = SimaBase::getInstance();
        $this->plugin = $this->simaBase->getPlugin();

        $this->customizer = new Customizer();

        $this->registerFilters();
        $this->registerActions();
        $this->registerCoreFiles();
        $this->registerCoreFeatures();

    }

    protected function registerFilters(){

        //

    }

    protected function registerActions(){

        add_action('after_setup_theme', [$this, 'registerMenus']);

    }

    protected function registerCoreFiles(){
        $this->onLoadFrontend(function() {

            wp_enqueue_script('main_script');

        });
    }

    protected function registerCoreFeatures(){

        add_theme_support('title-tag');
        add_theme_support('gallery');
        add_theme_support('post-thumbnails');

    }



    public function getCustomizer(): Customizer
    {
        return $this->customizer;
    }

    public function getSocial(): Customizer\SocialManager
    {
        return $this->customizer->getSocial();
    }



    public function useScript($handle, $src, $version = false, $deps = [], $args = []) {
        wp_enqueue_script($handle, $src, $deps, $version, $args);

        return $this;
    }

    public function useThemeScript($handle, $themeSrc, $version = false, $deps = [], $args = []) {
        return $this->useScript($handle, asset($themeSrc), $deps, $version, $args);
    }

    public function useStyle($handle, $src, $version = false, $deps = [], $media = 'all'){
        wp_enqueue_style($handle, $src, $deps, $version, $media);

        return $this;
    }

    public function useThemeStyle($handle, $themeSrc, $version = false, $deps = [], $media = 'all'){
        return $this->useStyle($handle, asset($themeSrc), $deps, $version, $media);
    }


    public function useUtils(){
        return $this->useStyle('sima-style', $this->plugin->getPluginUrl("/includes/css/style.css"), $this->plugin->version);
    }

    public function useBootstrapGrid(){
        return $this->useStyle('bootstrap-grid', $this->plugin->getPluginUrl("/vendor/twbs/bootstrap/dist/css/bootstrap-grid.min.css"), InstalledVersions::getVersion('twbs/bootstrap'));
    }

    public function useBootstrapUtils(){
        return $this->useStyle('bootstrap-utilities', $this->plugin->getPluginUrl("/vendor/twbs/bootstrap/dist/css/bootstrap-utilities.min.css"), InstalledVersions::getVersion('twbs/bootstrap'));
    }

    public function useJquery(){
        wp_localize_script('jquery', 'ajaxurl', admin_url('admin-ajax.php'));
        wp_enqueue_script('jquery');
    }

    public function useScrollFlow(){
        return $this->useScript('scroll-flow', $this->plugin->getPluginUrl("/includes/js/ScrollFlow.js"));
    }

    public function useCountUp(){
        return $this->useScript('count-up', $this->plugin->getPluginUrl("/includes/vendor/inorganik/countUp/js/countUp.umd.js"));
    }


    public function useTitleBuilder(){
        add_filter('wp_title', [new TitleBuilder(), 'build'], 10, 2);
    }

    public function useAcfGoogleMaps(string $apiKey){
        add_filter('acf/fields/google_map/api', function($api) use($apiKey){
            $api['key'] = $apiKey;

            return $api;
        });

        return $this;
    }

    public function useWoocommerce(){
        add_theme_support('woocommerce');
        add_filter('woocommerce_show_page_title', '__return_false');
        add_filter('single_product_archive_thumbnail_size', function(){
            return 'large';
        });
    }



    public function onLoadFrontend(callable $callback){
        add_action('init', function() use($callback){
            if(!is_admin() && !is_login_page()){
                $callback($this);
            }
        });
    }



    public function menu($slug, $name): self
    {
        if(count($this->menus) === 0){
            add_theme_support('menus');
        }

        $this->menus[$slug] = $name;

        return $this;
    }

    public function registerMenus(){
        register_nav_menus($this->menus);
    }

}