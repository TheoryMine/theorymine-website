<?
restrict_is_admin();

$here_link = "?go=admin&s=rels";

$act = set_default($_REQUEST['act'], 'search');
$search = set_default($_REQUEST['search'], null);
$offset = set_default(sql_str_escape($_REQUEST['offset']), 0);
$limit = set_default(sql_str_escape($_REQUEST['limit']), 10);

if($act == "edit-rel" or $act == "show-edit-rel") {
  $rel_id = set_default($_REQUEST['rel_id'], null);
  $rel = get_rel($rel_id);
}

if($act == "edit-rel" or $act == "make-new-rel"){
  $pid1 = set_default(sql_str_escape($_REQUEST['pid1']), null);
  $pid2 = set_default(sql_str_escape($_REQUEST['pid2']), null);
  $rel_type = set_default(sql_str_escape($_REQUEST['rtype']), null);
  $user_id = set_default(sql_str_escape($_REQUEST['user_id']), null);
  $act_body = set_default(sql_str_escape($_REQUEST['abody']), null);

  if($act == "edit-rel"){
    $rel2 = array("relation_type" => $rel_type, 'src_obj_id' => $pid1, 'dst_obj_id' => $pid2);
    edit_rel($user_id, $rel, $rel2, $act_body);
    $act='search';
    $search="r.id='$rel_id2'";
  }
  
  if($act == "make-new-rel"){ 
    $rel = array('src_obj_id' => $pid1, 
                 'dst_obj_id' => $pid2, 
                 'relation_type' => $rel_type);
    $rel_id = create_rel($user_id, $rel, $act_body);
    $rel = get_rel($rel_id);
    $act='search';
    $search="r.id='$rel_id'";
  }
}

if($act == "delete-rel"){
  $rel_id = set_default($_REQUEST['rel_id'], null);
  $rel = get_rel($rel_id);

  if($rel != null){
    if(delete_rel($_SESSION['id'], $rel, 'admin.del.') != null) {
        ?><h3 class="warning">Deleted rel:</h3><?
        $act='search';
        $search=null;
    }
    ?>
    <div class="red-block">
    (<? print $rel['id']; ?>) <? print $rel['title']; ?><br>
    relation_type: <? print $rel['relation_type']; ?><br>
    src_obj_id: <? print $rel['src_obj_id']; ?><br>
    dst_obj_id: <? print $rel['dst_obj_id']; ?><br>
    prev_id: <? print $rel['prev_id']; ?><br>
    history_id: <? print $rel['history_id']; ?><br>
    action_id: <? print $rel['action_id']; ?><br> 
    time stamp: <? print $rel['time_stamp']; ?>
    </div>
    <?
  } else {
    ?><p>
    <span class="warning"><? print $act; ?> (<? print $rel_id; ?>) failed.</span>
    </p><?
    $act='search';
    $search=null;
  }
}
?>
<h3>Search</h3>

  <form action="?go=admin&s=rels" method="post">
  <input type="hidden" name="act" value="search" size="70">
  <input type="text" name="search" value="<? print $search; ?>" size="70">
  <input class="greenbutton" type="submit" value="Search!"> &nbsp; <a class="greenbutton" href="?go=admin&s=rels">Show All</a><br>
  e.g. <code>r.id = '3'</code> for finding rels with id of 3, <code>r.relation_type >= 'pants'</code> for finding all relations where the type contains the substring 'pants'.
  </form>

<?
if($act == 'search') {
  $res = get_from_rels_and_stuff($search);
  $rows = $res['rows'];
  if($rows != null) { ?>
    <h3>Found Rels:</h3>
    <div class="simple-border">
    <?
    $toggle = true;
    $fst = true;
    foreach($rows as $rel) {
      $toggle = !$toggle;
      if($fst){ $fst = false; 
        ?><div class="simple-list0"><?
      } else if($toggle){ 
        ?><div class="simple-list1"><?
      } else {
        ?><div class="simple-list2"><?
      } ?>
      <div class="edit-btns-right"><a href="?go=admin&s=rels&act=show-edit-rel&rel_id=<? print $rel['id']; ?>">edit</a> | <a href="?go=admin&s=rels&search=<? print urlencode("r.id='" . $rel['id'] . "'"); ?>">show</a></div>

      id: <? print $rel['id']; ?>; relation_type: <? print $rel['relation_type']; ?><br>
      src_obj_id: (<? print $rel['src_obj_id']; ?>; <? print $rel['p1_type']; ?>) <? print $rel['p1_title']; ?> <br>
      dst_obj_id: (<? print $rel['dst_obj_id']; ?>; <? print $rel['p2_type']; ?>) <? print $rel['p2_title']; ?>
      <? 
      if($res['rowcount'] == 1 and $limit == 1) {
        ?>
        history_id: <? print $rel['history_id']; ?><br>
        prev_id: <? print $rel['prev_id']; ?><br>
        action_id: <? print $rel['action_id']; ?><br> 
        time_stamp: <? print $rel['time_stamp']; ?><br> 
        action_type: <? print $rel['action_type']; ?>; action_timestamp: <? print $rel['a_time_stamp']; ?>; 
        action_body: <? print $rel['action_body']; ?>; ipaddr: <? print $rel['ipaddr']; ?><br> 
        user_id: <? print $rel['user_id']; ?>; firstname: <? print $rel['firstname']; ?>; lastname: <? print $rel['lastname']; ?>; email: <? print $rel['email']; ?>; 
        last_act_time: <? print $rel['last_act_time']; ?>;  
        last_act_kind: <? print $rel['last_act_kind']; ?><br> 
        <? 
      }
       //print_r($point);
      ?>
      </div>
      <?
    }
    ?>
    </div>
    <?
    // rows != null
  } else {
    ?><p><span class="warning">No entries</span></p><?
  }
}


