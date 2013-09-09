<?php
/* PhP code for working with mysql and postgresql databases in php.
   Provides a simple single interface to both databases. 
   Assumes global SQL vars for preferences...

   $db_type = either 'psql' for progresql or 'mysql' for a mysql database.
   $db_server = the server name or IP address;
   $db_user = the username;
   $db_pass = the username's password for SQL;
   $db = the database;
*/

// USES add_slashes from common.php
function sql_str_escape($s){
  global $db_link, $db_type;
  
  if($db_type == 'mysql') {
    return mysql_escape_string($s);
  } elseif($db_type == 'psql') {
    // FIXME: 
    // pg_escape_string is buggy; given "s'" it returns "s''"
    // sql_try_connect();
    // return pg_escape_string($db_link, $s);
    return addslashes($s);
  } else {
    die_at_noted_problem("<p>sql_str_escape: unkown DB type");
    return addslashes($s);
  }
}

// copy and sql escape strings in an array
function sql_escape_array($a){
  if($a == null) { return null; }
  else {
    $a2 = array();
    //print "<br>a:"; print_r($a);
    foreach(array_keys($a) as $k) 
    { $a2[$k] = sql_str_escape($a[$k]); }
    //print "<br>a2:"; print_r($a2);
    return $a2;
  }
}

// print an SQL date as a nice string
function sqltimestamp_to_str($date)
{
  global $db_type;
  if($db_type == 'mysql') {
    if(preg_match('/^(\d\d\d\d)\-(\d\d)\-(\d\d)\ (\d\d)\:(\d\d)\:(\d\d)/', $date, $m )) {
      return date("j M Y", mktime($m[4],$m[5],$m[6],$m[2],$m[3],$m[1]));
    } else {
      return $date;
    }
  } else if($db_type == 'psql') {
    if(preg_match('/^(\d\d)\/(\d\d)\/(\d\d\d\d)\ (\d\d)\:(\d\d)\:(\d\d)/', $date, $m )) {
      return date("j M Y", mktime($m[4],$m[5],$m[6],$m[2],$m[1],$m[3]));
    } else {
      return $date;
    }
  } else {
    die_at_noted_problem("<p>sqltimestamp_to_str: bad datatype type</p>");
  } 
}

// print an SQL date as a nice string
function sqldate_to_str($date)
{
  if(preg_match('/^(\d\d)\/(\d\d)\/(\d\d\d\d)$/', $date, $m )) {
		return date("j M Y", mktime(0,0,0,$m[2],$m[1],$m[3]));
	} else {
		return $date;
	}
}

// checka a result is valid and return it
function assert_valid_sql_result($query, $result) {
  global $db_type;
  global $admin_email, $site_name;

  if($result == null) {
    if($db_type == 'mysql') {
      $msg = "<p class='warning'>Mysql query failed: <br>$query<p> error: <br>" .  mysql_error();
    } else if($db_type == 'psql') {
      $msg = "<p class='warning'>Postgres query failed: <br>$query<p> error: <br> " . pg_last_error();
    } else {
      $msg = "<p class='warning'>assert_valid_sql_result: Query failed: <br> $query.<p> [Note: unkown database type: '$db_type'; should be 'mysql' or 'psql'.] </p>";
    }
    die_at_noted_problem($msg);
  } else { return $result; }
} 


