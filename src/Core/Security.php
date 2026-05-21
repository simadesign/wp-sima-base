<?php
namespace SimaBase\Core;

if(!defined('ABSPATH')) {
    exit; // Accessed directly
}

use SimaBase\Core\Traits\Singleton;

class Security
{

    use Singleton;

    protected bool $_disablePublicUserEndpoint = true;

    public function __construct()
    {
        $this->registerFilters();
    }

    protected function registerFilters(): void
    {

        add_filter('rest_endpoints', [$this, '_handleRestEndpoints']);

    }

    public function _handleRestEndpoints($endpoints): mixed
    {
        if($this->_disablePublicUserEndpoint){
            if(isset($endpoints['/wp/v2/users'])){
                unset($endpoints['/wp/v2/users']);
            }
            if(isset($endpoints['/wp/v2/users/(?P<id>[\d]+)'])){
                unset($endpoints['/wp/v2/users/(?P<id>[\d]+)']);
            }
        }

        return $endpoints;
    }

    public function disablePublicUserEndpoint($disable = true): void
    {
        $this->_disablePublicUserEndpoint = $disable;
    }

}