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

/**
 * Optimize Checkout Fields (Remove Bloat)
 */
add_filter( 'woocommerce_checkout_fields' , 'iamzee_simplify_checkout' );

function iamzee_simplify_checkout( $fields ) {
    // Remove Company Name (Unless you strictly sell B2B)
    unset($fields['billing']['billing_company']);
    unset($fields['shipping']['shipping_company']);
    
    // Make Phone Number Required (Essential for Indian Logistics/OTP)
    $fields['billing']['billing_phone']['required'] = true;
    
    // Optional: Remove Address Line 2 if you want a cleaner form
    // unset($fields['billing']['billing_address_2']);
    // unset($fields['shipping']['shipping_address_2']);

    return $fields;
}

/**
 * Clean up Product Meta (Hide SKU and Uncategorized)
 */
function iamzee_remove_product_meta() {
    // Remove SKU
    add_filter( 'wc_product_sku_enabled', '__return_false' );
}
add_action( 'init', 'iamzee_remove_product_meta' );

// Hide "Uncategorized" from category lists
add_filter( 'get_the_terms', 'iamzee_hide_uncategorized', 10, 3 );
function iamzee_hide_uncategorized( $terms, $post_id, $taxonomy ) {
    if ( ! is_admin() && ! empty( $terms ) && is_array( $terms ) ) {
        foreach ( $terms as $key => $term ) {
            if ( 'Uncategorized' === $term->name || 'uncategorized' === $term->slug ) {
                unset( $terms[$key] );
            }
        }
    }
    return $terms;
}

/**
 * Rename Product Tabs
 */
add_filter( 'woocommerce_product_tabs', 'iamzee_rename_tabs', 98 );
function iamzee_rename_tabs( $tabs ) {
    if ( isset( $tabs['description'] ) ) {
        $tabs['description']['title'] = __( 'Details', 'woocommerce' );
    }
    return $tabs;
}

/**
 * Disable Cart Fragments on Non-WooCommerce Pages (Performance)
 */
add_action( 'wp_enqueue_scripts', 'iamzee_dequeue_cart_fragments', 11 ); 
function iamzee_dequeue_cart_fragments() {
    if ( is_front_page() || is_page() ) { 
        wp_dequeue_script( 'wc-cart-fragments' ); 
    }
}

/**
 * Custom Footer Credit (Remove Storefront Credits)
 */
function iamzee_edit_footer_credit() {
    remove_action( 'storefront_footer', 'storefront_credit', 20 );
    add_action( 'storefront_footer', 'iamzee_custom_credit', 20 );
}
add_action( 'init', 'iamzee_edit_footer_credit' );

function iamzee_custom_credit() {
    ?>
    <div class="site-info" style="float:none; text-align:center;">
        &copy; <?php echo date( 'Y' ); ?> <strong>I Am Zee Shop</strong>. All rights reserved.
        <br>
        <span style="font-size:0.85em; opacity:0.6;">Curated for the Modern Indian Lifestyle.</span>
    </div>
    <?php
}