if($act == "show-edit-rel"){
  if($rel['user_id'] == null or $rel['user_id'] == ""){ $rel['user_id'] = $_SESSION['id']; } 
  ?>
  <p>
  <div class="simple-block">
  <h3> Edit Rel </h3>
  
  <p> 
  <h4>Change rel details: </h4>
  <form action="?go=admin&s=rels" method="post">
  <input type="hidden" name="act" value="edit-rel">
  <input type="hidden" name="rel_id" value="<? print($rel['id']); ?>">

  <table border="0">
  
  <tr><td align="right">rel_id:</td>
  <td><? print($rel['id']); ?></td></tr>
 
  <tr><td align="right" valign="top"><? print_required_field($rel['relation_type'], "relation_type"); ?>:
</td>
  <td><input type="text" name="rtype" size="40" value="<? print(htmlentities($rel['relation_type'])); ?>">
  </td></tr>
  
  <tr><td align="right">src_obj_id:</td>
  <td><input type="text" name="pid1" size="40" value="<? print(htmlentities($rel['src_obj_id'])); ?>"></td></tr>
  
  <tr><td align="right">dst_obj_id:</td>
  <td><input type="text" name="pid2" size="40" value="<? print(htmlentities($rel['dst_obj_id'])); ?>"></td></tr>
  
  <tr><td align="right">user_id:</td>
  <td><input type="text" name="user_id" size="40" value="<? print(htmlentities($rel['user_id'])); ?>"></td></tr>

  <tr><td align="right">action_body:</td>
  <td><input type="text" name="abody" size="40" value="<? print(htmlentities($rel['action_body'])); ?>"></td></tr>
  
  <tr><td colspan="2" align="center"><br>
  <input class="greenbutton" type="submit" value="Save changes"> &nbsp;&nbsp; <a class="redbutton" href="?go=admin&s=rels&act=search&search=<? print urlencode("r.id='" . $rel['id'] . "'"); ?>">Cancel</a></td></tr>
  </table>
  </form>
  
  <h4>Other actions</h4>
  <!-- <br><br>
  To update the last-change timestamp to now:<br>
  <a class="greenbutton" href="?go=admin&s=rels&act=touch&obj_id=<? print($rel['id']); ?>" method="post">Update time</a> 
  <br><br> -->
  To <span class="warning">delete</span> the rel: 
  <a class="redbutton" href="?go=admin&s=rels&act=delete-rel&rel_id=<? print($rel['id']); ?>" method="post">Delete rel</a>
  
  </div>
  <?
} else {
  $rel = array();
}

if($act == "enter-new-rel"){
  if($user_id == null or $user_id == ""){ $user_id = $_SESSION['id']; } 
  ?>
  <p> 
  <div class="simple-block">
  <h3> New Rel Details </h3>
  <form action="?go=admin&s=rels" method="post">
  <input type="hidden" name="act" value="make-new-rel">
  <table border="0">
  <tr><td align="right" valign="top">
  <? print_required_field($rel_type, "relation_type"); ?>:
  </td><td>
  <input type="text" name="rtype" size="40" value="<? print(htmlentities($rel_type)); ?>">
  </td></tr>

  <tr><td align="right">src_obj_id:</td>
  <td><input type="text" name="pid1" size="40" value="<? print(htmlentities($pid1)); ?>"></td></tr>
  
  <tr><td align="right">dst_obj_id:</td>
  <td><input type="text" name="pid2" size="40" value="<? print(htmlentities($pid2)); ?>"></td></tr>
  
  <tr><td align="right">user_id:</td>
  <td><input type="text" name="user_id" size="40" value="<? print(htmlentities($user_id)); ?>"></td></tr>

  <tr><td align="right">action_body:</td>
  <td><input type="text" name="abody" size="40" value="<? print(htmlentities($abody)); ?>"></td></tr>

  <tr><td colspan="2" align="center">
  <br>
  <input class="greenbutton" type="submit" value="Make new rel"> &nbsp;&nbsp; <a class="redbutton" href="?go=admin&s=rels">Cancel</a></td></tr>
  </table>
  </form>
  </div>
  <?
} else {
  ?><p><a href="?go=admin&s=rels&act=enter-new-rel">Make a new rel</a></p><?
}
?>
