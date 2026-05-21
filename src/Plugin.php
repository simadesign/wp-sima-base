<?php
namespace SimaBase;

if(!defined('ABSPATH')) {
    exit; // Accessed directly
}

use SimaBase\Admin\AdminController;
use SimaBase\Core\Traits\Singleton;
use SimaBase\Core\Updater;

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
    public string $pluginDirUrl;

    public function __construct(){
        $this->pluginFile = realpath(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'sb.php');
        $this->pluginDirUrl = plugin_dir_url(__DIR__);
        $this->basename = plugin_basename($this->pluginFile);

        $this->adminController = new AdminController();
        $this->updater = new Updater($this);
    }

    public function getData(){
        if(empty($this->pluginData)){
            $this->pluginData = get_plugin_data($this->pluginFile);
        }
        return $this->pluginData;
    }

    public function getVersion(){
        return $this->getData()['Version'];
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