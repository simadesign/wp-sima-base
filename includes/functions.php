<?php

if(!defined('ABSPATH')) {
    exit; // Accessed directly
}


if(!function_exists('sima')){
    function sima(): \SimaBase\SimaBase
    {
        return \SimaBase\SimaBase::getInstance();
    }
}

if(!function_exists('momemt')){
    /** @throws \Moment\MomentException */
    function momemt(string $dateTime = "now", $timezone = null, $immutableMode = false): \Moment\Moment
    {
        return new \Moment\Moment($dateTime, $timezone, $immutableMode);
    }
}

if(!function_exists('url')){
    function url($path){
        if(substr($path, 0, 1) === '/') $path = substr($path, 1);
        return get_page_link().$path;
    }
}

if(!function_exists('formatBytes')){
    function formatBytes($size, $precision = 2){
        $base = log($size, 1024);
        $suffixes = ['B', 'KB', 'MB', 'GB', 'TB'];

        return round(pow(1024, $base - floor($base)), $precision) .' '. $suffixes[floor($base)];
    }
}

function is_login_page() {
    return in_array($GLOBALS['pagenow'], ['wp-login.php', 'wp-register.php']);
}

function is_shop_page(){
    if(!function_exists('is_shop')){
        return false;
    }

    return is_page('shop') || is_shop() || is_product_category() || is_product() || is_account_page() || is_wc_endpoint_url();
}


function get_home_post_id(){
    return get_option('page_on_front');
}
function get_home_post(){
    return get_post(get_home_post_id());
}


function force_404() {
    status_header(404);
    nocache_headers();

    include(get_query_template('404'));
    die();
}


function asset($path){
    if(substr($path, 0, 1) !== '/') $path = "/$path";
    return get_template_directory_uri() . $path;
}

function asset_contents($path){
    if(substr($path, 0, 1) !== '/') $path = "/$path";
    return file_get_contents(get_template_directory().$path);
}


function returnIf($if, $return = "", $else = null){
    return ($if)? $return : $else;
}

function echoIf($if, $return = "", $else = ""){
    echo returnIf($if, $return, $else);
}


function acf_img($image, $size = 'thumbnail', $attributes = []){
    $attributes = array_merge(['src' => wp_get_attachment_image_url($image['id'], $size), 'alt' => $image['alt']], $attributes);
    $attributeStrings = [];
    foreach ($attributes as $key => $value){
        $attributeStrings[] = "$key=\"$value\"";
    }
    return '<img '.implode(' ', $attributeStrings).'/>';
}

function linked_acf_img($image, $size = 'thumbnail', $attributes = []){
    $el = '<a href="'.wp_get_attachment_image_url($image['id'], 'full').'" target="_blank">'.acf_img($image, $size, $attributes).'</a>';
    return function_exists('slb_activate')? slb_activate($el) : $el;
}