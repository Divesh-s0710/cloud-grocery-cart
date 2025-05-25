<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wpdb' );

/** Database username */
define( 'DB_USER', 'wpuser' );

/** Database password */
define( 'DB_PASSWORD', 'Liverpool1234' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'X`ywod5yqFlS#1UE-smb-<:2/t0yke!e|AZG7ie`dROM+sd>c6e_(2RQ{-ya[fL<');
define('SECURE_AUTH_KEY',  '.f,ktE:O(I0N):y{FZMhn148YCG|OxWP01pWo{~7|xO{y8JG.O|sRxA6|c.Hqj {');
define('LOGGED_IN_KEY',    '0m),1VEOIDx--#2XQ0!)5t#n6~#`:q3|KnT.1j~R6](S|fND>)L,L]|%k*G&Guu-');
define('NONCE_KEY',        'oPb$xTC3FshSx/4B*HdH^~< `wpSY,(WaZ%6gK   y@g[>jmU|oziGEX}5cyelka');
define('AUTH_SALT',        '6iY~__GYl-#xW[{cRjm30wlYkf[S4|DuX>b?K+o_2d[o+Ji~&&s][SFq dq:?kC;');
define('SECURE_AUTH_SALT', '>t+c8?H|tKfVrH,/ZWvCFcM#^Iyzwjx7U9EBJ|1^!a,8d`).{WDK*YsO)fJ{T&f8');
define('LOGGED_IN_SALT',   'SD.N[|dqiZ_u]7XAG@)vbE.8)K6&DV(CIhJnXE7oD+D-6yZt@awAW=b0RE:f(,ln');
define('NONCE_SALT',       'lIj2(k{9Q[<Yh )SQK>xqUc7IodNapXpbI4dr=vGKBWcQFJ>lgmnMw|jT#mEt6PC');

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
