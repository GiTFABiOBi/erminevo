<?php

/**
 * The plugin bootstrap file
 *
 *
 * @wordpress-plugin
 * Plugin Name:       Ermispettacoli
 * Description:       Aggiunge nuovi spettacoli
 * Version:           1.0.0
 * Author:            Fabio Basile
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       ermispettacoli
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'ERMISPETTACOLI_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-ermispettacoli-activator.php
 */
function activate_ermispettacoli() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ermispettacoli-activator.php';
	Ermispettacoli_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-ermispettacoli-deactivator.php
 */
function deactivate_ermispettacoli() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ermispettacoli-deactivator.php';
	Ermispettacoli_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_ermispettacoli' );
register_deactivation_hook( __FILE__, 'deactivate_ermispettacoli' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-ermispettacoli.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_ermispettacoli() {

	$plugin = new Ermispettacoli();
	$plugin->run();

}
run_ermispettacoli();
