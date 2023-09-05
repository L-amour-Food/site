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
define( 'DB_NAME', 'sabr8669_lamourfood' );

/** MySQL database username */
define( 'DB_USER', 'sabr8669_amourf' );

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
define( 'AUTH_KEY',         'bkzmq9zj199lk5xzuwyo4gkhdufi7wtekd4cs0dgnpfoltggnnwi2z2trog2twmy' );
define( 'SECURE_AUTH_KEY',  'y1m6zotoqlqpympnkqdnnlpa7xaevjx0t3dswfirprlymcetfpfqhlmqhwc88mv5' );
define( 'LOGGED_IN_KEY',    'sm8z6i4ejstv3ebym2tidrnibu5kivr3lqpza8nujfmam913kpqgtvovcf9nwrq8' );
define( 'NONCE_KEY',        'dxbj6bcdczgvqbttxai1d73knp3v4n13kiq5mump2lxqvcme9ixgpx1dwgmetsil' );
define( 'AUTH_SALT',        'ff6jtv55wyatllawu0u3s6jpdryistwrbky4xh4ry3rhz4qwbyxgnyn9ecrdbjmk' );
define( 'SECURE_AUTH_SALT', 'hkqv9pqbivf7weuxqdi9zhhxmu9ur84fx3xbjmwomkaa75excmtllkbjthygrs9d' );
define( 'LOGGED_IN_SALT',   'fsrcfigmw7k7rgujroogrs1nuoufkjgk4k43xp4jmyzbgi0ankgwcbpkbao2tke2' );
define( 'NONCE_SALT',       '3nfcbp80vufhk2aaqgklvdoc0wor10bi5ozocf72k9fuigsw499hdpyuzvzjjoci' );
define( 'JWT_AUTH_SECRET_KEY', 'hkqv9pqbivf7wxvxqdi9zhhxmu9ur84fx3xbjmwomkza75excmtllkbjthygrs5d' );
define( 'JWT_AUTH_CORS_ENABLE', true );
/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wput_';

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
