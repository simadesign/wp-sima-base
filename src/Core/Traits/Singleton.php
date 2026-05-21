<?php
namespace SimaBase\Core\Traits;

if(!defined('ABSPATH')) {
    exit; // Accessed directly
}

trait Singleton {

    /** @var self|null */
    private static ?self $_instance = null;

    /** @return self */
    public static function getInstance(): self
    {
        if(self::$_instance === null) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    private function __construct(){}
    private function __clone(){}

    public function __wakeup()
    {
        throw new \Exception("Cannot unserialize a singleton.");
    }

}