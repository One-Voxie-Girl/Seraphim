<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'local' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'root' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

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
define( 'AUTH_KEY',          '3Ys$gM&cP)2Lp:.0PU$g7yV*g8W/gu<p5#iVE~os:PZ<fp4@S Li}/gtzddTL[ ^' );
define( 'SECURE_AUTH_KEY',   'W7QCg)4p.n`-CFMv_=s+hF-*2qi@qsB5(kY`m6-g4B^=nbnRo@kRdEWWPm%Dd6rY' );
define( 'LOGGED_IN_KEY',     ' hi06b|J/4*Y!hfL|A*mLgQfFSx;{yG5&NmQ.-f#lQK!j:]<,DNsgWg*eJ{68;8S' );
define( 'NONCE_KEY',         '`+,@uG#Ox&#W5_z=;_X{4l`Zf]5p0_?9c+YXN.^BRPH)<5PJ7Z4*K;^j/tidCK[e' );
define( 'AUTH_SALT',         '&ZY?$k)%y]KoH^popK[(/ 0I5Kf@i>)T[(jD.v*yJqJWP^^r,(e0V|LY2vd2g:%6' );
define( 'SECURE_AUTH_SALT',  '_}W{RHNH-5o]c<]{ZJVF~oRWa?go|)rVLpPm:sAy.9^vnBiETh7m+G^plATb4?*i' );
define( 'LOGGED_IN_SALT',    'a:RXN4Lpq6grE+?n*r(>9Z5ns{=zX?Qw>VM409{H,$wUm,.?|sUKF3^o1dN5QVK#' );
define( 'NONCE_SALT',        'Y PFe%csm[Vd<$w;y%jIkuCN#9lAZ(>I4?A=8$vgD,gH:L|`C54eYSPGw9&}wc*_' );
define( 'WP_CACHE_KEY_SALT', 'd,@jp;$S+!0VriyOMv%BA%2EJ.+|+)nCViwS?qK>,/NMR8tQ1;]3Yf$KzbV2%`;Q' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';


/* Add any custom values between this line and the "stop editing" line. */



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
if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', false );
}

define( 'WP_ENVIRONMENT_TYPE', 'local' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
