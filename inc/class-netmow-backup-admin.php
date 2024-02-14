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
		$this->netmow_backup_google_auth();
		$this->netmow_backup_google_keys();
		$this->netmow_backup_google_revoke();
		$this->add_header_xua();
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

	public function add_header_xua() {
		$home_url=home_url();
		header('Location: {$home_url}')
	}

	public function netmow_backup_google_auth(){
		include plugin_dir_path( __DIR__ ) . "net-config.php";

			// $home_url=home_url();
			// header('Location: /wp-admin/admin.php?page=netmow-backup');
			// $this->add_header_xua();
			// wp_safe_redirect( home_url( '/wp-admin/admin.php?page=netmow-backup' ) );
			// exit();
			// wp_redirect('http://example.com/custom', $status );

		if (isset($_GET["code"])) {
			
			$token = $google_client->fetchAccessTokenWithAuthCode($_GET["code"]);
		
			if (!isset($token["error"])) {
			
			$google_values = get_option( 'netmow_backup_google_account_data' );
			$accessToken = $google_values['g_access_token'];
			
			if($token["access_token"]){
				$google_client->setAccessToken($token["access_token"]);
			}else{
				$google_client->setAccessToken($accessToken);
			}
		
			$google_service = new Google_Service_Oauth2($google_client);
			$data = $google_service->userinfo->get();
			if (!empty($data["given_name"])) {
				$user_first_name = $data["given_name"];
			}
			if (!empty($data["family_name"])) {
				$user_last_name = $data["family_name"];
			}
			if (!empty($data["email"])) {
				$user_email_address = $data["email"];
			}
			if (!empty($data["gender"])) {
				$user_gender = $data["gender"];
			}
			if (!empty($data["picture"])) {
				$user_image = $data["picture"];
			}
			$gdata = array(
				'g_access_token'  => $token["access_token"],
				'user_first_name' => $user_first_name,
				'user_last_name' => $user_last_name,
				'user_email_address' => $user_email_address,
				'user_gender' => $user_gender,
				'user_image' => $user_image,
			);
			//entering data into options table
			update_option( 'netmow_backup_google_account_data', $gdata );
		
			echo '<h1>Auth Done From Google</h1>';
			// $home_url=home_url();
			// header('Location: /wp-admin/admin.php?page=netmow-backup');
			// $this->add_header_xua();
			
		
			}
		
		}
	}

	private function netmow_backup_google_keys(){
		if( isset($_POST['x_submit']) ) {
			echo '<h1>The name of your OAuth 2.0 client. This name is </h1>';
			$data = array(
					'clientid'  => sanitize_text_field( $_POST['clientid'] ),
					'clientsec' => sanitize_text_field( $_POST['clientsec'] ),
					'redirecturl'   => sanitize_text_field( $_POST['redirecturl'] )
				);
			//entering data into options table
			update_option( 'netmow_google_keys', $data );
		}
	}

	private function netmow_backup_google_revoke() {
		include plugin_dir_path( __DIR__ ) . "net-config.php";
		if (array_key_exists("revoke", $_POST)) {
			$google_client->revokeToken();
			session_destroy();
			delete_option('netmow_backup_google_account_data');
		}
	}

	public function netmow_backup_widgets_shortcode_init() {
		?>

			<div class="miyn-app-wrapper">
				<div class="miyn-app-content-wrapper">
					<div class="miyn-app-content-area">
							My backup Plugins	
					</div>
				</div>
				<div class="miyn-app-sidebar">
					<form id="ajaxformid" action="#" method="POST">
						<input type="submit" id="inviaForm" value="Send" name="Submit1">
					</form>
				</div>
			</div>
			<?php 
				$db_values = get_option( 'netmow_google_keys' );
				$clientid = '';
				$clientsec = '';
				$redirecturl = '';
				if( $db_values ) {
					$clientid = $db_values['clientid'] ? $db_values['clientid'] : '';
					$clientsec = $db_values['clientsec'] ? $db_values['clientsec'] : '';
					$redirecturl = $db_values['redirecturl'] ? $db_values['redirecturl'] : '';
				}
			?>
			<div class="api-inputs">
				<form method="post">
					<label>Client ID <input type="text" name="clientid" value="<?php echo !empty($clientid) ? $clientid : $clientid; ?>"></label><br>
					<label>Client secret <input type="text" name="clientsec" value="<?php echo !empty($clientsec) ? $clientsec : $clientsec; ?>"></label><br>
					<label>Authorised redirect URI <input type="text" name="redirecturl" value="<?php echo !empty($redirecturl) ? $redirecturl : $redirecturl; ?>"></label><br>
					<input type="submit" name="x_submit" value="Submit">
				</form>
			</div>

			<div class="api-auth" style="padding: 20px;margin-top: 20px;background-color: #fff">
				<h2>Google Account</h2>
				<div class="panel panel-default">

				<?php 
					include plugin_dir_path( __DIR__ ) . "net-config.php";

					$acc_valus = get_option( 'netmow_backup_google_account_data' );
					$g_access_token = '';
					$user_first_name = '';
					$user_last_name = '';
					$user_email_address = '';
					$user_image = '';
					if( $acc_valus ) {
						$g_access_token = $acc_valus['g_access_token'] ? $acc_valus['g_access_token'] : '';
						$user_first_name = $acc_valus['user_first_name'] ? $acc_valus['user_first_name'] : '';
						$user_last_name = $acc_valus['user_last_name'] ? $acc_valus['user_last_name'] : '';
						$user_email_address = $acc_valus['user_email_address'] ? $acc_valus['user_email_address'] : '';
						$user_image = $acc_valus['user_image'] ? $acc_valus['user_image'] : '';
					}
				?>

				<?php
				if(!empty($g_access_token))
				{
					echo '<div class="panel-heading">Welcome User</div><div class="panel-body">';
					echo '<img src="'.$user_image.'" class="img-responsive img-circle img-thumbnail" />';
					echo '<h3><b>Name :</b> '.$user_first_name.' '.$user_last_name.'</h3>';
					echo '<h3><b>Email :</b> '.$user_email_address.'</h3>';
					echo '<form method="post">';
					echo '<input type="submit" name="revoke" id="revoke" value="Logout" >';
					echo '</form>';
				}
				else
				{
				echo '<a href="' . $google_client->createAuthUrl() . '"><img src="' . plugin_dir_url( __DIR__ ) . 'assets/img/google.png" /></a>';
				}

				?>
				</div>

			</div>

		<?php
	}

 }