<?php

// return point of given id
// ONLY TO BE CALLED BY ADMIN, or in safe cases
function get_rel($id) {
  global $db_relations;
  return try_get_row("SELECT r.* FROM $db_relations as r WHERE r.id='$id'");
}

// return point of given id
// ONLY TO BE CALLED BY ADMIN, or in safe cases
function get_point($id) {
  global $db_points;
  return try_get_row("SELECT p.* FROM $db_points as p WHERE p.id='$id'");
}

function get_point_with_prev_id($id) {
  global $db_points;
  return try_get_row("SELECT p.* FROM $db_points as p WHERE p.prev_id='$id'");
}


function get_from_rels_and_stuff($where = null, $offset = 0, $limit = 10) {
  global $db_relations, $db_actions, $db_points, $db_users;
  if($where != null and trim($where) != ""){
    $where = " AND " . $where;
  }
  return get_rowsandsummary("SELECT r.*, a.id as a_id, a.history_id as a_history_id, a.obj_id,  a.action_type, a.action_body, a.user_id, a.ipaddr, a.time_stamp as a_time_stamp, u.id as u_id, u.firstname, u.lastname, u.email, u.last_act_time, u.last_act_kind, u.userkind, p1.title as p1_title, p2.title as p2_title, p1.point_type as p1_type, p2.point_type as p2_type FROM $db_relations as r, $db_points as p1, $db_points as p2, $db_actions as a, $db_users as u WHERE r.action_id = a.id AND a.user_id = u.id AND r.src_obj_id = p1.id AND r.dst_obj_id = p2.id $where", $offset, $limit);
}

/*  */
function get_from_points_and_actions_and_user($where = null, $pre_where, $offset = 0, $limit = 10) {
  global $db_actions, $db_points, $db_users;
  if($where != null and trim($where) != ""){
    $where = " AND " . $where;
  }
  return get_rowsandsummary("SELECT p.*, a.id as a_id, a.history_id as a_history_id, a.obj_id,  a.action_type, a.action_body, a.user_id, a.ipaddr, a.time_stamp as a_time_stamp, ca.time_stamp as creation_date,  u.id as u_id, u.firstname, u.lastname, u.email, u.last_act_time, u.last_act_kind, u.userkind FROM $db_points as p, $db_actions as a, $db_actions as ca, $db_users as u WHERE p.action_id = a.id AND a.user_id = u.id AND ca.history_id = p.history_id AND ca.action_type = 'create_point'  $pre_where $where ORDER BY p.id", $offset, $limit);
}

// 
function get_points_where($where, $offset = null, $limit = null) {
  global $db_points;
  return get_where($db_points, $where, $offset, $limit);
}

// get a poit of a given type
function get_point_of_type($pid, $type) {
  global $db_points;
  return try_get_row("SELECT p.* FROM $db_points as p WHERE p.id='$pid' AND point_type LIKE '$type%'");
}

/*  */
function get_all_related_points() {
  global $db_relations, $db_points;
  return get_rowsandsummary("SELECT p.*, r.relation_type, r.dst_obj_id, r.src_obj_id, r.id as r_id, r.history_id as r_history_id, r.prev_id as r_prev_id, r.action_id as r_action_id, r.time_stamp as r_time_stamp FROM $db_points as p, $db_relations as r WHERE r.dst_obj_id=p.pid OR r.src_obj_id=p.id");
}


// get all points of a given kind which are related to this point
function get_from_points_related_to_title($dst_title, $ptype = null, $rtype = null, $offset = 0, $limit = 10) {
  global $db_points, $db_relations, $db_actions;

  $where_type = '';
  if($ptype != null) { 
    $where_type = $where_type . " AND p.point_type LIKE '" . $ptype . "%'"; 
  }
  if($rtype != null) { 
    $where_type = $where_type . " AND r.relation_type LIKE '" . $rtype . "%'"; 
  }

  return get_rowsandsummary("SELECT p.*, r.relation_type, r.dst_obj_id, r.src_obj_id, r.id as r_id, r.history_id as r_history_id, r.prev_id as r_prev_id, r.action_id as r_action_id, r.time_stamp as r_time_stamp FROM $db_points as p, $db_points as p2, $db_relations as r WHERE p2.title='" . $dst_title . "' AND r.dst_obj_id=p2.id AND r.src_obj_id=p.id" . $where_type, $offset, $limit);
}

