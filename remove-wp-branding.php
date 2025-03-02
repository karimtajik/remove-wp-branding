<?php
/*
Plugin Name: Remove WordPress Branding - Karim Tajik
Plugin URI: https://karimtajik.com
Description: A lightweight plugin by Karim Tajik that forcefully removes the "Powered by WordPress" text from both above and below the login form.
Version: 1.3
Author: Karim Tajik
Author URI: https://karimtajik.com
License: GPL2
Text Domain: remove-wp-branding
Domain Path: /languages
*/


// Force remove branding via CSS
function remove_wp_login_branding_css() {
    echo '<style type="text/css">
        /* Remove "Powered by WordPress" from above and below */
        .login a[href*="wordpress.org"], 
        .login p[style*="text-align:center"], 
        .login .privacy-policy-page-link, 
        .login #backtoblog, 
        .login #nav, 
        .login .wp-footer, 
        .login .footer-wrapper {
            display: none !important;
            visibility: hidden !important;
            height: 0 !important;
            opacity: 0 !important;
            position: absolute !important;
            z-index: -9999;
        }

        /* Hide any unexpected WordPress branding */
        .login h1 a {
            display: none !important;
            background-image: none !important;
            text-indent: -9999px;
        }
    </style>';
}
add_action('login_head', 'remove_wp_login_branding_css');

// Use JavaScript to remove elements that reload dynamically
function remove_wp_login_branding_js() {
    echo '<script>
        document.addEventListener("DOMContentLoaded", function() {
            let elementsToRemove = document.querySelectorAll(
                ".login a[href*=\'wordpress.org\'], " +
                ".login p[style*=\'text-align:center\'], " +
                ".login #backtoblog, .login #nav, .login .wp-footer, .login .footer-wrapper"
            );
            elementsToRemove.forEach(el => el.remove());
        });
    </script>';
}
add_action('login_footer', 'remove_wp_login_branding_js');

// Completely remove WordPress branding from login hooks
function remove_wp_login_hooks() {
    remove_action('login_footer', 'wp_shake_js', 12);
    remove_action('login_footer', 'login_footer');
}
add_action('init', 'remove_wp_login_hooks');
?>

// Enable GitHub Updates
function custom_plugin_update_checker() {
    require plugin_dir_path(__FILE__) . 'update-checker.php';
    $update_checker = Puc_v4_Factory::buildUpdateChecker(
        'https://github.com/karimtajik/remove-wp-branding/', // Replace with your GitHub repo URL
        __FILE__,
        'remove-wp-branding'
    );

    // Set branch (default: main or master)
    $update_checker->setBranch('main');
}
add_action('init', 'custom_plugin_update_checker');
