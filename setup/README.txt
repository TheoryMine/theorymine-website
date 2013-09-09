
1. SQL Setup File: 

You need to have a "sql.php" file in the setup directory.

You can copy one the files "sql_prefs.php.template" to "sql_prefs.php"
and set some variables according to your particular database.

Note: the "sql.php" file should be in a safe place that is *not*
world-readable or web-readable. It needs to contain your database
passwords.

The variables the file needs to contain are: 

$db_type == 'mysql' for a mysql server, and 'psql' for a postgresql
server

$db_server = the server address hosting your database (localhost, or
127.0.0.1 for local installations)

$db_user == the username which to be used by php to access the database

$db_pass == the userpassword to access the database.

$db == the name of the database in which the kenyersel tables
set. Usually 'keynersel'.

$db_tables_prefix == a unique prefix for the argument map tables to
use.  This allows you to have several copies of the argument maps on
one server, each one has its own name prefix.

The SQL producing php scripts "mk_mysql_tables.sql.php" and
"mk_psql_tables.sql.php" create the sql commands to make the tables
for your sql database using the $db_tables_prefix variable from the
"sql.php" file.

2. PHP Magic quotes off: 

the .htaccess file contains: 

php_value magic_quotes_gpc off

this should turn off magic quotes for PHP. You probably do not want to have this on anyway. 
