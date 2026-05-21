<?php
namespace SimaBase\Core;

use SimaBase\Core\Traits\Singleton;

class Security
{

    use Singleton;

    protected bool $_disablePublicUserEndpoint = true;

    public function disablePublicUserEndpoint($disable = true){
        $this->_disablePublicUserEndpoint = $disable;
    }

}