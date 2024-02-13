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

 class Netmow_backup_app_admin{

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0
	 * @access   private
	 * @var      string    $plugin_version    The current version of this plugin.
	 */
	private $plugin_version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $plugin_version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $plugin_version ) {

		$this->plugin_name = $plugin_name;
		$this->plugin_version = $plugin_version;
		$this->netmow_backup_load_zip_classes();
		$this->netmow_backup_create_backup();

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0
	 */
	public function netmow_backup_enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Netmow_backup_loader_init as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Netmow_backup_loader_init will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __DIR__ ) . 'assets/css/main.css', array(), $this->plugin_version, 'all' );

		wp_enqueue_script('netmow-backup-script', plugin_dir_url( __DIR__ ) . 'assets/js/netmow-backup-script.js', array( 'jquery' ), $this->plugin_version, false);
		// Localize the script with new data

	    $siteurl = array(
	        'siteurl' => get_home_url()
	    );
	    wp_localize_script( 'netmow-backup-script', 'object_netmow_backup_app', $siteurl );
	    // Enqueued script with localized data.
	    wp_enqueue_script( 'netmow-backup-script' );

		if ( ! did_action( 'wp_enqueue_media' ) ) {
			wp_enqueue_media();
		}

	}

	public function netmow_backup_add_admin_pages() {
        add_menu_page(__('Netmow Backup'), __('Netmow Backup'), 'manage_options', 'netmow-backup', [$this, 'netmow_backup_widgets_shortcode_init'], plugin_dir_url( __DIR__ ).'/assets/img/netmow-backup.png' );
	}

	public function netmow_backup_jquery_library_check_init(){    
	    if ( ! wp_script_is( 'jquery', 'enqueued' )) {
            //Enqueue
            wp_enqueue_script( 'jquery' );
        }
	}

	private function netmow_backup_load_zip_classes() {
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'inc/class-zip-archive.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'inc/class-export-sql.php';
	}

	public function netmow_backup_zip_and_push(){
		global $wpdb;
		$msg = "";
		$today = date("d-M-Y-H-i-s");
		$nfilename = "backup-" . $today;
	
		$the_folder = ABSPATH . "/wp-content/uploads/.";
		$zip_file_name =
			WP_CONTENT_DIR . "/netmow-backup/" . $today . "/" . $nfilename . ".zip";
		$sql_file_path = WP_CONTENT_DIR . "/netmow-backup/" . $today . "/";
	
		/* export database  */
		$config = [];
		$config["user"] = DB_USER;
		$config["pass"] = DB_PASSWORD;
		$config["host"] = DB_HOST;
		$config["name"] = DB_NAME;
		$sq = new Netmow_backup_export_SQL();
		$sq->netmow_backup_pre_config_folder();
		$aftersql = $sq->netmow_backup_export($sql_file_path, $config, $nfilename);
	
		/* zip creation */
		if ($aftersql) {
			$za = new Netmow_backup_zip_archive();
			$res = $za->open($zip_file_name, Netmow_backup_zip_archive::CREATE);
			if ($res === true) {
				$za->netmow_backup_add_dir($the_folder, basename($the_folder));
				$za->close();
			} else {
				echo "Could not create a zip archive";
			}
		}
		// if ($res) {
		// 	netmow_backup_push_to_drive($today);
		// }
	}

	private function netmow_backup_create_backup(){
		if (isset($_POST["Submit1"])) {
			$this->netmow_backup_zip_and_push();
		}
	}

	public function netmow_backup_widgets_shortcode_init() {
		?>
		<div class="nb-page-wrap">
            <h1>Netmow Backup</h1>
			<div class="miyn-app-sidebar">
				<form id="ajaxformid" action="#" method="POST">
					<input type="submit" id="inviaForm" value="Send" name="Submit1">
				</form>
			</div>
        </div>
		<?php
	}

 }