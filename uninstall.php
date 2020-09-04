<?php

//Uninstall Plugin

defined('ABSPATH') or die("Bye bye");

register_deactivation_hook( __FILE__, 'my_plugin_remove_database' );

function my_plugin_remove_database() {
     global $wpdb;
     $table_name = $wpdb->prefix . 'defer_css';
     $sql = "DROP TABLE IF EXISTS $table_name";
     $wpdb->query($sql);
     delete_option("my_plugin_db_version");
}

?>