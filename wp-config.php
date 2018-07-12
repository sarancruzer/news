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
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'news');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'devroot');

/** MySQL hostname */
define('DB_HOST', '127.0.0.1');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

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
define('AUTH_KEY',         'zC37L^T5]W$p3LI[P(B_At]C;9[X;;A ns9[a)_|8H7?84 SD;E?PY?yjND/@los');
define('SECURE_AUTH_KEY',  'p0fPORZ.mne~.y^4lgVA`fNyi@kRNA*w5=z.V4z5FY`Uo?(S&o0Beh[8a{@=gZ~&');
define('LOGGED_IN_KEY',    'x1M|pJkD# B:05zRoynEqs[UpE_bw`KZtHn`6E<gGGM/Nl_0+Y{_,9&5rmPwI/7>');
define('NONCE_KEY',        'U-c#]i49;x(ONO6*5>OiD7!.]/^0%bR[o1qCH9?oK1k68/5f:8xS0|j;_(#9V/B~');
define('AUTH_SALT',        'Q n3ex.ufg((nCVD12R3*Dg6#YG8HB_bUd#_wGbD-Emr?2~xCEpABIRoZ&<$k._<');
define('SECURE_AUTH_SALT', 'D@4z/V& * oLi>cw [iMMj&K2;Kg#46L8g_ZqRQ#/>3mL>]Y1s]Zg!_mEYZ%*#Fi');
define('LOGGED_IN_SALT',   'XM9~-38t}u{PA~pOqFA<yp&8N3QwT)Bm^d-rQdvA56u}h |OZ</ {XUA`yX&h,r[');
define('NONCE_SALT',       '[26Q)prdT/iM5mCi@s?|dvLeFb)nry{y42f8Kf4I(7la_Q%{)QTmgfWO>PRZK/xa');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
