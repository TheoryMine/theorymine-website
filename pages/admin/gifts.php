<?
restrict_is_admin();

$here_link = "?go=admin&s=gifts";

$offset = set_default(sql_str_escape($_REQUEST['offset']), 0);
$limit = set_default(sql_str_escape($_REQUEST['limit']), 10);

if($act=="newgift"){
  $msg = set_default(sql_str_escape($_REQUEST['msg']), "");

  $point = change_some_point($user_id, "point_type = 'thm.unnamed'", array('point_type' => "thm.gifted", 'title' => $thmname), 'gifted.theorem', $point['id'] . " gifted.");
  if($point == null){
    ?><span class="warning">There are no unnamed theorems to give.</span><? 
  } else {
    $lock_code = genRandomString(10);
    $point_id2 = create_point($_SESSION['id'], 'lock', $lock_code, $msg, 'created lock for gift');
    create_rel($_SESSION['id'], array('relation_type' => "lock", 'dst_obj_id' => $point['id'], 'src_obj_id' => $point_id2), 'created gift rel lock');
    ?><span class="good">Gifted theorem: (<? print($point['id']); ?>) <? print($point['title']); ?> with lock code: <? print($lock_code); ?></span>
    <? 
  }
} 

?>

<p>

<a class="greenbutton" href="<? print($here_link . "&act=newgift") ?>">gift a throem</a>

<h2>Theorem we are giving away:</h2>


<?
  print($i);
  ?><br><?
  print($x);

  $res = get_rowsandsummary("SELECT p1.*, r.id as r_id, p2.id as lock_id, p2.title as lock_code, p2.body as msg FROM $db_points as p1, $db_points as p2, $db_relations as r WHERE p1.point_type = 'thm.gifted' AND r.dst_obj_id = p1.id AND r.relation_type = 'lock' AND src_obj_id = p2.id AND p2.point_type = 'lock'", $offset, $limit);

  $rows = $res['rows'];
  if($rows != null) { ?>
    <div class="simple-border">
    <?
    $toggle = true;
    $fst = true;
    foreach($rows as $point) {
      $toggle = !$toggle;
      if($fst){ $fst = false; 
        ?><div class="simple-list0"><?
      } else if($toggle){ 
        ?><div class="simple-list1"><?
      } else {
        ?><div class="simple-list2"><?
      } ?>
      <div class="edit-btns-right"><a href="?go=admin&s=points&act=show-edit-point&point_id2=<? print $point['id']; ?>">edit</a> | <a href="?go=admin&s=points&search=<? print urlencode("p.id='" . $point['id'] . "'"); ?>&limit=1">show</a></div>

      id: <? print $point['id']; ?>; title: <? print $point['title']; ?><br>
      point_type: <? print $point['point_type']; ?><br>
     
      body: <div class="simple-block"><? print $point['body']; ?></div>
      time_stamp: <? print $point['time_stamp']; ?><br> 

      lock_id: <? print $point['lock_id']; ?><br>
      lock_code: <? print $point['lock_code']; ?><br>
      message: <div class="simple-block"><? print $point['msg']; ?></div><br> 
      </div>
      <?
      if($res['rowcount'] == 1) {
        print_related_points($point);
      }
    }
    ?>
    </div>
    <?
    // rows != null
  } else {
    ?><p><span class="warning">No entries</span></p><?
  }
