<?php

/**
 * Register & Enqueue Admin JavaScripts Scripts
 *
 * @return void
 */
if (!function_exists('adieve_admin_script_enqueue')) {
    function adieve_admin_script_enqueue()
    {
        $current_screen = get_current_screen();
        if (strpos($current_screen->base, 'adieve') !== false) {
            wp_register_script(
                'adieve-admin-js',
                PLUGIN_URL . 'assets/js/adieve-admin.js',
                array('wp-api'),
                false,
                false
            );
            wp_register_script(
                'adieve-ecs-js',
                PLUGIN_URL . 'assets/js/ecs.js',
                false,
                false,
                false
            );
            wp_register_script(
                'adieve-utils-js',
                PLUGIN_URL . 'assets/js/utils.js',
                false,
                false,
                false
            );

            wp_enqueue_script('adieve-admin-js');
            wp_enqueue_script('adieve-ecs-js');
            wp_enqueue_script('adieve-utils-js');
        }
    }
}
/**
 * Register & Enqueue Public JavaScripts Scripts
 *
 * @return void
 */
if (!function_exists('adieve_public_script_enqueue')) {
    function adieve_public_script_enqueue()
    {
        wp_register_script(
            'adieve-ecs-js',
            PLUGIN_URL . 'assets/js/ecs.js',
            false,
            false,
            true
        );
        wp_register_script(
            'adieve-utils-js',
            PLUGIN_URL . 'assets/js/utils.js',
            false,
            false,
            true
        );
        wp_register_script(
            'adieve-members-js',
            PLUGIN_URL . 'assets/js/adieve-members.js',
            false,
            false,
            true
        );

        wp_enqueue_script('adieve-ecs-js');
        wp_enqueue_script('adieve-utils-js');
        wp_enqueue_script('adieve-members-js');


        wp_localize_script(
            'adieve-members-js',
            'adieve_globals',
            array(
                'adieve_ajax'                     => admin_url('admin-ajax.php'),
                'adieve_members_action'           => 'adieve_members_handler',
                'adieve_members_nonce'            => wp_create_nonce('adieve_members_nonce')
            )
        );

    }
}
add_action( 'admin_enqueue_scripts', 'adieve_admin_script_enqueue' );
add_action( 'wp_enqueue_scripts', 'adieve_public_script_enqueue' );
