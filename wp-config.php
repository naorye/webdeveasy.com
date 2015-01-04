<?php
// ===================================================
// Load database info and local development parameters
// ===================================================
include( dirname( __FILE__ ) . '/local-config.php' );

// ========================
// Custom Content Directory
// ========================
define( 'WP_CONTENT_DIR', dirname( __FILE__ ) . '/content' );
define( 'WP_CONTENT_URL', 'http://' . $_SERVER['HTTP_HOST'] . '/content' );

// ================================================
// You almost certainly do not want to change these
// ================================================
define( 'DB_CHARSET', 'utf8' );
define( 'DB_COLLATE', '' );

// ==============================================================
// Salts, for security
// Grab these from: https://api.wordpress.org/secret-key/1.1/salt
// ==============================================================
define( 'AUTH_KEY',         'P{..4+P_v+lvaEH5<H-MSMKJ2v?2Yob+J>9RFS/+=GN(gn.5Z>$S~X-Y^QB[(@Nq');
define( 'SECURE_AUTH_KEY',  'UeOpFe1p$e[+[8;S=jc81/i(,{+aD;N`>#&4|6X+W+@em-+*&.9$##qrEW8ILsWb');
define( 'LOGGED_IN_KEY',    '0:b:^-iivOlM+:q&><8oJ-VYf#xz9(&v;b]2=$oA)V=h2oV,d?^{$mSm(vsG+2|d');
define( 'NONCE_KEY',        'Vo9^}R^Yb,5S]VCU|V-4AvF`~U{Qqnq)_ovg|OHQL;u`jazHe{MdCI7izI0j?0!O');
define( 'AUTH_SALT',        'M*aDLNWOW^Ay<1-oaPW]&=@e^g}A=Shs+e7a&=?7p4(TsdCVgp?h@tu&!9b#$Y*}');
define( 'SECURE_AUTH_SALT', 's2u&C_1H|_F<TxC-=e-`r&pqevw}aW PuhRv|ZCW~zHuI7Q@ADo;awEB1:E-wW]]');
define( 'LOGGED_IN_SALT',   'r-C6HOuDrW+}kqR{>O0{Sv#tJ5b:4b1wIVe*HOd?D$/wlnmA&!s}Rw@|}C`)H,N&');
define( 'NONCE_SALT',       'CG}HxRo(+oTad-Utmor`DQG~wtx1FOk3Q:U|G>WlPnwO~C`?3IY)Kp{QGc-E6c0D');

// ==============================================================
// Table prefix
// Change this if you have multiple installs in the same database
// ==============================================================
$table_prefix  = 'ny_';

// ================================
// Language
// Leave blank for American English
// ================================
define( 'WPLANG', '' );

// ===========
// Hide errors
// ===========
ini_set( 'display_errors', 0 );
define( 'WP_DEBUG_DISPLAY', false );

// =================================================================
// Debug mode
// Debugging? Enable these. Can also enable them in local-config.php
// =================================================================
// define( 'SAVEQUERIES', true );
// define( 'WP_DEBUG', true );

// ======================================
// Load a Memcached config if we have one
// ======================================
if ( file_exists( dirname( __FILE__ ) . '/memcached.php' ) )
	$memcached_servers = include( dirname( __FILE__ ) . '/memcached.php' );

// ===========================================================================================
// This can be used to programatically set the stage when deploying (e.g. production, staging)
// ===========================================================================================
define( 'WP_STAGE', '%%WP_STAGE%%' );
define( 'STAGING_DOMAIN', '%%WP_STAGING_DOMAIN%%' ); // Does magic in WP Stack to handle staging domain rewriting

// ===================
// Bootstrap WordPress
// ===================
if ( !defined( 'ABSPATH' ) )
	define( 'ABSPATH', dirname( __FILE__ ) . '/wp/' );
require_once( ABSPATH . 'wp-settings.php' );
