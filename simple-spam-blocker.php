<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              awais300@gmail.com
 * @since             2.0.0
 * @package           Spam_Blocker
 *
 * @wordpress-plugin
 * Plugin Name:       Simple Spam Blocker
 * Description:       Stop spam comments and also can be used to stop bots to try to login into admin panel.
 * Version:           2.0.0
 * Author: AWP
 * Author URI: https://awaiswp.is-a-fullstack.dev/contact/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       spam-blocker
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 */
define( 'SPAM_BLOCKER_VERSION', '2.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-spam-blocker-activator.php
 */
function activate_spam_blocker() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-spam-blocker-activator.php';
	Spam_Blocker_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-spam-blocker-deactivator.php
 */
function deactivate_spam_blocker() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-spam-blocker-deactivator.php';
	Spam_Blocker_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_spam_blocker' );
register_deactivation_hook( __FILE__, 'deactivate_spam_blocker' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-spam-blocker.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_spam_blocker() {

	$plugin = new Spam_Blocker();
	$plugin->run();

}
run_spam_blocker();