// get all points of a given kind which are related to this point
function get_points_related_from($dst_point, $ptype = null, $rtype = null, $offset = 0, $limit = 10) {
  global $db_points, $db_relations, $db_actions;

  $where_type = '';
  if($ptype != null) { 
    $where_type = $where_type . " AND p.point_type LIKE '" . $ptype . "%'"; 
  }
  if($rtype != null) { 
    $where_type = $where_type . " AND r.relation_type LIKE '" . $rtype . "%'"; 
  }

  return get_rowsandsummary("SELECT p.*, r.relation_type, r.dst_obj_id, r.src_obj_id, r.id as r_id, r.history_id as r_history_id, r.prev_id as r_prev_id, r.action_id as r_action_id, r.time_stamp as r_time_stamp FROM $db_points as p, $db_relations as r WHERE r.src_obj_id='" . $dst_point['id'] . "' AND r.dst_obj_id=p.id" . $where_type, $offset, $limit);
}

// get all points of a given kind which are related to this point
function get_points_related_to($dst_point, $ptype = null, $rtype = null, $offset = 0, $limit = 10) {
  global $db_points, $db_relations, $db_actions;

  $where_type = '';
  if($ptype != null) { 
    $where_type = $where_type . " AND p.point_type LIKE '" . $ptype . "%'"; 
  }
  if($rtype != null) { 
    $where_type = $where_type . " AND r.relation_type LIKE '" . $rtype . "%'"; 
  }

  return get_rowsandsummary("SELECT p.*, r.relation_type, r.dst_obj_id, r.src_obj_id, r.id as r_id, r.history_id as r_history_id, r.prev_id as r_prev_id, r.action_id as r_action_id, r.time_stamp as r_time_stamp FROM $db_points as p, $db_relations as r WHERE r.dst_obj_id='" . $dst_point['id'] . "' AND r.src_obj_id=p.id" . $where_type, $offset, $limit);
}

// get the one point of a given kind which is related to this point
// in a real language this would be a written as a higher-order function 
// composition; I hate php. 
function get_point_related_to($dst_point, $ptype = null, $rtype = null) {
  $rowsandsummary = get_points_related_to($dst_point, $ptype, $rtype,0,1);
  if($rowsandsummary['rowcount'] == 1) {
    return $rowsandsummary['rows'][0];
  } else {
    // maybe have an error instead?
    return null;
  }
}

function try_get_point_related_from($src_point, $ptype = null, $rtype = null) {
  $rowsandsummary = get_points_related_from($src_point, $ptype, $rtype,0,1);
  if($rowsandsummary['rowcount'] == 1) {
    return $rowsandsummary['rows'][0];
  } else {
    return null;
  }
}

function get_point_related_from($src_point, $ptype = null, $rtype = null) {
  $rowsandsummary = get_points_related_from($src_point, $ptype, $rtype,0,1);
  if($rowsandsummary['rowcount'] == 1) {
    return $rowsandsummary['rows'][0];
  } else {
    die_at_noted_problem("Expected single point, found: " . $rowsandsummary['rowcount']);
    // maybe have an error instead?
    return null;
  }
}


function has_points_related_from($src_point, $ptype = null, $rtype = null) {
  $rowsandsummary = get_points_related_from($src_point, $ptype, $rtype,0,1);
  return ($rowsandsummary['rowcount'] > 0);
}

function get_from_point_related_to_title($dst_title, $ptype = null, $rtype = null) {
  $rowsandsummary = get_from_points_related_to_title($dst_title, $ptype, $rtype,0,1);
  if($rowsandsummary['rowcount'] == 1) {
    return $rowsandsummary['rows'][0];
  } else if($rowsandsummary['rowcount'] > 1){
    die_at_noted_problem("Expected single point, found: " . $rowsandsummary['rowcount']);
    // maybe have an error instead?
    return null;
  } else {
    return null;
  }
}


// get point with id and which was created by the given user.
function get_user_history_relation($user_id, $rhid) {
  global $db_relations_h, $db_actions;
  return try_get_row("SELECT r.*, a.time_stamp FROM $db_relations_h as r, $db_actions as a WHERE r.id='$rhid' AND a.history_id = r.history_id AND a.action_type = 'create_rel' AND a.user_id = $user_id");
}

// get a draft point with id and which was created by the given user.
function get_user_history_point($user_id, $hpid) {
  global $db_points_h, $db_actions;
  return try_get_row("SELECT p.*, a.time_stamp FROM $db_points_h as p, $db_actions as a WHERE p.id='$hpid' AND a.history_id = p.history_id AND a.action_type = 'create_point' AND a.user_id = $user_id");
}

