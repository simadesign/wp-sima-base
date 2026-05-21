<?php
namespace SimaBase;

if(!defined('ABSPATH')) {
    exit; // Accessed directly
}

use SimaBase\Core\Traits\Singleton;
use SimaBase\Core\Updater;

class Plugin
{

    use Singleton;


    const CAPABILITY = 'manage_options';


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

        $this->updater = new Updater($this);
    }

    public function getData(): array
    {
        if(empty($this->pluginData)){
            $this->pluginData = get_plugin_data($this->pluginFile);
        }
        return $this->pluginData;
    }

    public function getVersion(): string
    {
        return $this->getData()['Version'];
    }


    public function onAdminLoad(callable $callback): void
    {
        add_action('admin_init', function() use($callback){
            $callback($this);
        });
    }

    public function onLoad(callable $callback): void
    {
        add_action('init', function() use($callback){
            $callback($this);
        });
    }


    public function getPluginUrl($path): string
    {
        return (str_starts_with($path, '/'))? $this->pluginDirUrl . substr($path, 1) : "{$this->pluginDirUrl}{$path}";
    }

}