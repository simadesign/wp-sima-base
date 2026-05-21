<?php
namespace SimaBase;

use SimaBase\Admin\AdminController;
use SimaBase\Core\Traits\Singleton;
use SimaTheme\Core\Updater;

class Plugin
{

    use Singleton;

    const CAPABILITY = 'manage_options';

    /** @var AdminController */
    protected AdminController $adminController;

    /** @var Updater */
    protected Updater $updater;

    public string $basename;
    public string $slug = 'sima-base';

    public string $pluginFile;
    public ?array $pluginData = null;
    public ?string $version = null;

    public string $pluginDirUrl;

    public function __construct(){
        $this->pluginDirUrl = plugin_dir_url(__DIR__);
        $this->pluginFile = realpath(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'st.php');
        $this->basename = plugin_basename($this->pluginFile);

        $this->onAdminLoad(function() {
            $this->pluginData = get_plugin_data($this->pluginFile);
            $this->version = $this->pluginData['Version'];
        });

        $this->adminController = new AdminController();
        $this->updater = new Updater($this);
    }


    public function onAdminLoad(callable $callback){
        add_action('admin_init', function() use($callback){
            $callback($this);
        });
    }

    public function onLoad(callable $callback){
        add_action('init', function() use($callback){
            $callback($this);
        });
    }


    public function getPluginUrl($path){
        return (str_starts_with($path, '/'))? $this->pluginDirUrl . substr($path, 1) : "{$this->pluginDirUrl}{$path}";
    }

}