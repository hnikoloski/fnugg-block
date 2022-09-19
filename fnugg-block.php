<?php

/**
 * Plugin Name:       Fnugg Block
 * Description:       A Gutenberg block to display weather forecast from fnugg.no
 * Version:           0.1.0
 * Requires at least: 5.9
 * Requires PHP:      7.0
 * Author:            hNikoloski
 * Author URI:        https://hnikoloski.com
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       fnugg-block
 *
 * @package           create-block
 */

/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://developer.wordpress.org/reference/functions/register_block_type/
 */

define('MOLLIE_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('MOLLIE_API_NAMESPACE', 'mollie/v1');
define('MOLLIE_PLUGIN_FILE', __FILE__);
define('FNUGG_URL', 'https://api.fnugg.no');
define('CACHE_TIME', 86400);



function create_block_fnugg_block_block_init()
{
	register_block_type(__DIR__ . '/build');
}
add_action('init', 'create_block_fnugg_block_block_init');

// Load 

if (!class_exists('Mollie_Api_Routes')) {
	require_once MOLLIE_PLUGIN_DIR . 'includes/class-mollie-starter.php';
	Mollie_Starter::getInstance();
}
