<?php

// set global vars for db table names
function set_sql_db_table_names() {
  global $db_tables_prefix, $db_actions, $db_points, $db_points_h, 
  $db_relations, $db_relations_h, $db_unique_keys,
  $db_users, $db_tables;
  global $db_paypal_payment_info;
  
  // core tables
  $db_actions = $db_tables_prefix . 'actions';
  $db_points = $db_tables_prefix . 'points';
  $db_points_h = $db_tables_prefix . 'points_history';
  $db_relations = $db_tables_prefix . 'relations';
  $db_relations_h = $db_tables_prefix . 'relations_history';
  $db_unique_keys = $db_tables_prefix . 'unique_keys';
  $db_users = $db_tables_prefix . 'users';

  // paypal stuff
  $db_paypal_payment_info = $db_tables_prefix . 'paypal_payment_info';

  $db_tables = array($db_actions, $db_points, $db_points_h, 
    $db_relations, $db_relations_h, $db_unique_keys, $db_users, 
    $db_paypal_payment_info);
}


function globalprefs_of_phpfile($filename) {
  // global variables to instantiate in the file
  global $admin_pass, $admin_email, $safe_sql_dir;
  ob_start();
  include($filename);
  $return_str = ob_get_contents();
  ob_end_clean();
  return $return_str;
}
 
// load a php file, allowing it to set global prefs
function load_php_global_prefs($f){
  // the global variables of the program that can be set by a prefs file. 
  global $safe_sql_dir, $admin_pass, $admin_email, $prefs;
  global $db_type, $db_server, $db_user, $db_pass, $db, $db_tables_prefix;
  
  if(file_exists($f)){ 
    ob_start();
    include($f);
    ob_end_clean();
    return true;
  }
  return false;
}

// load an ini file, using it to set globals and entries in prefs array
/* function load_ini_prefs($f){
  global $prefs;
  if(file_exists($f)){ 
    if($ini_array = parse_ini_file($f, false)){
      foreach($ini_array as $key => $value){
        $GLOBALS[$key] = $value;
      }
      return true;
    }
  }
  return false;
}*/

// load prefs and set abbreviations. 
function load_global_prefs($prefs_filename){
  global $safe_sql_dir, // set by load_prefs
  $sqlprefs_filename, // used globals
  $prefs_loaded, 
  $debug_mode,
  $sqlprefs_fullfilename; // set by this function
  
  if(load_php_global_prefs($prefs_filename))
  {
    $sqlprefs_fullfilename = $safe_sql_dir . "/" . $sqlprefs_filename;
    $prefs_loaded = true;
    return true;
  } else {return false;}
}

function load_sql_prefs($sqlprefs_fullfilename){
  if(load_php_global_prefs($sqlprefs_fullfilename)){
    set_sql_db_table_names();
    $sqlprefs_loaded = true;
    return true;
  } else {return false;}
  
  //return ;
  /*
  global $db_actions, $db_points, $db_points_h, 
  $db_relations, $db_relations_h, $db_unique_keys,
  $sqlprefs_loaded,
  $db_users, // used globals
  $db_tables; // set by this function
  if(load_ini_prefs($sqlprefs_fullfilename)){
    set_sql_db_table_names();
    $sqlprefs_loaded = true;
    return true;
  } else {return false;} */
}

?>
