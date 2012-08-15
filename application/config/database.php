<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------
| DATABASE CONNECTIVITY SETTINGS
| -------------------------------------------------------------------
| This file will contain the settings needed to access your database.
|
| For complete instructions please consult the 'Database Connection'
| page of the User Guide.
|
| -------------------------------------------------------------------
| EXPLANATION OF VARIABLES
| -------------------------------------------------------------------
|
|	['hostname'] The hostname of your database server.
|	['username'] The username used to connect to the database
|	['password'] The password used to connect to the database
|	['database'] The name of the database you want to connect to
|	['dbdriver'] The database type. ie: mysql.  Currently supported:
				 mysql, mysqli, postgre, odbc, mssql, sqlite, oci8
|	['dbprefix'] You can add an optional prefix, which will be added
|				 to the table name when using the  Active Record class
|	['pconnect'] TRUE/FALSE - Whether to use a persistent connection
|	['db_debug'] TRUE/FALSE - Whether database errors should be displayed.
|	['cache_on'] TRUE/FALSE - Enables/disables query caching
|	['cachedir'] The path to the folder where cache files should be stored
|	['char_set'] The character set used in communicating with the database
|	['dbcollat'] The character collation used in communicating with the database
|				 NOTE: For MySQL and MySQLi databases, this setting is only used
| 				 as a backup if your server is running PHP < 5.2.3 or MySQL < 5.0.7
|				 (and in table creation queries made with DB Forge).
| 				 There is an incompatibility in PHP with mysql_real_escape_string() which
| 				 can make your site vulnerable to SQL injection if you are using a
| 				 multi-byte character set and are running versions lower than these.
| 				 Sites using Latin-1 or UTF-8 database character set and collation are unaffected.
|	['swap_pre'] A default table prefix that should be swapped with the dbprefix
|	['autoinit'] Whether or not to automatically initialize the database.
|	['stricton'] TRUE/FALSE - forces 'Strict Mode' connections
|							- good for ensuring strict SQL while developing
|
| The $active_group variable lets you choose which connection group to
| make active.  By default there is only one group (the 'default' group).
|
| The $active_record variables lets you determine whether or not to load
| the active record class
*/

$active_group = 'DiabloIII';
$active_record = TRUE;

// 暗盟
$db['DiabloIII']['hostname'] = 'localhost';
$db['DiabloIII']['username'] = 'sc2clan';
$db['DiabloIII']['password'] = 'ilovesc2!';
$db['DiabloIII']['database'] = 'sc2clan_diabloiii';
$db['DiabloIII']['dbdriver'] = 'mysql';
$db['DiabloIII']['dbprefix'] = 'd3bbs_';
$db['DiabloIII']['pconnect'] = FALSE;
$db['DiabloIII']['db_debug'] = TRUE;
$db['DiabloIII']['cache_on'] = FALSE;
$db['DiabloIII']['cachedir'] = APPPATH . 'cache/DiabloIII_db_cache';
$db['DiabloIII']['char_set'] = 'utf8';
$db['DiabloIII']['dbcollat'] = 'utf8_general_ci';
$db['DiabloIII']['swap_pre'] = '';
$db['DiabloIII']['autoinit'] = TRUE;
$db['DiabloIII']['stricton'] = FALSE;

// 星盟
$db['StarCraftII']['hostname'] = 'localhost';
$db['StarCraftII']['username'] = 'sc2clan';
$db['StarCraftII']['password'] = 'ilovesc2!';
$db['StarCraftII']['database'] = 'sc2clan_main';
$db['StarCraftII']['dbdriver'] = 'mysql';
$db['StarCraftII']['dbprefix'] = '';
$db['StarCraftII']['pconnect'] = FALSE;
$db['StarCraftII']['db_debug'] = TRUE;
$db['StarCraftII']['cache_on'] = FALSE;
$db['StarCraftII']['cachedir'] = APPPATH . 'cache/StarCraftII_db_cache';
$db['StarCraftII']['char_set'] = 'utf8';
$db['StarCraftII']['dbcollat'] = 'utf8_general_ci';
$db['StarCraftII']['swap_pre'] = '';
$db['StarCraftII']['autoinit'] = TRUE;
$db['StarCraftII']['stricton'] = FALSE;

// 業務
$db['Business']['hostname'] = 'localhost';
$db['Business']['username'] = 'sc2clan';
$db['Business']['password'] = 'ilovesc2!';
$db['Business']['database'] = 'sc2clan_business';
$db['Business']['dbdriver'] = 'mysql';
$db['Business']['dbprefix'] = '';
$db['Business']['pconnect'] = FALSE;
$db['Business']['db_debug'] = TRUE;
$db['Business']['cache_on'] = FALSE;
$db['Business']['cachedir'] = APPPATH . 'cache/Business_db_cache';
$db['Business']['char_set'] = 'utf8';
$db['Business']['dbcollat'] = 'utf8_general_ci';
$db['Business']['swap_pre'] = '';
$db['Business']['autoinit'] = TRUE;
$db['Business']['stricton'] = FALSE;

/* End of file database.php */
/* Location: ./application/config/database.php */