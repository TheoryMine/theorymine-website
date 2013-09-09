<pre><?php
// testing

//include basic libs
include "../utils/common.php";
include "../utils/prefs.php"; // sets the db_table names 
// sql libs
include "../utils/sql_lib.php";
include "../utils/sql_actions.php";
include "../utils/sql_user.php";
include "../utils/sql_points.php";
// include "debug_post.php"; // sets the db_table names 

$prefs_filename = '../prefs/prefs.php'; // location of global prefs
$sqlprefs_filename = 'sql_prefs.php'; // sql prefs filename

// if prefs file exists, proceed as normal
if(! load_global_prefs($prefs_filename)) {
  die("could not load global prefs:" . $prefs_filename);
} elseif(! load_sql_prefs("../" . $sqlprefs_fullfilename)) {
  die("could not load sql prefs:" . $sqlprefs_fullfilename );
} elseif(! sql_has_tables($db_tables)) {
  die("sql does not have the right tables: " . $db_tables);
} else { 
  // working ok
}

log_as_point("test", "Request:\n" . print_r($_REQUEST, true));

?>

