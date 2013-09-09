<?

// creates a new action and returns its id
function mk_action($user_id, $pid, $atype, $abody, $ahid) {
  global $db_actions;
  $ip = $_SERVER['REMOTE_ADDR'];
  return sql_insert1($db_actions, "(obj_id, action_type, action_body, user_id, ipaddr, time_stamp, history_id)"
  . "VALUES('$pid', '$atype', '$abody', '$user_id', " 
  . "'$ip', NOW(), '$ahid')");
}


function get_user_recent_actions_with_point($user_id, $offset = 0, $limit = 10) {
    global $db_actions, $db_points_h;
    $query = "SELECT a.*, p.title, p.body, p.id as pid, p.point_type FROM $db_actions as a, $db_points_h as p WHERE a.user_id='$user_id' AND p.id=a.obj_id ORDER BY a.time_stamp DESC, a.id DESC";
    return get_rowsandsummary($query, $offset, $limit);
}

function get_user_recent_actions($user_id, $offset = 0, $limit = 10) {
    global $db_actions;
    $query = "SELECT a.* FROM $db_actions as a WHERE a.user_id='$user_id' ORDER BY a.time_stamp DESC, a.id DESC";
    return get_rowsandsummary($query, $offset, $limit);
}

function get_all_recent_actions($offset = 0, $limit = 10) {
    global $db_actions;
    $query = "SELECT a.* FROM $db_actions as a ORDER BY a.time_stamp DESC, a.id DESC";
    return get_rowsandsummary($query, $offset, $limit);
}
 

function get_all_recent_actions_with_point($offset = 0, $limit = 10) {
    global $db_actions, $db_points_h;
    $query = "SELECT a.*, p.title, p.body, p.id as pid, p.point_type FROM $db_actions as a, $db_points_h as p WHERE p.id=a.obj_id ORDER BY a.time_stamp DESC, a.id DESC";
    return get_rowsandsummary($query, $offset, $limit);
}


function get_last_action_for_point_by_user($point, $user_id) {
    global $db_actions, $db_points;
    $query = "SELECT a.* FROM $db_actions as a, $db_points as p WHERE p.action_id = a.id AND a.user_id='$user_id'";
    return get_row($query);
}

function get_last_action_for_point($point) {
    global $db_actions, $db_points;
    $query = "SELECT a.* FROM $db_actions as a, $db_points as p WHERE p.action_id = a.id";
    return get_row($query);
}

function get_last_action_for_rel($rel) {
    global $db_actions, $db_relations;
    $query = "SELECT a.* FROM $db_actions as a, $db_relations as r WHERE r.action_id = a.id";
    return get_row($query);
}


function get_all_user_action_points($user_id, $act_id, $offset = 0, $limit = 10) {
    global $db_actions, $db_points;
    $query = "SELECT p.* FROM $db_actions as a, $db_points as p WHERE a.id = '$act_id' a.user_id='$user_id' AND p.action_id=a.id";
    return get_rowsandsummary($query, $offset, $limit);
}

?>
