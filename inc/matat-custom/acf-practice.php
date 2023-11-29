<?php

/**
 * Menu Field Settings
 */
add_filter('wp_nav_menu_items', 'my_wp_nav_menu_items', 10, 2);
function my_wp_nav_menu_items( $items, $args ) {    
    // get menu
    $menu = wp_get_nav_menu_object($args->menu);
    print_r( $menu );    
    // modify primary only 
        
        // vars
        $logo = get_field('logo', $menu);
        $color = get_field('color', $menu);

        // echo '<pre>';
        // print_r($logo);
        // echo '</pre>';        
        
        // prepend logo
        $html_logo = '<li class="menu-item-logo"><a href="'.home_url().'"><img src="'.$logo['url'].'" alt="'.$logo['alt'].'" /></a></li>';
        
        // append style
        $html_color = '<style type="text/css">.navigation-top{ background: '.$color.';}</style>';
        
        // append html
        $items = $html_logo . $items . $html_color;
    
    // return
    return $items;    
}

/**
 * Global Field Settings
 */
