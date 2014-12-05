<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, and ABSPATH. You can find more information by visiting
 * {@link http://codex.wordpress.org/Editing_wp-config.php Editing wp-config.php}
 * Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'wordpress');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

/** MySQL hostname */
define('DB_HOST', '127.0.0.1');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

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
define('AUTH_KEY',         'Rh*FC)jPmWco&4T1y]8TBo}J>-L`9re@P&(D5 pmYOtZ#JPQ;~+Qx#H<ug<n|@a)');
define('SECURE_AUTH_KEY',  'KgQ-D4&@L+5Fx5cz.&<&w;c3!6+$o%Oki)WN@YCAXQ.`qYVQUoSh|FY|kyi[)]gx');
define('LOGGED_IN_KEY',    'Ipu+r$J-~9&pQ_uAd(G[&+~/e<K{G%b&s}1VFNgypWw+-bQ_u-{qH_gOTM=Be,AR');
define('NONCE_KEY',        'Y<IzZ,#+b#}kG#t+wYu5e}h]-*pe;3`Yyb8xaP[|c(x)z226|1$7`e/Z`Xq&S+{I');
define('AUTH_SALT',        ' /PEGz;/_Z|4*V_Fa4(O7W8xj[`]A!D)YXq)kpnnmO+:<<bVB2NrOI?c*-o1?fDY');
define('SECURE_AUTH_SALT', 'Hd6~ol2Zk+z7?F|scjk,k=p9o6~R!EPE{L-nh~YL:[=-XpDt>quAks+5LI@-[k63');
define('LOGGED_IN_SALT',   'G2xsY2zu/KQ-uVCAk1#*Yxm;-cwy _KzH[0)Pi0o+K5UIh<8Q9}QyJahJS%P*UVi');
define('NONCE_SALT',       'pF5.;D%za(yBSts72M<yl{Zx[vb6^%DpW1W65?cE; v|L=?rt(lrxr8HhjaG=ph.');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
