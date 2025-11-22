<?php
function iamzee_enqueue_styles() {
    $parent_style = 'storefront-style'; 

    wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'iamzee-child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array( $parent_style ),
        wp_get_theme()->get('Version')
    );
}
add_action( 'wp_enqueue_scripts', 'iamzee_enqueue_styles' );

/**
 * Enqueue Google Fonts (Plus Jakarta Sans)
 */
function iamzee_add_google_fonts() {
    wp_enqueue_style( 'iamzee-google-fonts', 'https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap', false );
}
add_action( 'wp_enqueue_scripts', 'iamzee_add_google_fonts' );