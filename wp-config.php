<?php
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

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'perfumes' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'QMP8/S`Z<vOG; t{tsN)Noa>=Vh?fUh!ndBKRnZ:=n:vK;;kRP&Tgryc/1?N<7#d' );
define( 'SECURE_AUTH_KEY',  'C0)h;>:Ci,D2b;1;Pt^n#zE#&VkL_k{WPmW%$m)c9Z_!i/#l*d^of48O%O3o_`ps' );
define( 'LOGGED_IN_KEY',    'ad)PFg!M{<*1ITP2T8sBaRu07#?X7b@45Gv&R ~.NPa[[%s+cIEtWvS=4I+nKxgg' );
define( 'NONCE_KEY',        '$@+oH3mm_(@LW4a|H[c`Sb^:tow]sL?* meFGwb0WcI F~Xk7dBnm*4#kRhpv1(J' );
define( 'AUTH_SALT',        'x?WT)Lv3mkcwqE74`4R+O*oC<dS~4n7.B}qSD[h!O,pk*W<^E}6tGX${6:F$]>)E' );
define( 'SECURE_AUTH_SALT', '.;#|VpD&5-/QCVf.d-Nb>!!OWZ2.QkRD:^^g],vB}qO7Hh$q$s2yuiZCK-4S~b)v' );
define( 'LOGGED_IN_SALT',   'pmL+y0Ml3`5l3TRAJ{JUkYS=6{$(G@NMS_na%KXF0#*Gk]s<A)Pzb]B+a(6}&?.O' );
define( 'NONCE_SALT',       'k],5SVlQc>;8k:R{tQ }&)BvQlmYWqpwFC)P-O8R&e5b(*ZISqNT03IeD_8nCo>R' );

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
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
