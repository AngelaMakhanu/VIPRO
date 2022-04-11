<?php
define('WP_AUTO_UPDATE_CORE', 'minor');// This setting is required to make sure that WordPress updates can be properly managed in WordPress Toolkit. Remove this line if this WordPress website is not managed by WordPress Toolkit anymore.
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */
define('FORCE_SSL_ADMIN', true);


// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('WP_CACHE', true);
define( 'WPCACHEHOME', '/home/thevirtu/public_html/wp-content/plugins/wp-super-cache/' );
define( 'DB_NAME', 'thevirtu_vipro' );

/** MySQL database username */
define( 'DB_USER', 'thevirtu_root' );

/** MySQL database password */
define( 'DB_PASSWORD', 'Vipro@2020' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**define( 'FS_METHOD', 'direct' );*/

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '*,K#cG<J~mQ;haP`i#i<H0ckmq)7-`GF@C-zL=JAa{ClruHVYU,SSUM:FAr?VUHr' );
define( 'SECURE_AUTH_KEY',  'O_4Jvgu^A~x@H,Hv]!]CH& HsaK3T7`mRE(bti*0lKuY)7_Sw<+o[?{iixPN,k}q' );
define( 'LOGGED_IN_KEY',    '[( y[xE[iF4nu}?*!&;fU[Is67MatzD%j!>6)z1/)XTIXpe+`$aWFOa5[g26ul*.' );
define( 'NONCE_KEY',        'nC63oQIU:.Sio^VVQl3fLkP5My$x!fZ@qD45Vs_|:$V%SG/-Jg^@pf[2P7d1G_I[' );
define( 'AUTH_SALT',        '+];,ejF|CDX1EiybU$vQL0eV87_]y{BmNkS~%rsf}D/W%wyyl*n_iPZaA]6sUKXK' );
define( 'SECURE_AUTH_SALT', 'vb{E;TBa{e:X>>tM$G#M(-[UYF%B;vsW=:}/}YyQqj,e*~2N26l$GA?U0ckA%Du_' );
define( 'LOGGED_IN_SALT',   '| p5[768,~wfE|8kJRA%))>!7=pRzD3hmi34O7mOc8/G#C]wZ<9Raq+Gm#tdvRGw' );
define( 'NONCE_SALT',       'F^7f4EXw[+U{@W,i;r1_?gSm5so-];b0FX;djK@}l[pOr=^1?rRZGAq%~]P:[0N6' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
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
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
// Enable WP_DEBUG mode
define( 'WP_DEBUG', true );

// Enable Debug logging to the /wp-content/debug.log file
define( 'WP_DEBUG_LOG', true );

// Disable display of errors and warnings
define( 'WP_DEBUG_DISPLAY', false );
@ini_set( 'display_errors', 0 );

// Use dev versions of core JS and CSS files (only needed if you are modifying these core files)
define( 'SCRIPT_DEBUG', true );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
