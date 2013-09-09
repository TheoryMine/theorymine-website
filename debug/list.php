<?
// PayPal Notification Script - paypal website sends query here
// This was adaptd from PayPal's auto genereated php script. 

include "../utils/common.php";
include "../utils/prefs.php"; // sets the db_table names 
// sql libs
include "../utils/sql_lib.php";
include "../utils/sql_actions.php";
include "../utils/sql_user.php";
include "../utils/sql_points.php";


// init/start session
session_name($site_name . "_session");
session_start();
session_cache_expire(180);

/* */
// some basic security to avoid random people stmbling onto the in
// development website.
$_SESSION['secure'] = set_default($_REQUEST['secure'], $_SESSION['secure']);  
if(!$_SESSION['secure']) {
  die("access here is restricted.");
}


$prefs_filename = '../prefs/prefs.php'; // location of global prefs
$sqlprefs_filename = 'sql_prefs.php'; // sql prefs filename

//DB connect creds and email 
$notify_email =  "lucas.dixon@gmail.com";  //email address to which debug emails are sent to

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

if($_REQUEST['act'] == 'add') {
  print("added point to log");
  log_as_point("test", "Request:\n" . print_r($_REQUEST, true), 'test');
}

if($_REQUEST['act'] == 'clear') {
  print("cleared log");
  sql_query("DELETE FROM $db_points_h WHERE point_type LIKE 'log.%'");
}

if($_REQUEST['act'] == 'del') {
  print("deleted point");
  sql_query("DELETE FROM $db_points_h WHERE point_type LIKE 'log.%' AND id ='" .  sql_str_escape($_REQUEST['id']) . "'");
}
?>

<ul>
  <li><a href="?act=clear">clear log</a></li>
  <li><a href="?act=add">add</a></li>
  <li><a href="?act=list">list</a></li>

</ul>

<h2>Logged points: <? print $db_points_h; ?></h2>
<?
$res = get_rowsandsummary("SELECT * FROM $db_points_h WHERE point_type LIKE 'log.%' ORDER BY time_stamp DESC");
?>
ROW COUNT: <? print($res['rowcount']); ?><br>
<?
  if($res['rowcount'] > 0){
    foreach($res['rows'] as $r) {
      ?>
      <table cellpadding="2" cellspacing="0" BORDER="1" width="90%">
      <tr><td width="4em"><? print($r['id']); ?></td>
            <td width="6em"><? print($r['point_type']); ?></td>
            <td><? print($r['title']); ?></td></tr>
        <tr>
        <td colspan=3><code><? print(str_replace("\r","\r<br>",
           str_replace('&','<br>&',
             str_replace("\n","\n<br>",$r['body'])))); ?></code></td></tr>
        <tr>
        <td colspan=3><a href="?act=del&id=<? print($r['id']); ?>">del</a></tr>
        </table><?
    }
  }
// print_sql_rows($rows);
?>



<h2>Paypal payment info: <? print $db_paypal_payment_info; ?></h2>
<?
$res = get_rowsandsummary("SELECT * FROM $db_paypal_payment_info ORDER BY time_stamp DESC");
$rows = $res['rows'];
print_sql_rows($rows);
?>

               

