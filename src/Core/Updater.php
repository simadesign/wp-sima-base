<?php
namespace SimaTheme\Core;

use SimaBase\Plugin;

class Updater
{

    const PLUGIN_DATA_URL = 'https://raw.githubusercontent.com/simadesign/wp-sima-base/main/plugin.json';


    protected Plugin $plugin;

    protected string $_cache_key = 'sima_base_update_data';


    public function __construct(Plugin $plugin)
    {
        $this->plugin = $plugin;

        $this->register();
    }

    protected function register(): void
    {
        add_filter('pre_set_site_transient_update_plugins', [$this, 'checkForUpdates']);
        add_filter('plugins_api', [$this, 'getPluginInfo'], 20, 3);
    }

    public function checkForUpdates(object $transient): object
    {
        if (empty($transient->checked[$this->plugin->basename])) {
            return $transient;
        }

        $remote = $this->getRemotePluginData();

        if(!$remote || empty($remote['version']) || empty($remote['download_url'])) {
            return $transient;
        }

        $currentVersion = $transient->checked[$this->plugin->basename];

        if(version_compare($remote['version'], $currentVersion, '>')) {
            $transient->response[$this->plugin->basename] = (object) [
                'slug'        => $this->plugin->slug,
                'plugin'      => $this->plugin->basename,
                'new_version' => $remote['version'],
                'url'         => $remote['homepage'] ?? '',
                'package'     => $remote['download_url'],
                'tested'      => $remote['tested'] ?? '',
                'requires'    => $remote['requires'] ?? '',
                'requires_php'=> $remote['requires_php'] ?? '',
            ];
        }

        return $transient;
    }

    public function getPluginInfo(false|object|array $result, string $action, object $args): false|object|array
    {
        if ($action !== 'plugin_information') {
            return $result;
        }

        if (($args->slug ?? '') !== $this->plugin->slug) {
            return $result;
        }

        $remote = $this->getRemotePluginData();

        if (!$remote) {
            return $result;
        }

        return (object) [
            'name'          => $remote['name'] ?? 'SiMa Base',
            'slug'          => $this->plugin->slug,
            'version'       => $remote['version'] ?? '',
            'author'        => $remote['author'] ?? 'SiMa Design',
            'homepage'      => $remote['homepage'] ?? '',
            'requires'      => $remote['requires'] ?? '',
            'tested'        => $remote['tested'] ?? '',
            'requires_php'  => $remote['requires_php'] ?? '',
            'download_link' => $remote['download_url'] ?? '',
            'sections'      => [
                'description' => $remote['sections']['description'] ?? '',
                'changelog'   => $remote['sections']['changelog'] ?? '',
            ],
        ];
    }

    protected function getRemotePluginData(): ?array
    {
        $cached = get_site_transient($this->_cache_key);

        if(is_array($cached)) {
            return $cached;
        }

        $response = wp_remote_get(Updater::PLUGIN_DATA_URL, [
            'headers' => [
                'Accept' => 'application/json',
            ],
        ]);

        if(is_wp_error($response) || wp_remote_retrieve_response_code($response) !== 200) {
            return null;
        }

        $data = json_decode(wp_remote_retrieve_body($response), true);

        if(!is_array($data)) {
            return null;
        }

        set_site_transient($this->_cache_key, $data, 2 * HOUR_IN_SECONDS);

        return $data;
    }

}