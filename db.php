<?php

/*
Plugin Name: Word DB Replacer
Plugin URI: http://wordpress.org/plugins/words/
Description: This plugin replaces some words with other words using access to DataBase.
Author: Lorenzo Nogueira
Version: 3.3
Author URI: https://ma.tt/
*/

//Create a 5 length array of football players
$fbPlayers = array(
    'Critiano Ronaldo',
    'Lionel Messi',
    'Neymar',
    'Kylian Mbappé',
    'Mohamed Salah');

//Create another 5 word length array of football stadiums
$stadiums = array(
    'San Siro',
    'Camp Nou',
    'Santiago Bernabéu',
    'Old Trafford',
    'Anfield');

function renym_wordpress_typo_fix($text){

    $words = selectData();
    foreach ($words as $result){
        $fbPlayers[] = $result->fbPlayers;
        $stadiums[] = $result->stadiums;
    }
    return str_replace($fbPlayers, $stadiums, $text);
}


add_filter('the_content', 'renym_wordpress_typo_fix');

function createTable(){
    global $wpdb;
    $table_name = $wpdb->prefix . 'damLorenzo';

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        cars varchar(255) NOT NULL,
        places varchar(255) NOT NULL,
        PRIMARY KEY (id)
    ) $charset_collate;";
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
}

add_action( 'plugins_loaded', 'createTable' );


function insertData(){
    global $wpdb, $stadiums, $fbPlayers;
    $table_name = $wpdb->prefix . 'damLorenzo';
    $hasSomething = $wpdb->get_results( "SELECT * FROM $table_name" );
    if ( count($hasSomething) == 0 ) {
        for ($i = 0; $i < count($stadiums); $i++) {
            $wpdb->insert(
                $table_name,
                array(
                    'fbPlayers' => $stadiums[$i],
                    'stadiums' => $fbPlayers[$i]
                )
            );
        }
    }
}

add_action( 'plugins_loaded', 'insertData' );

function selectData(){
    global $wpdb;
    $table_name = $wpdb->prefix . 'damLorenzo';
    $results = $wpdb->get_results( "SELECT * FROM $table_name" );
    return $results;
}