<?php
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
define('DB_NAME', 'satlujpu_satlujfinal');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '}UAVhvvMtd*QP*tFCvLdwyTb6_VR7?y Z-(jI7=V$sTOv~&~Qh $V7kb^+=LvQ+e');
define('SECURE_AUTH_KEY',  '@5CSi|a/|8RI7)6)rfI#cDFa &iY|Jh6%-.)t+>=C.z|/{ S=+1kJttkGE? Su/7');
define('LOGGED_IN_KEY',    'Zsnm_ldcCmZ&1aYgL!Uy=-e}]x[qZyS.@e7!=I7dQtCA%h`BkoLuPk-)3(wRDP2a');
define('NONCE_KEY',        '6.#fn]!FGyGBO~4OXK){9tTp*CJAp$;J:4ep. uM&XTp*fN#N@F6a5a+Fv9j(p|s');
define('AUTH_SALT',        'zd!o<-TF>o7D`vEyz+_YDpK|61Txu[PZUr-0_MzC#d4~C=22M/CP|dwc9#?XXchd');
define('SECURE_AUTH_SALT', '_$R([^{gV|K,L`@|;3|z+:ap6kUFQX!HQ53Z*Ffk}VYz->~*@@++O^MkM(EFX Q%');
define('LOGGED_IN_SALT',   '>45 aoTix4RhIL*b>Q}gGw<GrXkgAgB]M`/}kJ*s=:q@NM0m@yZb LY{$&0R-|I}');
define('NONCE_SALT',       'UYF@,zzcI42|oJ@=KI}}$%dr%59ShpE- ?mUS}~1.1E+%/)Rp`=)=HElD|j*[p4r');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'sat9Qy_';

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

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