// general gets, of all points with various conditions
function get_all_points_in_type($type, $offset = 0, $limit = 10) {
  global $db_points;
  return get_rowsandsummary("SELECT p.* FROM $db_points as p WHERE point_type LIKE '$type%' ORDER BY p.time_stamp DESC, p.id DESC", $offset, $limit);
}

// general gets, of all points with various conditions
function get_all_points_with_type($type, $offset = 0, $limit = 10) {
  global $db_points;
  return get_rowsandsummary("SELECT p.* FROM $db_points as p WHERE point_type = '$type' ORDER BY p.time_stamp DESC, p.id DESC", $offset, $limit);
}

function get_all_user_points_in_type($user_id, $type, $offset = 0, $limit = 10) {
  global $db_points, $db_actions;
  return get_rowsandsummary("SELECT p.*, a.time_stamp FROM $db_points as p, $db_actions as a WHERE a.user_id = $user_id AND a.history_id = p.history_id AND a.action_type = 'create_point' AND point_type LIKE '$type%' ORDER BY p.time_stamp DESC, p.id DESC", $offset, $limit);
}

// general (but restricted and safe) gets for specific point ids
function get_point_in_type($pid,$type) {
  global $db_points;
  return try_get_row("SELECT p.* FROM $db_points as p WHERE p.id='$pid' AND point_type LIKE '$type%'");
}

function get_user_point_in_type($user_id, $type, $pid) {
  global $db_points, $db_actions;
  return try_get_row("SELECT p.*, a.time_stamp FROM $db_points as p, $db_actions as a WHERE p.id='$pid' AND a.history_id = p.history_id AND a.action_type = 'create_point' AND a.user_id = '$user_id' AND point_type LIKE '$type%'");
}

function get_user_point($user_id, $pid) {
  global $db_points, $db_actions;
  return try_get_row("SELECT p.*, a.time_stamp FROM $db_points as p, $db_actions as a WHERE p.id='$pid' AND a.history_id = p.history_id AND a.action_type = 'create_point' AND a.user_id = '$user_id'");
}

// general gets, of all points with various conditions
function count_all_user_points_in_type($type) {
  global $db_points, $db_actions;
  $row = try_get_row("SELECT COUNT(1) as count FROM $db_points as p, $db_actions as a WHERE a.history_id = p.history_id AND a.action_type = 'create_point' AND p.point_type LIKE '$type%'", 0, null);
  //print_r($row);
  return $row['count'];
}

// general gets, of all points with various conditions
function count_user_points_in_type($type) {
  global $db_points, $db_actions;
  $row = try_get_row("SELECT COUNT(1) as count FROM $db_points as p, $db_actions as a WHERE a.history_id = p.history_id AND a.action_type = 'create_point' AND a.user_id = '" . $_SESSION['id'] . "' AND p.point_type LIKE '$type%'", 0, null);
  //print_r($row);
  return $row['count'];
}



// get point with id and which was created by the given user.
function get_relation($rid) {
  global $db_relations, $db_actions;
  return try_get_row("SELECT r.* FROM $db_relations as r WHERE r.id='$rid'");
}

// get point with id and which was created by the given user.
function get_user_relation($user_id, $rid) {
  global $db_relations, $db_actions;
  return try_get_row("SELECT r.*, a.time_stamp FROM $db_relations as r, $db_actions as a WHERE r.id='$rid' AND a.history_id = r.history_id AND a.action_type = 'create_rel' AND a.user_id = $user_id");
}




// get all points of a given kind which are related to this point
function get_user_points_related_to($user_id, $other_point, $ptype = null, $rtype = null, $offset = 0, $limit = 10) {
  global $db_points, $db_relations, $db_actions;

  $where_type = '';
  if($ptype != null) { 
    $where_type = $where_type . " AND p.point_type LIKE '" . $ptype . "%'"; 
  }
  if($rtype != null) { 
    $where_type = $where_type . " AND r.relation_type LIKE '" . $rtype . "%'"; 
  }

  return get_rowsandsummary("SELECT p.*, r.relation_type, r.dst_obj_id, r.src_obj_id, r.id as r_id, r.history_id as r_history_id, r.prev_id as r_prev_id, r.action_id as r_action_id, r.time_stamp as r_time_stamp FROM $db_points as p, $db_relations as r, $db_actions as a WHERE r.dst_obj_id='" . $other_point['id'] . "' AND r.src_obj_id=p.id AND a.history_id = p.history_id AND a.user_id = $user_id AND a.action_type = 'create_point'" . $where_type, $offset, $limit);
}


