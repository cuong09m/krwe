<?php
/** Enable W3 Total Cache */
define('WP_CACHE', true); // Added by W3 Total Cache

/**
* The base configurations of the WordPress.
*
* This file has the following configurations: MySQL settings, Table Prefix,
* Secret Keys, WordPress Language, and ABSPATH. You can find more information
* by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
* wp-config.php} Codex page. You can get the MySQL settings from your web host.
*
* This file is used by the wp-config.php creation script during the
* installation. You don't have to use the web site, you can just copy this file
* to "wp-config.php" and fill in the values.
*
* @package WordPress
*/

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'keesings');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'megasoft');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

// define("LOCALHOST", true);

/**#@+
* Authentication Unique Keys and Salts.
*
* Change these to different unique phrases!
* You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
* You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
*
* @since 2.6.0
*/
define('AUTH_KEY',         'EYQ@Z n`wl7qXYr`zkVdR5gT+$KB+`k6dJ&24;Bmg36*8,|~|vDgNc$vR5sa/@))');
define('SECURE_AUTH_KEY',  'q)U?IUoy]TQFML[ I1+!afI];O:zUNT%>_|LGuODq2gMvtd84~].WO7fB~St2|R-');
define('LOGGED_IN_KEY',    'd(fxKqY`]mo07UK/NNSGA~wIES`RC J!t^f4,Rx?IsfZO$YX0H y5@b)2F^d0hTj');
define('NONCE_KEY',        'v{cv(+pyM5<V@FINtSY;E< ~B>scde/zWQ;0I|?^|cFf$*KG5kn1Ql`mBa8dUidC');
define('AUTH_SALT',        'K)F+P]EkJ@Jvl,d3#pIK:wJBgXE3 C=`u]CG_hN6i9U4 kSY6&+LrmG#!amX*{]O');
define('SECURE_AUTH_SALT', 'H6ZjMTpgHda{Hf^^Xc)x&T@@VGa{{$C`|M,:u8QbO8j>n1)L&}WzA@pQ> wM(F_]');
define('LOGGED_IN_SALT',   'j]+J5<J% UV(.46$A/y/DYQRl%GFg_jY/5_qgU?`].QD>;i~{?2JBinrMf:(Mb88');
define('NONCE_SALT',       'ex}C)fW2pDLY@v!^eG>m{x1x9@ur] CH_;M`DsiI5gp+}8*mQqG4CufD1^0W!]Tp');

/**#@-*/

/**
* WordPress Database Table prefix.
*
* You can have multiple installations in one database if you give each a unique
* prefix. Only numbers, letters, and underscores please!
*/
$table_prefix  = 'wp_';

/**
* WordPress Localized Language, defaults to English.
*
* Change this to localize WordPress. A corresponding MO file for the chosen
* language must be installed to wp-content/languages. For example, install
* de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
* language support.
*/
define('WPLANG', '');

/**
* For developers: WordPress debugging mode.
*
* Change this to true to enable the display of notices during development.
* It is strongly recommended that plugin and theme developers use WP_DEBUG
* in their development environments.
*/
define('WP_DEBUG', false);


define('WP_ALLOW_MULTISITE', true);
define('MULTISITE', true);
define('SUBDOMAIN_INSTALL', true);
define('DOMAIN_CURRENT_SITE', 'recordofworldevents.com');
define('PATH_CURRENT_SITE', '/');
define('SITE_ID_CURRENT_SITE', 1);
define('BLOG_ID_CURRENT_SITE', 1);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
define('ABSPATH', dirname(__FILE__) . '/');

define( 'SUNRISE', 'on' );

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

