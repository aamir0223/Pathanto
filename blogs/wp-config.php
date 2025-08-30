<?php
define( 'WP_CACHE', true );

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
define( 'DB_NAME', 'u285245875_JODJF' );

/** Database username */
define( 'DB_USER', 'u285245875_P5xIh' );

/** Database password */
define( 'DB_PASSWORD', 'fqFHKrAsfb' );

/** Database hostname */
define( 'DB_HOST', '127.0.0.1' );

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
define( 'AUTH_KEY',          'kY<.lyhcW,skc 2m9 ,n%e~URX55vXRb:&tYKw3P{y-l}zM4}JnYSsD;Ikr}e3mF' );
define( 'SECURE_AUTH_KEY',   'u~LDItIQ9il2,pw>5Q-$d(+4INdj`,Iea{<ih.d;W>]rnVN0}1+/tPzf_o5.ye,z' );
define( 'LOGGED_IN_KEY',     '>[CdU3thz~32M>M?b.E8_1<pmb&?Iw[}a:)wK{cT61#3!_yZ7ij+&^;+(dLg.wEZ' );
define( 'NONCE_KEY',         'v^fB0SW5|[!Xuq,MaeOhV|S77=K?[8^0STX:j=8 XGJf* <Fs<$|X&D4M6,t>;7B' );
define( 'AUTH_SALT',         '.KL&n#Lc-c9Pc6D%/G=hM_kTSVD_WW{.7FATq7wOIikIO+1q9KAcx3+#zu,RA_*K' );
define( 'SECURE_AUTH_SALT',  'cM)@S~yuy@e;kUov|RxZ/X(.|g^E}&x50,ng{;2.*.1[CsH3C,;Zc>,Si@QNCARy' );
define( 'LOGGED_IN_SALT',    'J}84axVDMZSIj{d1SFPb_EX,c3EvT>,U?m`p*3ilMLPX7F<O)P_i^XQ4VzKet;`7' );
define( 'NONCE_SALT',        '{Ud~}rQ*E7+9Mr1g;E^s^5}b{b`u7LY{rj]wh)Q-_g~6mwA<$?a{COhj@IIB+[{]' );
define( 'WP_CACHE_KEY_SALT', 'ge!Z^WNQvTaYqn>.q/4!:>+j&)|2N~;9Vu)w^xD!;Y9g|UycKIr!.P9EpnoYPP}9' );


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



define( 'FS_METHOD', 'direct' );
define( 'WP_AUTO_UPDATE_CORE', 'minor' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
