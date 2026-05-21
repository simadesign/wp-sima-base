<?php
namespace SimaBase\Frontend;

if(!defined('ABSPATH')) {
    exit; // Accessed directly
}

class TitleBuilder
{

    public function build($title, $sep) {
        if(is_feed()) {
            return $title;
        }

        // Add the site name.
        $title .= get_bloginfo( 'name', 'display' );

        // Add the site description for the home/front page.
        $site_description = get_bloginfo('description', 'display');
        if($site_description && (is_home() || is_front_page())) {
            $title = "$title $sep $site_description";
        }

        return $title;
    }

}