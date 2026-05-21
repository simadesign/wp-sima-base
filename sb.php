<?php
/**
 * SiMa Base
 *
 * @author        SiMaDesign
 *
 * @wordpress-plugin
 * Plugin Name:   SiMa Base
 * Plugin URI:    https://simadesign.de
 * Description:   A WordPress base plugin for SiMa projects, providing utilities for custom theme development and essential security features.
 * Version:       0.1
 * Author:        SiMaDesign
 * Author URI:    https://simadesign.de
 * Update URI:    https://simadesign.de
 * Text Domain:   sb
 */

if (!defined('ABSPATH')) {
    exit; // Accessed directly
}

require_once __DIR__ . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

$simaTheme = \SimaBase\SimaBase::getInstance();

require_once __DIR__ . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'functions.php';