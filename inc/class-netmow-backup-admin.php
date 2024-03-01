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
		$this->netmow_backup_cron_init();
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

	private function netmow_backup_recursive_remove($dir) {
		$structure = glob(rtrim($dir, "/").'/*');
		if (is_array($structure)) {
			foreach($structure as $file) {
				if (is_dir($file)) netmow_backup_recursive_remove($file);
				elseif (is_file($file)) unlink($file);
			}
		}
		rmdir($dir);
	}



	public function netmow_backup_push_to_drive($today) {
		$db_values = get_option( 'netmow_google_keys' );
		$clientid = '';
		$clientsec = '';
		$redirecturl = '';
		
		
		if( $db_values ) {
			$clientid = $db_values['clientid'] ? $db_values['clientid'] : '';
			$clientsec = $db_values['clientsec'] ? $db_values['clientsec'] : '';
			$redirecturl = $db_values['redirecturl'] ? $db_values['redirecturl'] : '';
		
			// require_once WP_PLUGIN_DIR . '/netmow-backup/google-api-php-client/vendor/autoload.php';
			// $client = new Google_Client();
			// $client->setClientId($clientid);
			// $client->setClientSecret($clientsec);
			// $client->setRedirectUri($redirecturl);
			// $client->setAccessType("offline");
			// $client->setApprovalPrompt('force');
			// $client->addScope("https://www.googleapis.com/auth/drive");

			include plugin_dir_path( __DIR__ ) . "net-config.php";

	
			// $google_values = get_option( 'netmow_backup_google_account_data' );
			// $accessToken = $google_values['g_access_token'];

			// Refresh the token if it's expired.
			// if ($google_client->isAccessTokenExpired()) {
			// 	$accFromrefre = $google_client->fetchAccessTokenWithRefreshToken($accessToken);
			// 	echo '<h1>New Refress token has been made ppp:</h1>'. $accFromrefre;
			// }else{
			// 	$accFromrefre = $accessToken;
			// }

			// $google_client->setAccessToken($accFromrefre);
			// $google_client->fetchAccessTokenWithRefreshToken($accessToken);
			$google_client->setAccessToken('ya29.a0AfB_byDNhpajsgjslWYEAtDrEzSGswLTyFCW2VUOq6thpD8ofaSNwxnE4ItMal-74Cq-9wAsbTl96mS9Sv0ntSurubwbsDiVs-kFMk1TmC3yPrKpZskZgRE3XmHzG51TmJDhY_9UBOXzoktOtH9csZQXsJFRc5lVN_UaCgYKATwSARESFQHGX2Mi3SRWnNYcDw56Lic8PkZTyw0170');

			$google_service = new Google_Service_Oauth2($google_client);

			$service = new Google_Service_Drive($google_client);
	
			$rootFolderID = "root";
			$name = "Netmow Backup";
			$optParams = [
				"pageSize" => 100,
				"fields" =>
					"nextPageToken, files(id, name, mimeType, modifiedTime, size, parents)",
				"q" =>
					"mimeType = 'application/vnd.google-apps.folder' and name='" .
					$name .
					"' and '" .
					$rootFolderID .
					"' in parents",
			];
	
			$results = $service->files->listFiles($optParams);
			$cfolder = $results[0]->name;
			$cfolderID = $results[0]->id;
	
			if (!empty($cfolder)) {
				$parent = $cfolderID;
				print "Found Folder ID: " . $parent;
			} else {
				$file = new Google_Service_Drive_DriveFile([
					"name" => "Netmow Backup",
					"mimeType" => "application/vnd.google-apps.folder",
				]);
	
				$optParams = [
					"fields" => "id",
					"supportsAllDrives" => true,
				];
	
				$createdRootFolder = $service->files->create($file, $optParams);
				$parent = $createdRootFolder->id;
	
				print "Created Folder: " . $createdRootFolder->id;
			}
	
			//Create Date folder
			$file = new Google_Service_Drive_DriveFile([
				"name" => $today,
				"mimeType" => "application/vnd.google-apps.folder",
				"driveId" => $parent,
				"parents" => [$parent],
			]);
	
			$optParams = [
				"fields" => "id",
				"supportsAllDrives" => true,
			];
	
			$createdDateFolder = $service->files->create($file, $optParams);
			print "<br> Created Date Folder: " . $createdDateFolder->id;
	
			//Add SQL file to the new folder
			$sqlFileMetadata = new Google_Service_Drive_DriveFile([
				"name" => "backup-" . $today . ".sql",
				"parents" => [$createdDateFolder->id],
			]);
			$sqlContent = file_get_contents(
				WP_CONTENT_DIR . "/netmow-backup/" . $today . "/backup-" . $today . ".sql"
			);
			$sqlFile = $service->files->create($sqlFileMetadata, [
				"data" => $sqlContent,
				"mimeType" => "application/octet-stream",
				"uploadType" => "multipart",
			]);
			echo "<br>SQl File ID: " . $sqlFile->id;
	
			//Add Zip file to the new folder
			$zipFileMetadata = new Google_Service_Drive_DriveFile([
				"name" => "backup-" . $today . ".zip",
				"parents" => [$createdDateFolder->id],
			]);
			$ziplContent = file_get_contents(
				WP_CONTENT_DIR . "/netmow-backup/" . $today . "/backup-" . $today . ".zip"
			);
			$zipFile = $service->files->create($zipFileMetadata, [
				"data" => $ziplContent,
				"mimeType" => "application/octet-stream",
				"uploadType" => "multipart",
			]);
			echo "<br>Zip File ID: " . $zipFile->id;
	
			if($sqlFile->id && $zipFile->id){
				$this->netmow_backup_recursive_remove(WP_CONTENT_DIR . "/netmow-backup/" . $today);
			}
	
		}
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
		if ($res) {
			$this->netmow_backup_push_to_drive($today);
		}
	}

	private function netmow_backup_create_backup(){
		if (isset($_POST["Submit1"])) {
			$this->netmow_backup_zip_and_push();
		}
	}

	public function netmow_backup_redirect_to() {
		$nbRedirect = get_admin_url() . 'admin.php?page=netmow-backup';;
		echo '<script type="text/javascript">window.location.replace("' . $nbRedirect . '");</script>';
	}

	public function netmow_backup_google_auth(){
		include plugin_dir_path( __DIR__ ) . "net-config.php";

		if (isset($_GET["code"])) {
			
			$token = $google_client->fetchAccessTokenWithAuthCode($_GET["code"]);
		
			if (!isset($token["error"])) {
			
				$google_client->setAccessToken($token["access_token"]);

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
				$this->netmow_backup_redirect_to();

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
			// session_destroy();
			delete_option('netmow_backup_google_account_data');
		}
	}

	public function netmow_backup_widgets_shortcode_init() { ?>

		<div class="nb-page-wrapper">
			<div class="nb-header">
				<div class="nb-header-left">
					<img src="<?php echo plugin_dir_url( __DIR__ ).'/assets/img/netmow-backup.png'; ?>" alt="Logo">
					<div class="nb-header-left-text">Netmow Backup <span>- Auto Backup To Google Drive</span></div>
				</div>
				<div class="nb-header-right">
					<a href="#" class="nb-btn">
						<div class="icon">
							<svg width="2rem" height="2.8rem" viewBox="0 0 20 28" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M19.7 7.3L12.7 0.3C12.5 0.0999999 12.3 0 12 0H2C0.9 0 0 0.9 0 2V26C0 27.1 0.9 28 2 28H18C19.1 28 20 27.1 20 26V8C20 7.7 19.9 7.5 19.7 7.3ZM12 2.4L17.6 8H12V2.4ZM18 26H2V2H10V8C10 9.1 10.9 10 12 10H18V26Z" fill="#3c434a"/>
							</svg>
						</div>
						<span>Documentation</span>
					</a>
				</div>
			</div>
			<div>Access token: <?php var_dump(get_option( 'netmow_backup_google_account_data' )['g_access_token']); ?></div>
			<div class="nb-page-content">
				<div class="nb-body-wrap">
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

						$authValus = get_option( 'netmow_backup_google_account_data' );
						
					?>
					<?php if(!empty($clientid) && !empty($clientsec) && !empty($redirecturl) && !empty($authValus['g_access_token'])) { ?>
					<div class="nb-widget">
						<div class="nb-data-wrap">
							<div class="nb-data-left">
								<h2 class="nb-widget-title">Latest Backups</h2>
								<div class="nb-data-items">
									<div class="nb-data-line nb-data-line-b">
										<span class="nbdl-t1">Time</span>
										<span class="nbdl-t2">Status</span>
									</div>
									<div class="nb-data-line">
										<span class="nbdl-t1">22-Feb-2024-02-35-22</span>
										<span class="nbdl-t2">Done</span>
									</div>
									<div class="nb-data-line">
										<span class="nbdl-t1">22-Feb-2024-02-35-22</span>
										<span class="nbdl-t2">Done</span>
									</div>
									<div class="nb-data-line">
										<span class="nbdl-t1">22-Feb-2024-02-35-22</span>
										<span class="nbdl-t2">Done</span>
									</div>
									<div class="nb-data-line">
										<span class="nbdl-t1">22-Feb-2024-02-35-22</span>
										<span class="nbdl-t2">Done</span>
									</div>
									<div class="nb-data-line">
										<span class="nbdl-t1">22-Feb-2024-02-35-22</span>
										<span class="nbdl-t2">Done</span>
									</div>
								</div>
							</div>
							<div class="nb-data-right">
								<form id="ajaxformid" action="#" method="POST" class="nb-backup-now">
									<input type="submit" id="inviaForm" value="Backup Now" name="Submit1">
								</form>
							</div>
						</div>
						<div class="nb-widget-settings">
							<div class="nbws-status">
								<h2>Testt</h2>
							</div>
						</div>
					</div>
					<?php } ?>

					<?php if(!empty($clientid) && !empty($clientsec) && !empty($redirecturl)) { ?>
					<div class="nb-widget">
						<h2 class="nb-widget-title">Google Account</h2>
						<div class="nb-widget-body">
							<div class="nb-profile-area">
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

								if(!empty($g_access_token)) { ?>
								<div class="nb-profile-info">
									<div class="nb-avatar">
										<img src="<?php echo $user_image; ?>" alt="<?php echo $user_first_name; ?>">
									</div>
									<div class="nb-info">
										<div class="nb-info-items">
											<div class="nb-info-s">Name</div>
											<div class="nb-info-m"><?php echo $user_first_name.' '.$user_last_name; ?></div>
										</div>
										<div class="nb-info-items">
											<div class="nb-info-s">Email</div>
											<div class="nb-info-m"><?php echo $user_email_address; ?></div>
										</div>
										<div class="nb-revoke-button">
											<form method="post">
												<input type="submit" name="revoke" id="revoke" value="Logout" >
											</form>
										</div>
									</div>
								</div>
								<?php }else{ ?>
								<div class="nb-login-google">
									<a href="<?php echo $google_client->createAuthUrl(); ?>">
										<img src="<?php echo plugin_dir_url( __DIR__ ) . 'assets/img/google.png'; ?>" alt="Login With Goolgle">
									</a>
								</div>
								<?php } ?>
							</div>
						</div>
					</div>
					<?php } ?>
					
					<div class="nb-widget">
						<h2 class="nb-widget-title">Google API</h2>
						<div class="nb-widget-body">
							<div class="nb-widget-inputs">
								<form method="post">
									<label>
										<span>Client ID:</span>
										<input type="text" name="clientid" value="<?php echo !empty($clientid) ? $clientid : $clientid; ?>">
									</label>
									<label>
										<span>Client secret:</span>
										<input type="text" name="clientsec" value="<?php echo !empty($clientsec) ? $clientsec : $clientsec; ?>">
									</label>
									<label>
										<span>Authorised redirect URI:</span>
										<div class="nbwi-notice-input">
											<input type="text" name="redirecturl" value="<?php echo home_url(); ?>">
											<div class="nbwi-notice">You need to input <strong><?php echo home_url(); ?></strong> into the <strong>Authorised redirect URIs</strong> field of your Google Cloud Project.</div>
										</div>
									</label>
									<input type="submit" name="x_submit" value="Add">
								</form>
							</div>
						</div>
					</div>
				</div>
				<div class="nb-sidebar">
					<div class="nb-sidebar-widget">
						<h2 class="nb-widget-title">User Guidelines</h2>
						<div class="nb-sidebar-widget-yt">
							<iframe width="350" src="https://www.youtube.com/embed/URKxXv--2Zc?si=0n1f2pH6C8ngLAKf" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
						</div>
					</div>
				</div>
			</div>
		</div>

		<?php
	}

	// CRON

	public function prefix_add_cron_schedules( $schedules = array() ) {
		$schedules['every_1_min'] = array(
		'interval' => 600, // 600 seconds means 10 minutes.
		'display' => __( 'Every 5 Min', 'textdomain' ),
		);
		return $schedules;
	}

	public function prefix_add_scheduled_event() {
		// Schedule the event if it is not scheduled.
		if ( ! wp_next_scheduled( 'prefix_cron_hook' ) ) {
		wp_schedule_event( time(), 'every_1_min', 'prefix_cron_hook' );
		}
	}
	
	public function prefix_cron_task() {
		$this->netmow_backup_zip_and_push();
	}

	public function netmow_backup_cron_init() {
		add_action( 'cron_schedules',  array( $this, 'prefix_add_cron_schedules' ) );
		add_action( 'admin_init',  array( $this, 'prefix_add_scheduled_event' ) );
		add_action( 'prefix_cron_hook',  array( $this, 'prefix_cron_task' ) );

		$time = wp_next_scheduled('prefix_cron_hook');
		echo '<h1>'.get_date_from_gmt( date('Y-m-d H:i:s', $time) ).'</h1>';
	}

 }