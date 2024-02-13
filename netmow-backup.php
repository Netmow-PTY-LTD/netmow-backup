<?php
/**
 * Plugin Name:       Netmow Backup - Auto Backup to Google Drive
 * Plugin URI:        #
 * Description:       Netmow Backup: Safeguard Your Data Effortlessly. Auto Backup to Google Drive for Instant Security
 * Version:           1.0
 * Requires at least: 5.0
 * Requires PHP:      7.0
 * Author:            Netmow
 * Author URI:        https://netmow.com/
 * Text Domain:       netmow
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

 // If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
echo '<h1>If this file is called directly, abort  guti lal</h1>';

// Current plugin version.
define( 'NETMOW_BACKUP_VERSION', '1.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in class-miyn-app-activation.php
 */
function netmow_backup_activate_init() {
	require_once plugin_dir_path( __FILE__ ) . 'netmow-backup-activation.php';
	Netmow_backup_activations_init::netmow_backup_activate();
}
register_activation_hook( __FILE__, 'netmow_backup_activate_init' );

/**
 * The code that runs during plugin deactivation.
 * This action is documented in netmow-backup-deactivation.php
 */
function netmow_backup_deactivation_init() {
	require_once plugin_dir_path( __FILE__ ) . 'netmow-backup-deactivation.php';
	Netmow_backup_deactivator_init::netmow_backup_deactivate();
}
register_deactivation_hook( __FILE__, 'netmow_backup_deactivation_init' );

/**
 * The code that runs during plugin activation.
 * This action is documented in inc/class-init-netmow-backup-core.php
 */
require_once plugin_dir_path( __FILE__ ) . 'inc/class-init-netmow-backup-core.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0
 */
function netmow_backup_run_init() {

	$plugin = new Netmow_backup_features_init();
	$plugin->netmow_backup_run();

}
netmow_backup_run_init();