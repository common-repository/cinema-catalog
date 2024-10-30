<?php

/*
 * Plugin Name: Кинотеатр онлайн
 * Description: Импорт информации о фильмах и сериалах, вставка плеера на сайт.
 * Version:     1.1
 * Author:      Pavel Kolyshev
 * License: 	GPLv2
 */

global $wpdb;
require_once plugin_dir_path(__FILE__) ."includes/core.php";
add_option("api_key", "");

if( is_admin() ) { 
	require_once plugin_dir_path(__FILE__) ."includes/admin.php";
	$class = new CinemaMaster();
	$wpdb->hide_errors();
	
	$tables = $wpdb->get_results("SHOW TABLES LIKE '". $wpdb->prefix. "cinema';");
	if( count($tables) < 1) { 
		$wpdb->query("CREATE TABLE `". $wpdb->prefix ."cinema` (
					  `ID` int(255) NOT NULL,
					  `publication` tinyint(1) NOT NULL DEFAULT 0,
					  `poster_url` varchar(255) NOT NULL,
					  `name_ru` varchar(130) NOT NULL,
					  `name_en` varchar(130) NOT NULL,
					  `year` varchar(10) NOT NULL,
					  `description` longtext NOT NULL,
					  `country` varchar(50) NOT NULL,
					  `genres` longtext NOT NULL,
					  `kinopoisk` int(6) NOT NULL
					) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

		$wpdb->query("ALTER TABLE `". $wpdb->prefix ."cinema`
					  ADD PRIMARY KEY (`ID`);");

		$wpdb->query("ALTER TABLE `". $wpdb->prefix ."cinema`
					  MODIFY `ID` int(255) NOT NULL AUTO_INCREMENT;");
		
		add_action( 'admin_notices', array( "CinemaMaster", 'first_run') );
	}
	
	if( isset($_POST) AND @$_GET['edit'] == "load") {
		if( $class->new_catalog( $_POST ) ) { 
			add_action( 'admin_notices', array( "CinemaMaster",'load_notice') ); 
		}
	}
	
	if( isset($_POST) AND count($_POST) == 12 AND @$_POST['_action'] == "update") { 
		if($class->update_record( $_POST )) { header("Location: ". admin_url() ."admin.php?page=cinema&show=all"); } else {
			add_action( 'admin_notices', array( "CinemaMaster",'update_error') );
		}
	}
	
	if( isset($_POST) AND @$_POST['_action'] == "cheked") { 
		if($class->cheked_records( $_POST )) { header("Location: ". admin_url() ."admin.php?page=cinema&show=all"); } else {
			add_action( 'admin_notices', array( "CinemaMaster",'update_error') );
		}
	}
	
	add_action( "admin_menu", array("CinemaMaster", "manage_catalog") ); 
	
	add_filter( "admin_footer_text", array( "CinemaMaster", "plugin_footer" ) );
}

if( isset($_GET['cinema']) AND count($_GET) == 1) { 
	add_filter('template_include', array( "Cinema_Catalog", "single_cinema_watching" ));
}

?>
