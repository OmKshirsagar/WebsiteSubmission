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
define( 'DB_NAME', 'MyProject' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

if ( !defined('WP_CLI') ) {
    define( 'WP_SITEURL', $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] );
    define( 'WP_HOME',    $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] );
}



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
define( 'AUTH_KEY',         '9rRkgs0B1VCp1ERiMLuA34Lw64vDbBXUtzzSOO3FDHZnKG8AzJHUjMGA1j3PsLOg' );
define( 'SECURE_AUTH_KEY',  'cGN6E3QCEYanKHYN5144mujobAdzNVkYHAmACHGDOd1ALNH7DrZWXPdFNRq8WNhP' );
define( 'LOGGED_IN_KEY',    'bI8suCRttHl6ij074qNYGdRaBtnOZlZoS7dLXEMuTzjmCUtyOgeOBykGfIJy7eC4' );
define( 'NONCE_KEY',        'jCJmFufUe4FgzOJRfutbioAK3hDTv4GUV7ovypOH6MnItyMjJGOM6ketx8lqXHSS' );
define( 'AUTH_SALT',        'ENYcOty1zmKnmEzbF6GCVASPv8tDNVx2JdUqqa5OKKzaQDANjhOP6Tupp9CLJiC8' );
define( 'SECURE_AUTH_SALT', 'tATo4GpgDaqQxYHz39EtOR5pD3Q4NzZ7vu95FLShd96VSxiHTUVR9sEZFzlSA5t6' );
define( 'LOGGED_IN_SALT',   '4c0Nl3y8yInuTxv1oVtwRcIL5u1iG7JbLld57NYTAeLPeQXNXA7TJwgp6HhoZVRV' );
define( 'NONCE_SALT',       'tdD6oQsEVYWc41ChUGezQ9ge3DebN7wRbjN1cbgM2Xw0QNh9lbMKWnto0wWeVjGI' );

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