// get all points of a given kind which are related to this point
function get_points_related_to_user_point($user_id, $other_point_type, $point) {
  global $db_points, $db_relations, $db_actions;

  return get_rowsandsummary("SELECT p.*, r.relation_type, r.dst_obj_id, r.src_obj_id, r.id as r_id, r.history_id as r_history_id, r.prev_id as r_prev_id, r.action_id as r_action_id, r.time_stamp as r_time_stamp FROM $db_points as p, $db_points as p2, $db_relations as r, $db_actions as a WHERE a.user_id = $user_id AND a.history_id = p2.history_id AND a.action_type = 'create_point' AND p2.id = " . $point['id'] . " AND r.dst_obj_id='" . $point['id'] . "' AND r.src_obj_id=p.id AND p.point_type LIKE '$other_point_type%'");
}



function get_user_of_point($point) {
  global $db_users, $db_actions;
  return try_get_row("SELECT u.* FROM $db_users as u, $db_actions as a WHERE a.history_id = " . $point['history_id'] . " AND a.action_type = 'create_point' AND a.user_id = u.id");
}











// returns new unique key for a path in history
function start_new_history() {
  global $db_unique_keys;
  return sql_insert1($db_unique_keys, null); // null for default values
}

// add a point to the given table, return the points id
function db_new_point($db_table, $point) {
  if(trim($point['prev_id']) == "" or $point['prev_id'] == null) {
    $point['prev_id'] = 0;  // implies no prev id!
  } 
  return sql_insert1($db_table, 
//    "(history_id, action_id, point_type, title, body)"
    "(history_id, prev_id, action_id, point_type, title, body, time_stamp)"
    . "VALUES (" 
    . "'" . $point['history_id'] . "'," 
    . "'" . $point['prev_id'] . "',"
    . "'" . $point['action_id'] . "'," 
    . "'" . $point['point_type'] . "'," 
    . "'" . $point['title'] . "'," 
    . "'" . $point['body'] . "'," 
    . "NOW()"
    . ")");
}

/* function copy_point_h_relations($old_h_pid, $new_h_pid) {
  sql_insert_many($db_points_h, "(title, body, history_id, action_id, point_type, time_stamp) SELECT title, body, history_id, action_id, point_type, time_stamp from $db_points WHERE id = '$pid'");
}
*/

// update fields of point (where id = point['id']), updates time_stamp to now
function db_update_point($db_table, $point) {
  // update point in current points table
  return sql_query("UPDATE $db_table set "
    . "history_id = '" . $point['history_id'] . "',"
    . "action_id = '" . $point['action_id'] . "',"
    . "prev_id = '" . $point['prev_id'] . "',"
    . "point_type = '" . $point['point_type'] . "',"
    . "title = '" . $point['title'] . "',"
    . "body = '" . $point['body'] . "',"
    . "time_stamp = '" . $point['time_stamp'] . "'"
    . " WHERE id = " . $point['id']);
}


// make a new relation
function db_new_rel($db_table, $rel) {
  return sql_insert1($db_table, 
//    "(history_id, action_id, point_type, title, body)"
    "(action_id, prev_id, history_id, src_obj_id, dst_obj_id, relation_type, time_stamp)"
    . "VALUES (" 
    . "'" . $rel['action_id'] . "'," 
    . "'" . $rel['prev_id'] . "'," 
    . "'" . $rel['history_id'] . "'," 
    . "'" . $rel['src_obj_id'] . "'," 
    . "'" . $rel['dst_obj_id'] . "'," 
    . "'" . $rel['relation_type'] . "'," 
    . "NOW()"
    . ")");
}

// update fields of relation (where id = point['id']), updates time_stamp to now
function db_update_rel($db_table, $rel) {
  // update point in current points table
  return sql_query("UPDATE $db_table set "
    . "action_id = '" . $rel['action_id'] . "',"
    . "prev_id = '" . $rel['prev_id'] . "',"
    . "history_id = '" . $rel['history_id'] . "',"
    . "src_obj_id = '" . $rel['src_obj_id'] . "',"
    . "dst_obj_id = '" . $rel['dst_obj_id'] . "',"
    . "relation_type = '" . $rel['relation_type'] . "',"
    . "time_stamp = '" . $rel['time_stamp'] . "'"
    . " WHERE id = " . $rel['id']);
}