// make a query to the database - connects if needed.
function sql_try_connect() {
    global $db_server, $db_port, $db_type, $db_user, $db_pass, $db, $db_link;

    //$olderrlevel = error_reporting(E_ERROR);
    if($db_type == 'mysql') {
      if(!isset($db_link) or $db_link == NULL) {
        $db_link = mysql_connect($db_server, $db_user, $db_pass);
        mysql_select_db($db);
        if($db_link == null){ return false; }
      }
      //error_reporting($olderrlevel);
      return true;
    } elseif($db_type == 'psql') {
      if(!isset($db_link) or $db_link == NULL) {
        $split = split(":", $db_server, 2);
        if(count($split) == 2) {
          $db_server_str = $split[0];
          $db_port = " port=" . $split[1];
        } else {
          $db_server_str = $db_server;
          $db_port = "";
        }
        
        $db_link = pg_connect("host=$db_server_str $db_port dbname=$db user=$db_user
          password=$db_pass");
        $stat = pg_connection_status($db_link);
        //error_reporting($olderrlevel);
        if ($stat === PGSQL_CONNECTION_OK) { return true; }
        else { return false; } 
      } else {return true;} // already connected 
    } else {
      print("sql_try_connect: unkown database type: '$db_type'; should be 'mysql' or 'psql'.");
      //error_reporting($olderrlevel);
      return false;
    }
}

// make a query to the database - connects if needed.
function sql_query($query) {
    global $db_server, $db_type, $db_user, $db_pass, $db, $db_link;
    if($db_type == 'mysql') {
      if($db_link == NULL) {
        $db_link = mysql_connect($db_server, $db_user, $db_pass)
        or die_at_noted_problem("Could not connect to database. " . $db_user . "@" . $db_server);
        mysql_select_db($db) or die_at_noted_problem("Could not select database. " . $db);
      }
      $result = mysql_query($query, $db_link);
      return assert_valid_sql_result($query,$result);
    } else if($db_type == 'psql') {
      if($db_link == NULL) {
        $db_link = pg_connect("host=$db_server dbname=$db user=$db_user
          password=$db_pass");
        if($db_link == null) { return null; }
        // die_at_noted_problem("Could not connect to database $db with $db_user @ $db_server");
        pg_query($db_link,"SET DATESTYLE TO 'SQL, EUROPEAN'");
      }
      $result = pg_query($db_link,$query);
      return assert_valid_sql_result($query, $result);
    } else {
      die_at_noted_problem("sql_query: unkown database type: '$db_type'; should be 'mysql' or 'psql'.");
      return null;
    }
}

// assume that $result is not null - checked at query time
function sql_fetch_row($result) {
  global $db_type;
  if($db_type == 'mysql') {
    return mysql_fetch_array($result, MYSQL_ASSOC);
  } else if($db_type == 'psql') {
    return pg_fetch_assoc($result);
  } else {
    die_at_noted_problem("sql_fetch_row: unkown database type: '$db_type'; should be 'mysql' or 'psql'.");
    return null;
  }
}

function limit_sql_query($query, $offset = 0, $limit = null){
  global $db_type;
  settype($offset, "integer");
  settype($limit, "integer");
  // never give back more than a thousand things...
  if($limit == null or $limit == 0) {$limit = 1000;}
  if($db_type == 'mysql') {
    $query .= " LIMIT " . $offset . " ," . ($limit + 1);
    return $query;
  } else if($db_type == 'psql') {
    if($limit != null and $limit != 0){ $query .= " LIMIT " . ($limit + 1); }
    if($offset != null and $offset != 0){ $query .= " OFFSET " . $offset; }
    return $query;
  } else {
    die_at_noted_problem("limit_sql_query: unkown database type: '$db_type'; should be 'mysql' or 'psql'.");
    return null;
  }
}

function sql_row_count($result){
  global $db_type;
  if($db_type == 'mysql') {
    return mysql_num_rows($result);
  } else if($db_type == 'psql') {
    return pg_numrows($result);
  } else {
    die_at_noted_problem("sql_row_count: unkown database type: '$db_type'; should be 'mysql' or 'psql'.");
    return null;
  }
}


function sql_error($result){
  global $db_type;
  if($db_type == 'mysql') {
    return mysql_error($result);
  } else if($db_type == 'psql') {
    return pg_result_error($result);
  } else {
    die_at_noted_problem("sql_error: unkown database type: '$db_type'; should be 'mysql' or 'psql'.");
    return null;
  }
}

    

// get rows from a query and a database link, if no link, then reconnect
function get_rowsandsummary($query, $offset = 0, $limit = null){
  
  if($limit != null or $offset != 0)
  { $query = limit_sql_query($query, $offset, $limit); }
  
  //print "<p>q =  $query</p>";
  if( $result = sql_query($query) ) {
    $i = 0;
    // bit of a hackish way to see if we 'could' have got more
    // relies on limit_sql_query limitting query to 1 more than given limit
    $more = false; 
    while (($limit == NULL or $i <= $limit) and
           ($line = sql_fetch_row($result)))
    { // IMPROVE: limit for loop and look at $line is null or not to set $more.
      if( $limit == null or $i < $limit ) { $rows[$i] = $line; }
      $i++;
    }
    if($i > $limit) { $more = true; }
    $rowcount = sql_row_count($result);
  } else {
    // FIXME: do something better here - this is ignored!
    $error = sql_error($result);
    return null;
    //die ("Query failed: $query : $error");
  }
  
  $ret['rowcount'] = $rowcount; // number of rows returned
  $ret['offset'] = $offset; // started showing rows from here
  $ret['limit'] = $limit; // limited to this max number of rows    
  $ret['rows'] = $rows; // array of rows found
  $ret['more'] = $more; // true if there are more matching things, beyond the limit
  return $ret;
}


// get rows from a query and a database link, if no link, then reconnect
function get_rows($query, $offset = 0, $limit = null){
    $res = get_rowsandsummary($query, $offset, $limit);
    if ($res != NULL) {
        return $res['rows'];
    } else {
        return NULL;
    }
}

// get a single row, die on failure
function get_row($query, $offset = 0) {
  $res = get_rowsandsummary($query, $offset, 1);
  if($res['rowcount'] > 0){
    return $res['rows'][0];
  } else {
    die ("get_row: no such row: $query");
  }
}

// get a row - null on failure
function try_get_row($query, $offset = 0, $limit = 1) {
  $res = get_rowsandsummary($query, $offset,$limit);
  if($res['rowcount'] > 0){
    return $res['rows'][0];
  } else {
    return null;
  }
}


// get row with given id (assume id column of table)
function get_row_from_id($table, $id) {
  return try_get_row("SELECT * FROM $table as t WHERE t.id='$id'");
}


// get a row - null on failure
/* We depend on the row with the unique seq of a table to be called "id" */
function sql_insert1($table,$q) {
  global $db_type;

  if($db_type == "psql"){
    if($q == null){
      $q = "DEFAULT VALUES";
    }
    $res = sql_query("INSERT INTO $table $q");
    $row = get_row("SELECT currval('" . $table . "_id_seq')");
    return $row['currval'];
  } elseif($db_type == "mysql"){
    if($q == null){ $q = "() VALUES ()"; }
    sql_query("INSERT INTO $table $q");
    return mysql_insert_id();
  } else {
    die_at_noted_problem("sql_insert1: bad db_type ");
    return null;
  }
}


function sql_insert_many($table,$q) {
  global $db_type;

  if($db_type == "psql"){
    if($q == null){
      $q = "DEFAULT VALUES";
    }
    return sql_query("INSERT INTO $table $q");
  } elseif($db_type == "mysql"){
    if($q == null){ $q = "() VALUES ()"; }
    return sql_query("INSERT INTO $table $q");
  } else {
    die_at_noted_problem("sql_insert_many: bad db_type ");
    return null;
  }
}


/* from_tabs is a string of the form: "table1, table2, ..." */
function sql_update($table, $from_tabs, $setarray, $where) {
  global $db_type;
  
  if($db_type == "psql"){
    if($from_tabs == null) { $from = ""; } 
    else { $from = "FROM $from_tabs"; }
    $setstr = "";
    foreach(array_keys($setarray) as $k) {
      if($setstr != "") { $setstr .= ", "; }
      $setstr .= $k . " = '" . $setarray[$k] ."'"; 
    }
    return sql_query("UPDATE $table SET $setstr $from WHERE $where");
  } elseif($db_type == "mysql"){
    if($from_tabs == null) { $from = ""; } 
    else {$from = ", $from_tabs"; } 
    $setstr = "";
    foreach(array_keys($setarray) as $k) {
      if($setstr != "") { $setstr .= ", "; }
      $setstr .= $table . "." . $k . " = " . $setarray[$k] . ""; 
    }
    return sql_query("UPDATE $table $from SET $setstr WHERE $where");
  } else {
    die_at_noted_problem("sql_update: bad db_type ");
    return null;
  }
}


// make a query, returns rows affected; use sql_query for all other queries. 
function do_query($query) {
  global $db_type;
  $result = sql_query($query);
  if($db_type == 'mysql') {
    return mysql_affected_rows();
  } else if($db_type == 'psql') {
    return pg_affected_rows($result);
  } else {
    die_at_noted_problem("do_query: unkown database type: '$db_type'; should be 'mysql' or 'psql'.");
    return null;
  }
}


// check if database has given array of tables. 
function sql_select_tables($tablenames) {
  global $db, $db_type, $db_user;
  
  if($db_type == 'mysql') {
    $tablenames_str = "TABLE_NAME = '" . join("' or TABLE_NAME = '", $tablenames)
    . "'";
    $res = get_rowsandsummary("SELECT TABLE_NAME as tablename FROM INFORMATION_SCHEMA.TABLES where TABLE_SCHEMA = '$db' and ($tablenames_str)");
    return $res;  
  } else if($db_type == 'psql') {
    $tablenames_str = "tablename = '" . join("' or tablename = '", $tablenames)
    . "'";
    $res = get_rowsandsummary("select tablename from pg_tables where tableowner='$db_user' and ($tablenames_str)");
    return $res;
  } else {
    //print "<pre>";
    //debug_print_backtrace();
    //print "</pre>";
    print("sql_select_tables: unkown database type: '$db_type'; should be 'mysql' or 'psql'."); 
    return null;
  }
}

function sql_has_tables($tablenames) {
  $res = sql_select_tables($tablenames);
  if($res != null) { return ($res['rowcount'] == count($tablenames));  
  } else { return false; }
}

function sql_check_tables($tablenames) {
  $res = sql_select_tables($tablenames);
  if($res != null and $res['rowcount'] > 0) {
    $tables_in_db = array();
    foreach($res['rows'] as $r) {
      array_push($tables_in_db, $r['tablename']);
    }
    
    $missing = array();
    $available = array();
    foreach($tablenames as $t) {
      if(in_array($t,$tables_in_db)){
        array_push($available, $t);
      } else {
        array_push($missing, $t);
      }
    }
    return array('missing' => $missing, 'available' => $available);
  } else {
    return array('missing' => $tablenames, 'available' => array());
  }
}

// a bit application specific... reset tables in the database - removes all data!
function mk_sql_tables($tables_list) {
  global $db_type, $db_tables_prefix;
  // ugly hack: have to put in all tables here fo include can use them...
  global $db_actions, $db_points, $db_points_h, $db_relations, $db_relations_h, $db_unique_keys, $db_users, $db_paypal_payment_info, $db_paypal_subscription_info, $db_paypal_cart_info;

  $q = null;
  if($db_type == 'mysql') {
    include("setup/mk_mysql_tables.sql.php");
  } else if($db_type == 'psql') {
    include("setup/mk_psql_tables.sql.php");
  } else {
    die_at_noted_problem("mk_sql_tables: unkown database type: '$db_type'; should be 'mysql' or 'psql'.");
    return null;
  }
  
  // create each table in table list
  foreach($tables_list as $t) { 
    if(array_key_exists($t,$q)) {
      $res = sql_query($q[$t]);
      if($res == null) { 
        print("mk_sql_tables: make table query failed: '$q[t]' from creation file (setup/mk_" . $db_type . "_tables.sql.php).");
        return null; 
      }
    } else {
      print("mk_sql_tables: unkown table: '$t', not defined in sql table creation file (setup/mk_" . $db_type . "_tables.sql.php).");
      return null;
    }
  }
  return 1;
}


function drop_sql_tables($tables) {
  // same syntax for mysql and psql!
  foreach($tables as $tab){
    if(sql_query("DROP TABLE " . $tab) == null) 
    {
      print "<br>failed to droped: $tab";
    } 
  };
}

function reset_sql_tables($tablelist) {
  global $db_tables;
  $res = sql_check_tables($db_tables);
  drop_sql_tables(array_intersect($tablelist, $res['available']));
  return mk_sql_tables($tablelist);
  // FIXME: only create intersection of unaviable tables and given list - give wanring about those tables that don't exist. 
}

/* make an SQL query from a basic langauge of queries. */
function interpret_search($search) {
  //print("<p> Search: $search </p>");
  $ms = "/((\\w|_|\.)+)\\s*(=|>=)\\s*'((\\\\'|[^'])+)'/";
  // print("<p> m: $ms </p>");
  if(preg_match_all($ms, $search, $q, PREG_SET_ORDER))
  {
    //$s = array();
    if(count($q) > 0){
      $s = " ";
      $l = array();
      foreach($q as $m){
        array_push($l, $m[1] . " = '" . $m[4] . "'");
      }
      $s .= join(" AND ", $l);
    } else { 
      $s = "";
    }
    return $s;
  } else {
    return "";
  }
}


function get_where($db_table, $where, $offset = null, $limit = null) {
  if($where == null or trim($where) == "") {
    $query = "SELECT * FROM $db_table;";
  } else {
    $query = "SELECT * FROM $db_table WHERE $where";
  }
  return get_rowsandsummary($query, $offset, $limit);
}


function print_sql_rows($rows) {
  ?><br>ROWS: <br> 
  <table cellpadding="2" cellspacing="0" BORDER="1">
  <?
  if($rows != null) {
    $i = 0;
    foreach($rows as $r) {
      if($i == 0) {
        ?><tr><?
        foreach(array_keys($r) as $k) {
          ?><th><pre><? print($k); ?></pre></th><?
        }
        ?></tr><?
      }
      ?><tr><?
      foreach($r as $c) {
        ?><td><pre><? print($c); ?></pre></td><?
      }
      ?></tr><?
      $i ++;
    }
    ?></table><?
  }
}


?>
