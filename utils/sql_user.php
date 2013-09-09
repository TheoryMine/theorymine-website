<?

function get_user_actions($user_id, $offset = null, $limit = 10) {
  global $db_actions;
  return get_rowsandsummary("SELECT * FROM $db_actions as a WHERE a.id='$user_id' ORDER BY a.time_stamp", $offset,$limit );
}

// get point with id and which was created by the given user.
function get_user_action_of_type($user_id, $action_type) {
  global $db_actions;
  return try_get_row("SELECT a.* FROM $db_actions as a WHERE a.action_type = '$action_type' AND a.user_id = $user_id");
}


// user stuff
function try_get_email($email) {
  global $db_users;
  $query = "SELECT email FROM $db_users as u WHERE u.email='$email'";
  $user  = try_get_row($query);
  return $user;
}

function try_get_user_by_email($email) {
  global $db_users;
  $query = "SELECT * FROM $db_users as u WHERE u.email='$email'";
  return try_get_row($query);
}

function get_user_from_email_and_lock($email, $unlock) {
  global $db_users;
  $query = "SELECT * FROM $db_users as u WHERE u.email='$email' AND (u.last_act_kind = 'new_user' OR u.last_act_kind = 'locked_user' OR u.last_act_kind = 'change_pass_req') AND u.last_act_code='$unlock'";
  return try_get_row($query);
}

function try_unlock_user($email, $unlock) {
  global $db_users;
  if(empty($email) or empty($unlock) or $unlock == 'none'){ 
    // IMPROVE: error? 
    return null; 
  } else {
    $user = get_user_from_email_and_lock($email, $unlock);
    if($user != null) {
      unlock_user($user['id']);
    }
    return $user;
  }
}
 

function get_user($user_id) {
  global $db_users;
  $query = "SELECT * FROM $db_users as u WHERE u.id='$user_id'";
  $user  = get_row($query);
  return $user;
}

function try_get_user($user_id) {
  global $db_users;
  $query = "SELECT * FROM $db_users as u WHERE u.id='$user_id'";
  $user  = try_get_row($query);
  return $user;
}

function try_login($email, $password) {
  global $db_users;
  if(empty($email) or empty($password)){ return null; }
  $query = "SELECT * FROM $db_users as u WHERE u.email='$email' and u.password='" . MD5($email . $password) . "'";
  $user  = try_get_row($query);
  return $user;
}

function lock_user($user_id, $lock_kind = 'locked_user') {
  global $db_users;
  $user_key = "l:" . genRandomString(10);
  $last_act_time = date("Y-m-d H:i:s");
  $query = "UPDATE $db_users SET last_act_code = '$user_key', last_act_time = '$last_act_time', last_act_kind = '$lock_kind' WHERE id='$user_id'";
  sql_query($query);
  return $user_key;
}

function unlock_user($user_id, $lock_kind = 'unlocked_user') {
  global $db_users;
  $last_act_time = date("Y-m-d H:i:s");
  $query = "UPDATE $db_users SET last_act_code = 'none', last_act_time = '$last_act_time', last_act_kind = '$lock_kind'  WHERE id='$user_id'";
  return sql_query($query);
}


function edit_user($user_id, $email2, $firstname2, $lastname2, $userkind2, $last_act_kind2, $last_act_code2, $password2 = null) {
  global $db_users;
  
  if($password2 != null) {
    $passtr = "password = '" . md5($email2 . $password2) . "', ";
  } else {
    $passstr = "";
  }
  
  $query = "UPDATE $db_users SET " 
  . $passtr 
  . "email = '$email2', " 
  . "firstname = '$firstname2', " 
  . "lastname = '$lastname2', " 
  . "userkind = '$userkind2', " 
  . "last_act_code = '$last_act_code2', " 
  . "last_act_kind = '$last_act_kind2' "
  . "WHERE id='$user_id'";
  return sql_query($query);
}



function delete_user($user_id) {
  global $db_users;
  $query = "DELETE FROM $db_users WHERE id='$user_id'";
  return  sql_query($query);
}

function make_new_user($lastname, $firstname, $email, $password, $userkind = 'normal') {
  global $db_users;
  $user_key = "n:" . genRandomString(10);
  $passhash = MD5($email . $password);
  $last_act_time = date("Y-m-d H:i:s");

  $query = "INSERT INTO $db_users " . 
    "(lastname, firstname, email, password, last_act_code, last_act_kind, last_act_time, userkind) " .
    "VALUES('$lastname', '$firstname', '$email', '$passhash'," . 
    " '$user_key', 'new_user', '$last_act_time', '$userkind')";
  sql_query($query);
  return $user_key;
}


// get point with id and which was created by the given user.
function make_new_user_action_of_type($user_id, $action_type, $action_body) {
  global $db_actions;
  return sql_query("SELECT a.* FROM $db_actions as a WHERE a.action_type = '$action_type' AND a.user_id = $user_id");
}



function renew_user_lock($user_id) {
  global $db_users;
  // IMRPOVE: make a lock on number of times reset is allowed per day.
  // else you can get denial of service attacks for targetted users
  $user_key = "r:" . genRandomString(10);
  $last_act_time = date("Y-m-d H:i:s");
  $query = "UPDATE $db_users SET " . 
    "last_act_code = '$user_key', " . 
    "last_act_kind = 'locked_user', " . 
    "last_act_time = '$last_act_time'" . 
    "WHERE id='$user_id'";
  sql_query($query);
  return $user_key;
}


function pass_reset_req_user($user_id) {
  global $db_users;
  $user_key = "p:" . genRandomString(10);
  $last_act_time = date("Y-m-d H:i:s");
  $query = "UPDATE $db_users SET " . 
    "last_act_code = '$user_key', " . 
    "last_act_kind = 'change_pass_req', " . 
    "last_act_time = '$last_act_time'" . 
    "WHERE id='$user_id'";
  sql_query($query);
  return $user_key;
}

function reset_user_password($user_id, $password) {
  global $db_users;
  $last_act_time = date("Y-m-d H:i:s");
  $query = "UPDATE $db_users SET password = '$password', last_act_code = 'none', last_act_time = '$last_act_time', last_act_kind = 'change_pass' WHERE id='$user_id'";
  return sql_query($query);
}


function get_users_where($where, $offset = null, $limit = null) {
  global $db_users;
  return get_where($db_users, $where, $offset, $limit);
}

?>
