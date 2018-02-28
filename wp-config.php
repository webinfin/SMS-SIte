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
define('DB_NAME', 'suneel_mint');

/** MySQL database username */
define('DB_USER', 'suneeldb');

/** MySQL database password */
define('DB_PASSWORD', '@i#ec9oh');

/** MySQL hostname */
define('DB_HOST', 'localhost');

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
define('AUTH_KEY',         'T6.:pQbbkvDbSeb=ff(|761>S8)*S>wU`WN.Ajesb/-0Ol@(Nt#BSH:|Cty[>M3l');
define('SECURE_AUTH_KEY',  '=q]%j0#d-k^<sd)icjqwSH+Pz-Q)?k8TjqXS|/+4/VV?<`AdBq&@nqB;3>P6FX{r');
define('LOGGED_IN_KEY',    'LWNF ,g_]:,|()XB=nujo)g&IxWp:eC4L{E@b-HS|-gyTq<8^N{)Xx4e.^.1j$`i');
define('NONCE_KEY',        '58N?5JbBs:::?6,o8%>_#de)E9_`O8t%o^Yv^?m/3I>$|?QbL:EH[d&S=nAz?jTK');
define('AUTH_SALT',        'jP@Q 73LHA{jjc(^pL$&J#y=/5:n|pO}!Z?]K&B%R(UWJ^?g)2sR%h^xHipzD:zZ');
define('SECURE_AUTH_SALT', 'q_uK_0*K/,in|unntQ[s>b}jCt{fOY<dq7K=bh%`;:yC!IC)$lWk%E^FI#/8nM9$');
define('LOGGED_IN_SALT',   'A[uIP{yd.2~uBp%QZO@AfA{nf`6a*%%^$>N3_]NW)_$IN4fCN@n=RLV;?0;aG[Q=');
define('NONCE_SALT',       'yi*V-MufP*91#&(;bhP,b~}=3hmG=mU}hO$.B(%~k?Uhx>PQ.560H:@AKr2rK<M@');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'sml_wp_';

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
define('FS_METHOD', 'direct');