// move point to points_history table from cur points table
function copy_point_to_history($pid) {
  global $db_points_h, $db_points;
  $npid = sql_insert1($db_points_h, "(title, body, history_id, action_id, prev_id, point_type, time_stamp) SELECT title, body, history_id, action_id, prev_id, point_type, time_stamp from $db_points WHERE id = '$pid'");
  return $npid;
}

// move point from points_history table to cur table
function copy_point_from_history($phid) {
  global $db_points_h, $db_points;
  $pid = sql_insert1($db_points, "(title, body, history_id, action_id, point_type, prev_id, time_stamp) SELECT title, body, history_id, action_id, point_type, '$phid', time_stamp FROM $db_points_h WHERE id = '$phid'");
  //print_r($pid);
  return $pid;
}

// move point from points_history table to cur table
function update_point_from_history($hpid, $pid) {
  global $db_points_h, $db_points; // , $db_actions;
  
  $hpoint = get_row("SELECT * FROM $db_points_h WHERE id = '$hpid'");
  // NOTE: Don't do the line below:
  // because the invariant is that point[prev_id] = point_history[id]
  $hpoint['prev_id'] = $hpid;
  $hpoint['id'] = $pid;
  $hpoint2 = sql_escape_array($hpoint); 
  db_update_point($db_points, $hpoint2);
  
  // IMPROVE: write generic inter-table update, faster and 
  // better than extract and re-insert of data. see below
  // $npid = sql_query("UPDATE $db_points SET (title, body, history_id, action_id, point_type, prev_id, time_stamp) SELECT title, body, history_id, action_id, point_type, '$hpid', time_stamp FROM $db_points_h as p WHERE pid = '$hpid' AND id = '$pid'");
  // return $npid;
}


// given history_id, creates a new point in the points_table returns it's obj_id
function create_point($user_id, $type, $title, $body, $act_body = '') {
  global $db_points_h, $db_actions;
  $hist_id = start_new_history();
  $act_id = mk_action($user_id, 0, 'create_point', $act_body, $hist_id);
  $point_h_id = db_new_point($db_points_h, array ('history_id' => $hist_id, 'title' => $title, 'body' => $body, 'point_type' => $type, 'action_id' => $act_id));
  sql_query("UPDATE $db_actions SET obj_id='$point_h_id' WHERE id='$act_id'");
  $obj_id = copy_point_from_history($point_h_id);
  // update point with the latest action_id (the create action)
  return $obj_id;
}





// update point in points table, return the created action id
// point 1 and point2 should have new action ids set in them
function update_points($points, $act_id) {
  global $db_points, $db_points_h, $db_actions, $db_relations_h, $db_relations; 
  
  //print "1<br>";
  // new historical point to mirror current point
  $hids = array();
  $src_case_str = "CASE src_obj_id ";
  $src_where_str = "";
  $dst_case_str = "CASE dst_obj_id ";
  $dst_where_str = "";
  $upd_curr_where_str = "";

  foreach($points as $p) {
    $hid = db_new_point($db_points_h, $p);
    $hids[$p['id']] = $hid;
    update_point_from_history($hids[$p['id']], $p['id']);
    $src_case_str .= "WHEN '" . $p['prev_id'] . "' THEN '" . $hids[$p['id']] . "' ";
    $dst_case_str .= "WHEN '" . $p['prev_id'] . "' THEN '" . $hids[$p['id']] . "' ";
    if($src_where_str != "") { $src_where_str .= " OR "; } 
    $src_where_str .= "src_obj_id = '" . $p['prev_id'] . "'";
    if($dst_where_str != "") { $dst_where_str .= " OR "; } 
    $dst_where_str .= "dst_obj_id = '" . $p['prev_id'] . "'";

    if($upd_curr_where_str != "") { $upd_curr_where_str .= " OR "; } 
    $upd_curr_where_str .= "$db_relations.src_obj_id = '" . $p['prev_id'] . "' OR $db_relations.dst_obj_id = '" . $p['prev_id'] . "'";
  }
  $src_case_str .= "ELSE src_obj_id END";
  $dst_case_str .= "ELSE dst_obj_id END";

  // copy relations incident to p1 or p2.
  sql_insert_many($db_relations_h, "(action_id, prev_id, history_id, src_obj_id, dst_obj_id, relation_type, time_stamp) SELECT '$act_id', id, history_id, ($src_case_str), ($dst_case_str), relation_type, time_stamp FROM $db_relations_h WHERE ($src_where_str) AND ($dst_where_str)");

  // update live relations to point to new history points
  sql_update($db_relations, "$db_relations_h as h", array("prev_id" => "h.id"), "($upd_curr_where_str) AND $db_relations.prev_id = h.prev_id");
  
  // return new point history id (pid doesn't change)
  return $hids;
}

