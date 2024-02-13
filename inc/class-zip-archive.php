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

 
 class Netmow_backup_zip_archive extends ZipArchive {
    
    public function netmow_backup_add_dir($location, $name)
    {
        $this->addEmptyDir($name);
        $this->netmow_backup_add_dirDo($location, $name);
    }

    private function netmow_backup_add_dirDo($location, $name)
    {
        $name .= "/";
        $location .= "/";
        $dir = opendir($location);
        $dirr = opendir($location);

        while ($file = readdir($dir)) {
            if ($file == "." || $file == "..") {
                continue;
            }
            if ($file != "netmow_backup") {
                $do =
                    filetype($location . $file) == "dir" ? "netmow_backup_add_dir" : "addFile";
                $this->$do($location . $file, $name . $file);
            }
        }
    }

    public function netmow_backup_add_tables($wpdb)
    {
        $dbtables = $wpdb->get_results("SHOW TABLES");
        foreach ($dbtables as $dbtable) {
            foreach ($dbtable as $stable) {
                echo $stable . "<br>";
            }
        }
    }
}