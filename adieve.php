<?php

/**
 * Plugin Name: ADIEVE - Gestion Membre
 * Description: Gestion des Inscriptions
 * Author: Guillaume Coquard
 * Author URI: https://github.com/originecode
 * Version: 1.0.0
 * Licence: (c) All rights reserved.
 */

define('PLUGIN_DIR', plugin_dir_path(__FILE__));
define('PLUGIN_URL', plugin_dir_url(__FILE__));

require_once PLUGIN_DIR . 'assets/php/admin/adieve-database.php';
require_once PLUGIN_DIR . 'assets/php/adieve-functions.php';
require_once PLUGIN_DIR . 'assets/php/adieve-scripts.php';
require_once PLUGIN_DIR . 'assets/php/admin/adieve-members.php';
require_once PLUGIN_DIR . 'assets/php/admin/adieve-shortcodes.php';
require_once PLUGIN_DIR . 'assets/php/public/adieve-handlers.php';