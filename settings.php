<?php
#____________________________________________________________________________#
# File: settings.php                                                         #
#____________________________________________________________________________#

/**
 * Database Constants - these constants are required
 * in order for there to be a successful connection
 * to the MySQL database. Make sure the information is
 * correct.
 */
define("DB_TYPE", "MySQL");
define("DB_SERVER", "localhost");
define("DB_USER", "root");
define("DB_PASS", "root");
define("DB_NAME", "chrules_funch");

/**
 * Database Table Constants - these constants
 * hold the names of all the database tables used
 * in the script.
 */
define("TBL_USERS", "usersdb");
define("TBL_ACTIVE_USERS",  "active_users");
define("TBL_ACTIVE_GUESTS", "active_guests");
define("TBL_BANNED_USERS",  "banned_users");

/**
 * Server specific Constants
 */
$ar = split("[/ ]", $_SERVER['SERVER_SOFTWARE']);
for($i=0; $i<(count($ar)); $i++){
    switch(strtoupper($ar[$i])){
        case 'APACHE':
            $i++;
            $Apache_Version = $ar[$i];
            break;
        case 'PHP':
            $i++;
            $PHP_Version = $ar[$i];
            break;
    }
}
define("APACHE_VERSION_NUM", $Apache_Version);
define("PHP_VERSION_NUM", $PHP_Version);

/**
 * Directory and URL Constants
 */
$exploded_root = explode('/', $_SERVER['SCRIPT_NAME']);

define("ADMIN_PATH", "admin");
define("URL", $_GET['handle']);
define("DS", DIRECTORY_SEPARATOR);
define("URL_ROOT", DS . $exploded_root[1] . DS . $exploded_root[2] . DS);
define("ROOT", dirname(__file__) . DS);
define("MODULE_ROOT", ROOT . 'modules' . DS);
define("TEMPLATES_ROOT", ROOT . 'templates' . DS);
define("URL_TEMPLATES_ROOT", URL_ROOT . 'templates' . DS);
define("RESOURCES_ROOT", URL_ROOT . 'resources' . DS);
define("MEDIA_ROOT", URL_ROOT . 'resources' . DS . 'css' . DS);
define("UPLOAD_ROOT", ROOT . 'resources' . DS . 'uploads' . DS);

/**
 * Special Names and Level Constants - the admin
 * page will only be accessible to the user with
 * the admin name and also to those users at the
 * admin user level. Feel free to change the names
 * and level constants as you see fit, you may
 * also add additional level specifications.
 * Levels must be digits between 0-9.
 */
define("ADMIN_NAME", "chrules");
define("GUEST_NAME", "Guest");
define("ADMIN_LEVEL", 9);
define("USER_LEVEL",  1);
define("USER2_LEVEL",  2);
define("USER3_LEVEL",  3);
define("USER4_LEVEL",  4);
define("USER5_LEVEL",  5);
define("USER6_LEVEL",  6);
define("USER7_LEVEL",  7);
define("USER8_LEVEL",  8);
define("GUEST_LEVEL", 0);

/**
 * This boolean constant controls whether or
 * not the script keeps track of active users
 * and active guests who are visiting the site.
 */
define("TRACK_VISITORS", true);

/**
 * Timeout Constants - these constants refer to
 * the maximum amount of time (in minutes) after
 * their last page fresh that a user and guest
 * are still considered active visitors.
 */
define("USER_TIMEOUT", 10);
define("GUEST_TIMEOUT", 5);

/**
 * Cookie Constants - these are the parameters
 * to the setcookie function call, change them
 * if necessary to fit your website. If you need
 * help, visit www.php.net for more info.
 * <http://www.php.net/manual/en/function.setcookie.php>
 */
define("COOKIE_EXPIRE", 60*60*24*100);  //100 days by default
define("COOKIE_PATH", "/");  //Avaible in whole domain

/**
 * Email Constants - these specify what goes in
 * the from field in the emails that the script
 * sends to users, and whether to send a
 * welcome email to newly registered users.
 */
define("EMAIL_FROM_NAME", "Zeal - Christian Laustsen");
define("EMAIL_FROM_ADDR", "Contact@ZealDev.com");
define("EMAIL_SIGNATURE", "- Zeal");
define("EMAIL_WELCOME", false);

/**
 * This constant forces all users to have
 * lowercase usernames, capital letters are
 * converted automatically.
 */
define("ALL_LOWERCASE", false);