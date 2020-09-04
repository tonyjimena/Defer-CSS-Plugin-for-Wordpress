<?php

defined('ABSPATH') or die("Bye bye");

//Añadiendo plugin al panel

function defer_register_plugin() {
	add_menu_page(
		__( 'defer_css', 'textdomain' ),
		'Defer CSS', // nombre
		'manage_options', //acceso para administradores
		'defer_css',
		'defer_menu_page',
		'dashicons-media-code',
		null
	);
}

// Pagina principal del menu admin

function defer_menu_page() {

	echo '<div id="iframeContainer"></>
		  <div class="adminPluginContainer" id="mainContainer"></div>';
}

add_action( 'admin_menu', 'defer_register_plugin' ); //en el menu de admin

//Carga estilos del panel de admin

function defer_style() {
	if($_GET['page'] == 'defer_css') {
		wp_enqueue_style('defer-styles', plugin_dir_url( __FILE__ ) . 'public/css/defer.css');
	}
  }
  add_action('admin_enqueue_scripts', 'defer_style');

//Carga Javascript del panel de admin

function defer_enqueue_script_2() {

    wp_register_script(
        'defer-js', //manejador
        plugin_dir_url( __FILE__ ) . 'public/js/defer-script.js', // script
        array('jquery'), // dependencias
        time(), // Version
        false // Footer(True) o Header(False)
    );

	if($_GET['page'] == 'defer_css') { // carga en la pagina del plugin insertado
    	wp_enqueue_script('defer-js'); 
	}
}

add_action( 'admin_enqueue_scripts', 'defer_enqueue_script_2' ); //en el menu de admin


?>