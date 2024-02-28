<?php
/**
 * Fired during plugin core functions
 *
 * @link       https://github.com/Netmow-PTY-LTD
 * @since      1.0
 *
 * @package    netmow-backup
 * @subpackage netmow-backup/inc
 */

/**
 * Fired during plugin run.
 *
 * This class defines all code necessary to run during the plugin's features.
 *
 * @since      1.0
 * @package    netmow-backup
 * @subpackage netmow-backup/inc
 * @author     Netmow <pranto@netmow.com>
 */

class Netmow_backup_features_init {

	protected $loader;
	protected $plugin_name;
	protected $plugin_version;

	public function __construct() {
		if ( defined( 'NETMOW_BACKUP_VERSION' ) ) {
			$this->plugin_version = NETMOW_BACKUP_VERSION;
		} else {
			$this->plugin_version = '1.0';
		}
		$this->plugin_name = 'netmow-backup';
		$this->netmow_backup_load_dependencies();
		$this->netmow_backup_define_admin_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * Netmow_backup_loader Orchestrates the hooks of the plugin.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0
	 * @access   private
	 */
	
	private function netmow_backup_load_dependencies() {
		
		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'inc/class-netmow-backup-loader.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'inc/class-netmow-backup-admin.php';

		$this->loader = new Netmow_backup_loader_init();

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0
	 * @access   private
	 */
	private function netmow_backup_define_admin_hooks() {

		$plugin_admin = new Netmow_backup_app_admin( $this->netmow_backup_get_plugin_name(), $this->netmow_backup_get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'netmow_backup_enqueue_styles' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'netmow_backup_add_admin_pages');
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_admin, 'netmow_backup_jquery_library_check_init' );
	}


	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0
	 */
	public function netmow_backup_run() {
		$this->loader->netmow_backup_run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0
	 * @return    string    The name of the plugin.
	 */
	public function netmow_backup_get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0
	 * @return    netmow_backup_loader    Orchestrates the hooks of the plugin.
	 */
	public function netmow_backup_get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0
	 * @return    string    The version number of the plugin.
	 */
	public function netmow_backup_get_version() {
		return $this->plugin_version;
	}
}
