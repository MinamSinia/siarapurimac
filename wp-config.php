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
define( 'DB_NAME', 'wordpress_siarapurimac' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'minam.2022==$$' );

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
define( 'AUTH_KEY',         '@~g{<aI7@lu:$AU =QiVw5x6/gk4N5x1iUqeHPCq6Z_.X,j|r$GY9]>r%|lWi,6v' );
define( 'SECURE_AUTH_KEY',  'AP/Jahh5`x@hDVCD-0k.F7C4Ka3Bc~4)Q<^6x5my:Eb]~Zg)d=KDZO~^5=??vKD_' );
define( 'LOGGED_IN_KEY',    'A?P%<_*=+$9YR+Vg-PSz@^8i))AXGxH<]7[xX0.N0Rp)S~`(e|UgLbJ3_Y_<koL{' );
define( 'NONCE_KEY',        '}x])KD1}lS8`>hjXs6nx!]hbam6R}25*Pd*&xL/ r(s7Y:=oy9F>c3CY+Y]4UF=n' );
define( 'AUTH_SALT',        'Uu}XWpJf~col?$/{f<]Pe]u>AT;9Ou.O^5:9@f k14KeO~rH2[d<N?:xwJwD+7p ' );
define( 'SECURE_AUTH_SALT', '/eO3~?7g/vo9<^afi/1oVbaP+QbFjL}N!Ef|o0zFXq>_Qf[ZW@@]*Z^>pkN>wYlE' );
define( 'LOGGED_IN_SALT',   'g88Cb;V90~j(6s}z!6R2v!-V]:UK :u0e@$zGY:O)_42Nt[7IY;T2(?)~-w+),GO' );
define( 'NONCE_SALT',       'NYl;)qBNKm$-Qf4|r`);xGjcx:W)}zM=`YBYF{6SER$6>t~7+~2oe!PffD14fgDT' );

/**#@-*/

/**
 * WordPress database table prefix.
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

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';

define('FS_METHOD','direct');
