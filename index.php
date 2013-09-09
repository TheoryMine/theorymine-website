<? // the page that everyone goes to - all subpages of the site are by query.

// error info, and bug compatability. 
//ini_set('session.bug_compat_42', 0);
//error_reporting();
//error_reporting(E_ALL ^ E_NOTICE);
// ini_set('session.save_path', '/tmp/');

date_default_timezone_set('UTC');

require('utils/common.php');
require('utils/prefs.php');
// require('utils/sql_view.php');
require('utils/sql_lib.php');
require('utils/sql_points.php');
require('utils/sql_actions.php');
require('utils/sql_user.php');
// specific things for viewing DB
require('utils/view_tools.php');

// -- define global variables
$site_address= "http://www.theorymine.co.uk";
$site_name = "TheoryMine"; // site name used in email etc
$prefs_filename = 'prefs/prefs.php'; // location of global prefs
$prefs_loaded = false; // are prefs loaded.
$sqlprefs_filename = 'sql_prefs.php'; // sql prefs filename
$sqlprefs_loaded = false; // are sql prefs loaded.
$admin_pass = MD5('adminpass1'); // default password for admin
// -- abbriviation global vars: defined by above global vars
//   $sqlprefs_fullfilename = $safe_sql_dir . "/" . $sqlprefs_filename;

// -- gobal DB vars:
//  $safe_sql_dir // (safe) directory where $db_* prefs are saved
//  $db_server // address of database server
//  $db_type // kind of database, 'psql' or 'mysql'
//  $db_user // use login for sql database
//  $db_pass // password 
//  $db // database name
//  $db_tables_prefix // prefix for sql database tables

// -- global link vars: 
$register_link = "?go=register";
$login_link = "?go=login";
$profile_link = "?go=profile";
$logout_link = "?go=logout";

// -- global state
//  $header_printed // true when header is printed, for internal redirecting

// -- db tables
//   $db_actions = $db_tables_prefix . 'actions';
//   $db_points = $db_tables_prefix . 'points';
//   $db_points_h = $db_tables_prefix . 'points_history';
//   $db_relations = $db_tables_prefix . 'relations';
//   $db_relations_h = $db_tables_prefix . 'relations_history';
//   $db_unique_keys = $db_tables_prefix . 'unique_keys';
//   $db_users = $db_tables_prefix . 'users';
// -- db_tables for paypal payment/competition
//   $db_paypal_payment_info = $db_tables_prefix . 'paypal_payment_info';
//   $db_paypal_subscription_info = $db_tables_prefix 
//      . 'paypal_subscription_info';
//   $db_paypal_cart_info = $db_tables_prefix . 'paypal_cart_info';  
// -- array of all table names  
//  $db_tables = array($db_actions, $db_points, $db_points_h, 
//    $db_relations, $db_relations_h, $db_unique_keys, $db_users);


// init/start session
session_name($site_name . "_session");
session_start();
session_cache_expire(180);

/* */
// some basic security to avoid random people stmbling onto the in
// development website.
//$_SESSION['secure'] = set_default($_REQUEST['secure'], $_SESSION['secure']);  
//if(!$_SESSION['secure']) {
//  die("access here is restricted.");
//}

// -- user authentication stuff (through sessions)
// user_id is null if not logged in
// $email, $firstname, $lastname, $user_key
// $_SESSION['id']  -- user id 
// $_SESSION['firstname'] -- use firstname
// $_SESSION['lastname'] -- user last names
// $_SESSION['email'] -- user email address
// $_SESSION['userkind'] -- user kind: {}
// $_SESSION['debug'] -- is debug info turned on/displayed

// set debug
$_SESSION['debug'] = set_default($_REQUEST['debug'], $_SESSION['debug']);  
$_SESSION['debug'] = false; // turn off debug mode for normal use, don't leak info!

// set a new nonce for tmp id
if(!isset($_SESSION['id']) and !isset($_COOKIE['tmp_id'])) {
  setcookie("tmp_id",genRandomString(20));
}


/*
  $language = $_REQUEST['lang']; 
  if($language == 'en') {
    require 'languages/language.en.php';  
  } else if($language == 'cn') {
    require 'languages/language.cn.php';
  } else if($language == 'ko') {
    require 'languages/language.ko.php';
  } else if($language == 'zh') {
    require 'languages/language.zh.php';
  } else {
    require 'languages/language.en.php';  
  }
*/

function get_lang()
{
// The default lang
   $user_lang = 'en';

//if the user clicked on a language

  if (isset($_GET['lang']) && !empty($_GET['lang']))

    {
       $user_lang = $_GET['lang'];
       $_SESSION['lang']  = $user_lang;    
    }
    else 
    {
       //$_SESSION['lang']  = 'en';
    }
    
 // require 'languages/language.' . $user_lang . '.php';
  
  
 // return $lang;
    
       
}

get_lang();

// -- general stuff
$page = set_default($_REQUEST['go'], null);
//print_r($_SESSION);
//print($_SESSION['id']);
//print($_SESSION['firstname']);

// if prefs file exists, proceed as normal
if(! load_global_prefs($prefs_filename)) {
  // else goto admin/setup page
  $page = "admin";
} elseif(! load_sql_prefs($sqlprefs_fullfilename)) {
  // goto admin/setup page
  $page = "admin";
  $act = "change_sqlprefs";
} elseif(! sql_has_tables($db_tables)) {
  $page = "admin";
  $act = "change_sqltables";
} else { 
  // all basic stuff looks good
  // unset($_SESSION['admin']); // maybe good idea?
  //$user = get_user($_SESSION['id']);
    //$_SESSION['firstname'] = $user['firstname'];
}

// default page for different kinds of users
$page = set_default($page, 'overview');

//if($page != 'login' and page != 'admin' and iiset($_SESSION['id'])) {
//  $page = 'timeout';
//}

/**********************************************************************/
$page_loc = "pages/" . $page . ".php";
if(file_exists($page_loc)) { include($page_loc); }
elseif($page == "phpinfo") {phpinfo();}
else { ?><h3> unkown page: <? print $page; ?> </h3> <? }

include('pages/common_parts/footer.php');

?>
