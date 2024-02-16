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

 
class Netmow_backup_export_SQL {

    public function netmow_backup_export($the_folder, $config, $nfilename, $tables = "*")
    {
        $host = $config["host"];
        $user = $config["user"];
        $pass = $config["pass"];
        $name = $config["name"];
        $link = mysqli_connect($host, $user, $pass, $name);
        $db = mysqli_select_db($link, $name);

        //get all of the tables
        if ($tables == "*") {
            $tables = [];
            $result = mysqli_query($link, "SHOW TABLES");
            while ($row = mysqli_fetch_row($result)) {
                $tables[] = $row[0];
            }
        } else {
            $tables = is_array($tables) ? $tables : explode(",", $tables);
        }

        $return = "";

        foreach ($tables as $table) {
            $result = mysqli_query($link, "SELECT * FROM " . $table);
            $num_fields = mysqli_num_fields($result);

            $return .= "DROP TABLE " . $table . ";";
            $row2 = mysqli_fetch_row(
                mysqli_query($link, "SHOW CREATE TABLE " . $table)
            );
            $return .= "\n\n" . $row2[1] . ";\n\n";

            for ($i = 0; $i < $num_fields; $i++) {
                while ($row = mysqli_fetch_row($result)) {
                    $return .= "INSERT INTO " . $table . " VALUES(";
                    for ($j = 0; $j < $num_fields; $j++) {
                        $row[$j] = addslashes($row[$j]);
                        if (isset($row[$j])) {
                            $return .= '"' . $row[$j] . '"';
                        } else {
                            $return .= '""';
                        }
                        if ($j < $num_fields - 1) {
                            $return .= ",";
                        }
                    }
                    $return .= ");\n";
                }
            }
            $return .= "\n\n\n";
        }

        $handle = fopen($the_folder . $nfilename . ".sql", "w+");
        fwrite($handle, $return);
        fclose($handle);

        return true;
    }

    public function netmow_backup_pre_config_folder()
    {
        $today = date("d-M-Y-H-i-s");

        $nb_directory = WP_CONTENT_DIR . "/netmow-backup/" . $today . "/";
        if (!is_dir($nb_directory)) {
            if (false === @mkdir($nb_directory, 0777, true)) {
                throw new \RuntimeException(
                    sprintf(
                        "Unable to create the %s directory",
                        $nb_directory
                    )
                );
            }
        }

        $miyn_db = WP_CONTENT_DIR . "/netmow-backup/" . $today . "/";
        if (!is_dir($miyn_db)) {
            if (false === @mkdir($miyn_db, 0777, true)) {
                throw new \RuntimeException(
                    sprintf("Unable to create the %s directory", $miyn_db)
                );
            }
        }
    }

    public function netmow_backup_unlink_sql($sql_file_path, $nfilename)
    {
        unlink($sql_file_path . $nfilename . ".sql");
    }
}