/* change from rel to rel2, backup all points involved. 
$rel2 = array("relation_type" => ..., 'src_obj_id' => ..., 'dst_obj_id' => ...)
*/
function edit_rel($user_id, $rel, $rel2, $act_body = '') {
  global $db_relations_h, $db_relations, $db_actions; 
  
  $rid = $rel['id'];
  $hid = $rel['history_id'];

  $updated_pids = array();
  $updated_pids[$rel['src_obj_id']] = 1;
  $updated_pids[$rel['dst_obj_id']] = 1;
  $updated_pids[$rel2['src_obj_id']] = 1;
  $updated_pids[$rel2['dst_obj_id']] = 1;
  $updated_pids = array_keys($updated_pids);
  print_r($updated_pids);
  
  // action for creating this relation
  $act_id = mk_action($user_id, 0, 'edit_rel', $act_body, $hid);

  $points = array();
  foreach($updated_pids as $pid) {
    $point = sql_escape_array(get_point($pid));
    if($point == null){
      die_at_noted_problem("create_rel: missing point: $pid");
    } else {
      $point['action_id'] = $act_id;
      $points[$pid] = $point;
    }
  }

  // update all points that are touched by old and new relation values
  $new_hpids = update_points($points, $act_id);

  // get updated history for the current relation
  $rel = get_row_from_id($db_relations,$rid);
  $rhid = $rel['prev_id'];
  $rel_h = get_row_from_id($db_relations_h,$rhid);
  
  // update action to refer to updated relation
  sql_query("UPDATE $db_actions SET obj_id='" . $rel_h['id'] . "' WHERE id='$act_id'");
  
  // update the relation and it's history entry
  sql_update($db_relations, null, 
    array("relation_type" => "'" . $rel2['relation_type'] . "'", 
          'src_obj_id' => "'" . $rel2['src_obj_id'] . "'", 
          'dst_obj_id' => "'" . $rel2['dst_obj_id'] . "'", 
          "action_id" => "'" . $act_id . "'"), 
    "id = $rid");
  sql_update($db_relations_h, null, 
    array("relation_type" => "'" . $rel2['relation_type'] . "'", 
          'src_obj_id' => "'" . $rel2['src_obj_id'] . "'", 
          'dst_obj_id' => "'" . $rel2['dst_obj_id'] . "'", 
          "action_id" => "'" . $act_id . "'"), 
    "id = $rhid");

  return $rhid;
}


/* update the type of a relation, given its id */
function update_rel_type($user_id, $rid, $new_type, $act_body) {
  global $db_relations_h, $db_relations, $db_actions; 
  $rel = get_row_from_id($db_relations,$rid);
  $rel2 = array();
  $rel2['relation_type'] = $new_type;
  $rel2['src_obj_id'] = $rel['src_obj_id'];
  $rel2['dst_obj_id'] = $rel['dst_obj_id'];
  return edit_rel($user_id, $rel, $rel2, $act_body);
}

