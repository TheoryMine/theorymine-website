<?
$kind = set_default($_REQUEST['k'], null);
$types = $type_kinds[$kind]['types'];
$names = $type_kinds[$kind]['names'];

$loc_prefix = "?go=view_history&k=$kind";

$style = set_default($_REQUEST['s'], null);

$hid = set_default($_REQUEST['hid'], null);

if($kind == 'point') {
  $point = get_user_history_point($_SESSION['id'], $hid);
  $title = "View Point Action: " . $point['id'] . " : " . $point['title'];
} elseif ($kind == 'rel') {
  $rel = get_user_history_relation($_SESSION['id'], $hid);
  $title = "View Relation Action: " . $rel['id'] . " : " . $point['title'];
}

include 'pages/common_parts/header.php';

?>
<div class="backarrow"><a href="?">Return to overview</a></div>

<? 
if($kind == 'point') {
  ?><p>Action on Point:
  [<? print string_of_point_type($point); ?>] 
  </p>
  <div class="simple-block">
  <div class="point-title">Title: <b><? print $point['title']; ?></b></div>
  <div class="point-body"><? print $point['body']; ?></div>
  </div>
  id: <? print $point['id']; ?>; 
  Time-stamp: <? print $point['time_stamp']; ?>; 
  history-id: <? print $point['history_id']; ?>; 
  action-id: <? print $point['action_id']; ?>; 
  type: <? print $point['point_type']; ?>;  
  <?
} elseif ($kind == 'rel') {
  ?><p>Action on Relation:
  [<? print string_of_rel_type($rel); ?>] 
  </p>
  <div class="simple-block">
  <div class="point-title">Relation type: <b><? print $rel['relation_type']; ?></b></div>
  <div class="point-body">
  src_obj_id: <? print $rel['src_obj_id']; ?>; 
  dst_obj_id: <? print $rel['dst_obj_id']; ?>; 
  </div>
  </div>
  id: <? print $point['id']; ?>; 
  Time-stamp: <? print $rel['time_stamp']; ?>; 
  history-id: <? print $rel['history_id']; ?>; 
  action-id: <? print $rel['action_id']; ?>; 
  <?
} else {
  
}

?>
