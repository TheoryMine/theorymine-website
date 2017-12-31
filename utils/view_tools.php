<?


// get all points of a given kind which are related to this point
function get_users_named_theorem($user_id) {
  global $db_points, $db_relations, $db_actions;

  return get_rowsandsummary("SELECT p.*, r.relation_type, r.dst_obj_id, r.src_obj_id, r.id as r_id, r.history_id as r_history_id, r.prev_id as r_prev_id, r.action_id as r_action_id, r.time_stamp as r_time_stamp FROM $db_points as p, $db_points as p2, $db_relations as r, $db_actions as a "
  ." WHERE a.user_id = $user_id "
  ." AND a.action_type = 'create_point' "
  ." AND a.history_id = p2.history_id "
  ." AND p2.point_type LIKE 'order.%'"
  ." AND p2.id = r.src_obj_id "
  ." AND r.relation_type LIKE 'named.%' "
  ." AND r.dst_obj_id = p.id "
  ." AND p.point_type LIKE 'thm.named.%'");
}

function print_related_points_info($rpoint) {
  if(preg_match('/^thm\\./', $rpoint['point_type'])) {
    print('<a href="https://theorymine.com/?go=admin&s=thm&search=p.id%3D%27' .
      $rpoint['id'] . '%27&limit=1">Thm: ' . $rpoint['title'] . '</a>' . ': ' . $rpoint['body'] .
      ' (' . $rpoint['point_type'] . ')');
  } else if(preg_match('/^order\\./', $rpoint['point_type'])) {
    print('<a href="https://theorymine.com/?go=admin&s=orders&search=p.id%3D%27' .
      $rpoint['id'] . '%27&limit=1">Order: ' . $rpoint['title'] . '</a>' .
      ' (' . $rpoint['point_type'] . ')');
  } else if(preg_match('/^thy\\./', $rpoint['point_type'])) {
    print('<a href="https://theorymine.com/?go=admin&s=thy&search=p.id%3D%27' .
      $rpoint['id'] . '%27&limit=1">Thy: ' . $rpoint['title'] . '</a>' .
      ' (' . $rpoint['point_type'] . ')');
  } else {
    print('[' . $rpoint['id'] . ']' . $rpoint['title'] .
      ' (' . $rpoint['point_type'] . ')');
  }
}

// basic print for all points related to this one. 
// for debugging really.  
function print_related_points($point) {
    $relps_to = get_points_related_to($point);
    $relps_from = get_points_related_from($point);
    if($relps_to['rowcount'] > 0 or $relps_from['rowcount'] > 0) {
      ?><ul><?
    }
    if($relps_to['rowcount'] > 0) {
      foreach($relps_to['rows'] as $rpoint) {
        ?>
        <li>[<? print($rpoint['r_id']); ?>] <? print($rpoint['relation_type']); ?> &larr;
        <?
        print_related_points_info($rpoint);
      }
    }
    if($relps_from['rowcount'] > 0) {
      foreach($relps_from['rows'] as $rpoint) {
        ?>
        <li>[<? print($rpoint['r_id']); ?>] <? print($rpoint['relation_type']); ?> &rarr;
        <?
        print_related_points_info($rpoint);
      }
    }
    if($relps_to['rowcount'] > 0 or $relps_from['rowcount'] > 0) {
      ?></ul><?
    }
}



?>