// creates a new rel in the rel_table, action for its creation,
// updates history as appropriate.  
/* 
  rel == array('relation_type' => "", 'src_obj_id' => "", 'dst_obj_id' => "")
*/
function create_rel($user_id, $rel, $act_body) {  
  global $db_relations_h, $db_relations, $db_actions, $db_points; 

  /*debug_print_backtrace();*/
  // get the points, so we have history ids as well as current ids
  $from_point = sql_escape_array(get_point($rel['src_obj_id']));
  $to_point = sql_escape_array(get_point($rel['dst_obj_id']));
  if($from_point == null or $to_point == null) {
    die_at_noted_problem("create_rel: " . print_r($rel, true) . " \n from_point: " . print_r($from_point,true) . " \n to_point: " . print_r($from_point,true));
  }
  // history id for this relation
  $hist_id = start_new_history();
  // action for creating this relation
  $act_id = mk_action($user_id, 0, 'create_rel', $act_body, $hist_id);

  // store old state of connected points in history
  $from_point['action_id'] = $act_id;
  $to_point['action_id'] = $act_id;
  $newpids = update_points(array($from_point, $to_point), $act_id);
  $new_from_h_pid = $newhids[$from_point['id']]['id'];
  $new_to_h_pid = $newhids[$to_point['id']]['id'];

  // make new relation between new historical points
  $oldrel = array();     
  $oldrel['prev_id'] = 0;
  $oldrel['relation_type'] = $rel['relation_type'];
  $oldrel['src_obj_id'] = $new_from_h_pid;
  $oldrel['dst_obj_id'] = $new_to_h_pid;
  $oldrel['action_id'] = $act_id;
  $oldrel['history_id'] = $hist_id;
  $rel_h_id = db_new_rel($db_relations_h, $oldrel);
  
  // update action to use relation id
  sql_query("UPDATE $db_actions SET obj_id='$rel_h_id' WHERE id='$act_id'");
  
  //make new relation in "current" table
  $rel['prev_id'] = $rel_h_id;
  $rel['action_id'] = $act_id;
  $rel['history_id'] = $hist_id; 
  $rel_id = db_new_rel($db_relations, $rel);
  $rel['id'] = $rel_id;

  // update point with the latest action_id (the create action)
  return $rel_id;
}


// update point in points table, return the created action id
function edit_point_from_action($point, $type, $title, $body, $act_id) {
  global $db_points, $db_points_h, $db_actions, $db_relations_h, $db_relations; 

  $pid = $point['id'];
  $hid = $point['history_id'];

  //print "previd: " . $point['prev_id'] . ";<br>";
  // if point argument has not supplied prev_id, work it out.
  if(trim($point['prev_id']) == "" or $point['prev_id'] == null) {
    //print "0<br>";
    $tmp_act = get_last_action_for_point($point);
    $point['prev_id'] = $tmp_act['obj_id'];
  }
  // get id of old point in points_h_tab
  $prev_pid = $point['prev_id'];
  
  //print "1<br>";
  // new historical point to mirror current point
  $point_h_id = db_new_point($db_points_h, array ('history_id' => $point['history_id'], 'title' => $title, 'body' => $body, 'point_type' => $type, 'action_id' => $act_id, 'prev_id' => $point['prev_id']));
  //print "2<br>";
  
  // copy historical point to current point
  update_point_from_history($point_h_id, $pid);
  
  // copy relations to/from this point, to the new version/edited point
  // uses new action id
  sql_insert_many($db_relations_h, "(action_id, prev_id, history_id, src_obj_id, dst_obj_id, relation_type, time_stamp) SELECT '$act_id', id, history_id, (CASE src_obj_id WHEN '$prev_pid' THEN '$point_h_id' ELSE src_obj_id END), (CASE dst_obj_id WHEN '$prev_pid' THEN '$point_h_id' ELSE dst_obj_id END), relation_type, time_stamp FROM $db_relations_h WHERE (src_obj_id = '$prev_pid' OR dst_obj_id = '$prev_pid')");
  
  //sql_update("$db_relations", null, 
  //  "prev_id = (SELECT h.id FROM $db_relations_h as h WHERE prev_id = h.prev_id)", "(src_obj_id = '$prev_pid' OR dst_obj_id = '$prev_pid')");
  
  // update all current relations to have new prev_id's

  // FIXME: pile of crap: postgres and mysql have incompatable syntax here: 
  // postgres requires "prev_id" in "SET" to be unprefixed, and mysql requires 
  // it to be prefixed. fix: maybe use clever string replace for mysql to add in explit updated table name?  
  sql_update($db_relations, "$db_relations_h as h", array("prev_id" => "h.id"), "($db_relations.src_obj_id = '$prev_pid' OR $db_relations.dst_obj_id = '$prev_pid') AND $db_relations.prev_id = h.prev_id");
  
  // return new point history id (pid doesn't change)
  return $point_h_id;
}


// update point in points table, return the created action id
function edit_point($user_id, $point, $type, $title, $body, $act_body = '') {
  global $db_points, $db_points_h, $db_actions;
  $pid = $point['id'];
  $hid = $point['history_id'];
  // copy old point to history table
  // create associated action
  // update point in current points table; 0 is dummy point id
  $act_id = mk_action($user_id, 0, 'edit', $act_body, $hid);
  // editing point to use new action id
  $point_h_id = edit_point_from_action($point, $type, $title, $body, $act_id);
  // update the action to refer to the correct history id
  sql_query("UPDATE $db_actions SET obj_id='$point_h_id' WHERE id='$act_id'");
  
  return $act_id;
}




