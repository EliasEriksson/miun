<?php
/**
 * Baskonfiguration för WordPress.
 *
 * Denna fil används av wp-config.php-genereringsskript under installationen.
 * Du behöver inte använda webbplatsens installationsrutin, utan kan kopiera
 * denna fil direkt till "wp-config.php" och fylla i alla värden.
 *
 * Denna fil innehåller följande konfigurationer:
 *
 * * Inställningar för MySQL
 * * Säkerhetsnycklar
 * * Tabellprefix för databas
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL-inställningar - MySQL-uppgifter får du från ditt webbhotell ** //
/** Namnet på databasen du vill använda för WordPress */
define( 'DB_NAME', 'cms2' );

/** MySQL-databasens användarnamn */
define( 'DB_USER', 'cms2' );

/** MySQL-databasens lösenord */
define( 'DB_PASSWORD', '123' );

/** MySQL-server */
define( 'DB_HOST', 'eliaseriksson.eu' );

/** Teckenkodning för tabellerna i databasen. */
define( 'DB_CHARSET', 'utf8mb4' );

/** Kollationeringstyp för databasen. Ändra inte om du är osäker. */
define('DB_COLLATE', '');

/**#@+
 * Unika autentiseringsnycklar och salter.
 *
 * Ändra dessa till unika fraser!
 * Du kan generera nycklar med {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * Du kan när som helst ändra dessa nycklar för att göra aktiva cookies obrukbara, vilket tvingar alla användare att logga in på nytt.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '[i+:fSLW1XEA9b Hqos|BLO{5?9rj7?zC1+|IJs gb(WRXgU!Tc6?V4-ljFCzQ I' );
define( 'SECURE_AUTH_KEY',  '=:;Pvvp}zi~7Lj3<CT)&<KMk?4pXja8-R5$Vt3SIYqkN@z;XitM^fga8hUZ&9/7r' );
define( 'LOGGED_IN_KEY',    'T/j:wH%K@cYF3lfAFb~>GNTqm-/4W9h9NQ?yd=![6O!(D*/D@`uREjs#$faP<}Hl' );
define( 'NONCE_KEY',        '$?1(pE<Y>(!) SD({r#8Y=^9yW|3rm7h1D1gjXrMG&W3/h 4G$ m|OO,Z% b(5+p' );
define( 'AUTH_SALT',        'y#S9^l)+8{Y+$qJ5{9Gom94Ar7BMZ0w!s|i|#?@=>$R60hsh <sb=:hxpHmv+6^h' );
define( 'SECURE_AUTH_SALT', 'A3-]f;__lN%9!Kr^=2G  + otZMV9c`xZIO5 =]RiL~,aW@hs.PmdnS668/z+AHv' );
define( 'LOGGED_IN_SALT',   '>so6fE0J*Tmth*cDbDSsC3 7_UA8W:e)ko+g1J4K0p#4`9Aqsunxx||D+0bmCr9L' );
define( 'NONCE_SALT',       ':IM>2%m@oO7UC67|K@/q=FOgjp$;+Qq[_ju`Y>&@^ST/>vGIza![iF617w3PWcT<' );

/**#@-*/

/**
 * Tabellprefix för WordPress-databasen.
 *
 * Du kan ha flera installationer i samma databas om du ger varje installation ett unikt
 * prefix. Använd endast siffror, bokstäver och understreck!
 */
$table_prefix = 'wp_';

/** 
 * För utvecklare: WordPress felsökningsläge. 
 * 
 * Ändra detta till true för att aktivera meddelanden under utveckling. 
 * Det rekommenderas att man som tilläggsskapare och temaskapare använder WP_DEBUG 
 * i sin utvecklingsmiljö. 
 *
 * För information om andra konstanter som kan användas för felsökning, 
 * se dokumentationen. 
 * 
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */ 
define('WP_DEBUG', false);

/* Det var allt, sluta redigera här och börja publicera! */

/** Absolut sökväg till WordPress-katalogen. */
if ( !defined('ABSPATH') )
	define('ABSPATH', __DIR__ . '/');

/** Anger WordPress-värden och inkluderade filer. */
require_once(ABSPATH . 'wp-settings.php');