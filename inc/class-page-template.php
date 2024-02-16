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

class Netmow_backup_page_template {

	// public function netmow_backup_add_admin_pages() {
    //     add_menu_page(__('Netmow Backup'), __('Netmow Backup'), 'manage_options', 'netmow-backup', [$this, 'netmow_backup_widgets_shortcode_init'], plugin_dir_url( __DIR__ ).'/assets/img/netmow-backup.png' );
	// }

	public function netmow_backup_widgets_shortcode_init() {
		?>
			<div class=""></div>


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