// NOTE: where AND setarray query must be sql-safe! 
// update point in points table, return the created action id
function change_some_point($user_id, $where, $setarray, $act_kind, $act_body = '') {
  global $db_points, $db_points_h, $db_actions, $db_relations, $db_relations_h;

  // make action; new unique id. 
  $act_id = mk_action($user_id, 0, $act_kind, $act_body, $hid);

  // make sure we've got safe array stuff
  $setarray['action_id'] = $act_id;
  foreach($setarray as $k => $v) {
    $safe_setarray[$k] = "'" . sql_str_escape($v) . "'";
  }
  // update some point with old type to have new type and new action. 
  sql_update($db_points, null, $safe_setarray, $where . " LIMIT 1");

  // get the point we just updated (it's the only one with the new action id)
  $point = sql_escape_array(try_get_row("SELECT * FROM $db_points WHERE action_id = '$act_id'"));
  // this will be null if no entry was updated; so return null
  if($point == null) { return null; }
  // otherwise all is ok and we continue....
  $prev_pid = $point['prev_id'];

  // create copy of new point in history table
  $point_h_id = db_new_point($db_points_h, $point);

  // update the action to refer to the correct history id
  sql_query("UPDATE $db_actions SET history_id='" . $point['history_id'] . "', obj_id='$point_h_id' WHERE id='$act_id'");
  
  // update our history to have all relations we used to have for our 
  // new history point.  
  sql_insert_many($db_relations_h, "(action_id, prev_id, history_id, src_obj_id, dst_obj_id, relation_type, time_stamp) SELECT '$act_id', id, history_id, (CASE src_obj_id WHEN '$prev_pid' THEN '$point_h_id' ELSE src_obj_id END), (CASE dst_obj_id WHEN '$prev_pid' THEN '$point_h_id' ELSE dst_obj_id END), relation_type, time_stamp FROM $db_relations_h WHERE (src_obj_id = '$prev_pid' OR dst_obj_id = '$prev_pid')");

  // update the current point's prev field correctly refer to the 
  // new history point
  $point['prev_id'] = $point_h_id;
  sql_query("UPDATE $db_points SET prev_id='" .  $point_h_id . "' WHERE id = '" . $point['id'] . "'");

  // update all current relations to refer to updated relations history. 
  sql_update($db_relations, "$db_relations_h as h", array("prev_id" => "h.id"), "($db_relations.src_obj_id = '$prev_pid' OR $db_relations.dst_obj_id = '$prev_pid') AND $db_relations.prev_id = h.prev_id");
  
  // return the updated point
  return $point;
}






// IMRPOVE: maybe better to have deletion simply as changing type of point and all incident relations to 'deleted'?
// return point of given id
// ONLY TO BE CALLED BY ADMIN, or in safe cases
function delete_point($user_id, $point, $act_body = '') {
  global $db_points, $db_relations;
  $last_act = get_last_action_for_point($point);
  $act_id = mk_action($user_id, $last_act['obj_id'], 'delete', $act_body, $point['history_id']);
  $query1 = "DELETE FROM $db_points WHERE id = '" . $point['id'] . "'";
  $query2 = "DELETE FROM $db_relations WHERE src_obj_id = '" . $point['id'] . "' OR dst_obj_id = '" . $point['id'] . "'";
  print($query1 . "<br>" . $query2);
  sql_query($query2);
  sql_query($query1);
  return $last_act['obj_id']; // historical version of point
}


function undelete_point($user_id, $point_h_id, $act_body = '') {
  global $db_points, $db_points_h;
  $act_id = mk_action($user_id, $last_act['obj_id'], 'undelete', $act_body, $point['history_id']);
  sql_query("UPDATE $db_points_h SET action_id='$act_id' WHERE id='$point_h_id'");
  $obj_id = copy_point_from_history($point_h_id);
  return $obj_id; // newly created live point
}


function delete_rel($user_id, $rel, $act_body = '') {
  global $db_relations;
  $last_act = get_last_action_for_rel($rel);
  $act_id = mk_action($user_id, $last_act['obj_id'], 'delete', $act_body, $rel['history_id']);
  $query = "DELETE FROM $db_relations WHERE id = '" . $rel['id'] . "'";
  print($query);
  sql_query($query);
  return $last_act['obj_id']; // historical version of point
}
?>
