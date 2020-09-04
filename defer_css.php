<?php
/*
Plugin Name: Defer CSS
Plugin URI: tony
Description: Haz defer a todo el CSS de tu web para ganar en SEO y rendimiento
Version: 1.0
Author: tony
Author URI: tony
License: GPL2
*/

// If this file is called directly, abort.
if(!defined('WPINC')){ die; }
defined('ABSPATH') or die("Bye bye");

define('DEFER_RUTA',plugin_dir_path(__FILE__));

include(DEFER_RUTA . 'admin.php');
//include(DEFER_RUTA . 'uninstall.php');

//Install Plugin
/* Crear base de datos*/

function wp_create_table_install()
{
    global $wpdb;

    $table_name = $wpdb->prefix.'defer_css';
    $sql = 'CREATE TABLE IF NOT EXISTS '.$table_name.'(
        id INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
		name VARCHAR(75),
		link VARCHAR(100)
    );';

    require_once(ABSPATH.'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

register_activation_hook(__FILE__,'wp_create_table_install');

//Uninstall 

register_deactivation_hook( __FILE__, 'my_plugin_remove_database' );

function my_plugin_remove_database() {
     global $wpdb;
     $table_name = $wpdb->prefix . 'defer_css';
     $sql = "DROP TABLE IF EXISTS $table_name";
     $wpdb->query($sql);
     //delete_option("my_plugin_db_version");
}

// Inserta datos en la la tabla Defer_css cuando hacemos la peticion ajax
add_action( 'wp_ajax_my_action', 'my_action' );

function my_action() {
	global $wpdb; // this is how you get access to the database

    $table_name = $wpdb->prefix.'defer_css';

	$names = $_POST['ids'];
    $links = $_POST['hrefs'];
    
    $cnames = count($names);
		
	for($x = 0; $x < $cnames; $x++){
		$str[] = "('{$names[$x]}','{$links[$x]}')";
	}
	$s = implode(',',$str);

    $wpdb->query("TRUNCATE TABLE $table_name");

	$wpdb->query("INSERT INTO $table_name (name, link) VALUES $s;");
    
    echo "okey";
	wp_die(); // this is required to terminate immediately and return a proper response
}

// Funcion final de movimiento

function my_action_2() {

	// QUITO HEAD
	function quitar_css() {

		global $wpdb; // this is how you get access to the database
			
			$table_name = $wpdb->prefix.'defer_css';

			$ids = $wpdb->get_results("SELECT name FROM $table_name");

			for ($i=0; $i < count($ids); $i++) { 
				if(isset($ids[$i])) {

				wp_dequeue_style($ids[$i]);
				wp_deregister_style($ids[$i]);
				}
			}

	};
	add_action('wp_enqueue_scripts','quitar_css',3000);


	function sol_css_footer() {
		global $wpdb;
    
		$table_name = $wpdb->prefix.'defer_css';

		$names = $wpdb->get_results("SELECT name FROM $table_name", ARRAY_N);
		$links = $wpdb->get_results("SELECT link FROM $table_name", ARRAY_N);
		

		for ($i=0; $i <= 9; $i++) {
				wp_enqueue_style($names[$i][0], $links[$i][0], array(), wp_get_theme()->get('Version') );
		};

	};
	
	add_action( 'get_footer', 'sol_css_footer' );

}
my_action_2();
