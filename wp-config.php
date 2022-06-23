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
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'mellow_new' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

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
define( 'AUTH_KEY',         'H-})KJ$xa~1Ze4+s^b;5k4x+?RcqOm}>!{kzp+o.Tm,49/r(;qM0]6 0^L.<d-qs' );
define( 'SECURE_AUTH_KEY',  ')JYC$E~Z-fiz|;c$6a2,G$*^WA*6G`C@dz<BKp)KTwqclQEc/(uhOPh)!tqF#9[ ' );
define( 'LOGGED_IN_KEY',    '2-ROx:yv]0S0br@@4HFJC~;Yl$/.hG>$dS*]~TBU8aDL9s^&e_{7&`vHw^-XT+DJ' );
define( 'NONCE_KEY',        'l(J&e:ngyPm|X{=ShA?&0{LwL4I yrXT/)=/@<oJzx`$KiK7F $n&m`Kyk_ph<m-' );
define( 'AUTH_SALT',        'N{:z-Ls2R#+*(W/v=BZPADSN-1~!C=m@`NQSY+l%e!/Bzb2;{K~FQ>g@aOx^%.qc' );
define( 'SECURE_AUTH_SALT', 'I;Hp<**72gJ/N$@jpMEL0S5^sm=GTJSe{9nywUivW|FOzhp#nayIJk0<1ChGIx?G' );
define( 'LOGGED_IN_SALT',   '~*mR,D:;#MLGTM C6]%31zblGe7OV[&4/4Dy6Kr/yXb$Y&@mohoXP^%$`/n,V-`;' );
define( 'NONCE_SALT',       'Qppp=F`jeXlE_*qU^t$ZwD*:ZhUE%]]-5_OwC<#1{b2@DKN})+oT%8%f:-(@I}|C' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'msg641_';

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
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
