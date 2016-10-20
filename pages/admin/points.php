<?
restrict_is_admin();

$here_link = "?go=admin&s=points";

$act = set_default($_REQUEST['act'], 'search');
$search = set_default($_REQUEST['search'], null);
$offset = set_default(sql_str_escape($_REQUEST['offset']), 0);
$limit = set_default(sql_str_escape($_REQUEST['limit']), 10);

if($act == "edit-point" or $act == "show-edit-point") {
  $point_id2 = set_default($_REQUEST['point_id2'], null);
  $point = get_point($point_id2);
}

if($act == "edit-point" or $act == "make-new-point"){
  $point_type2 = set_default(sql_str_escape($_REQUEST['point_type2']), null);
  $title2 = set_default(sql_str_escape($_REQUEST['title2']), null);
  $user_id2 = set_default(sql_str_escape($_REQUEST['user_id2']), null);
  $body2 = set_default(sql_str_escape($_REQUEST['body2']), null);
  $abody = set_default(sql_str_escape($_REQUEST['abody']), null);

  if($act == "edit-point"){
    edit_point($user_id2, $point, $point_type2, $title2, $body2, $abody);
    $act='search';
    $search="p.id='$point_id2'";
    $limit = 1;
  }

  if($act == "make-new-point"){
    $point_id2 = create_point($user_id2, $point_type2, $title2, $body2, $abody);
    $point = get_point($point_id2);
    $act='search';
    $search="p.id='$point_id2'";
    $limit = 1;
  }
}

if($act == "delete-point"){
  $point_id2 = set_default($_REQUEST['point_id2'], null);
  $point = get_point($point_id2);

  if($point != null){
    if(delete_point($_SESSION['id'], $point) != null) {
        ?><h3 class="warning">Deleted point:</h3><?
        $act='search';
        $search=null;
    }
    ?>
    <div class="red-block">
    (<? print $point['id']; ?>) <? print $point['title']; ?><br>
    body: <? print $point['body']; ?><br>
    point_type: <? print $point['point_type']; ?><br>
    history_id: <? print $point['history_id']; ?><br>
    action_id: <? print $point['action_id']; ?><br>
    time stamp: <? print $point['time_stamp']; ?>
    </div>
    <?
  } else {
    ?><p>
    <span class="warning"><? print $act; ?> (<? print $point_id2; ?>) failed.</span>
    </p><?
    $act='search';
    $search=null;
  }
}
?>
  <form action="?" method="get">
  <input type="hidden" name="go" value="admin">
  <input type="hidden" name="s" value="points">
  <input type="hidden" name="act" value="search" size="70">
  <b>Search:</b> <input type="text" name="search" value="<? print $search; ?>" size="70"><br>
  Offset:
  <input type="text" name="offset" value="<? print $offset; ?>" size="10">; Limit: <input type="text" name="limit" value="<? print $limit; ?>" size="10">
  <input class="greenbutton" type="submit" value="Search!"> &nbsp; <a class="greenbutton" href="?go=admin&s=points">Show All</a><br>
  SQL added to WHERE e.g. <code>p.id = '3'</code> for finding points with id of 3, <code>p.title >= 'pants'</code> for finding all points where the title contains the substring 'pants'.
  </form>

<?
if($act == 'search' && $search != null) {
  $res = get_from_points_and_actions_and_user($search, "", $offset, $limit);
  $rows = $res['rows'];
  if($rows != null) { ?>
    <h3>Found Points:</h3>
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
      <?
      if($res['rowcount'] == 1 and $limit == 1) {
        ?>body: <div class="simple-block"><? print $point['body']; ?></div>
        history_id: <? print $point['history_id']; ?><br>
        prev_id: <? print $point['prev_id']; ?><br>
        action_id: <? print $point['action_id']; ?><br>
        time_stamp: <? print $point['time_stamp']; ?><br>
        action_type: <? print $point['action_type']; ?>; action_timestamp: <? print $point['a_time_stamp']; ?>;
        action_body: <? print $point['action_body']; ?>; ipaddr: <? print $point['ipaddr']; ?><br>
        user_id: <? print $point['user_id']; ?>; firstname: <? print $point['firstname']; ?>; lastname: <? print $point['lastname']; ?>; email: <? print $point['email']; ?>;
        last_act_time: <? print $point['last_act_time']; ?>;
        last_act_kind: <? print $point['last_act_kind']; ?><br>
        <?
      }
       //print_r($point);
      ?>
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
}


if($act == "show-edit-point"){
  if($point['user_id'] == null or $point['user_id'] == ""){ $point['user_id'] = $_SESSION['id']; }
  ?>
  <p>
  <div class="simple-block">
  <h3> Edit Point </h3>

  <p>
  <h4>Change point details: </h4>
  <form action="?go=admin&s=points" method="post">
  <input type="hidden" name="act" value="edit-point">
  <input type="hidden" name="point_id2" value="<? print($point['id']); ?>">

  <table border="0">

  <tr><td align="right">point_id:</td>
  <td><? print($point['id']); ?></td></tr>

  <tr><td align="right" valign="top"><? print_required_field($point['title'], "title"); ?>:
</td>
  <td><input type="text" name="title2" size="40" value="<? print(htmlentities($point['title'])); ?>">
  </td></tr>

  <tr><td align="right">body:</td>
  <td><input type="text" name="body2" size="40" value="<? print(htmlentities($point['body'])); ?>"></td></tr>

  <tr><td align="right">point_type:</td>
  <td><input type="text" name="point_type2" size="40" value="<? print(htmlentities($point['point_type'])); ?>"></td></tr>

  <tr><td align="right">user_id:</td>
  <td><input type="text" name="user_id2" size="40" value="<? print(htmlentities($point['user_id'])); ?>"></td></tr>

  <tr><td align="right">action_body:</td>
  <td><input type="text" name="abody" size="20" value="<? print(htmlentities($point['abody'])); ?>"></td></tr>

  <tr><td colspan="2" align="center"><br>
  <input class="greenbutton" type="submit" value="Save changes"> &nbsp;&nbsp; <a class="redbutton" href="?go=admin&s=points&act=search&search=<? print urlencode("p.id='" . $point['id'] . "'"); ?>">Cancel</a></td></tr>
  </table>
  </form>

  <h4>Other actions</h4>
  <!-- <br><br>
  To update the last-change timestamp to now:<br>
  <a class="greenbutton" href="?go=admin&s=points&act=touch&obj_id=<? print($point['id']); ?>" method="post">Update time</a>
  <br><br> -->
  To <span class="warning">delete</span> the point:
  <a class="redbutton" href="?go=admin&s=points&act=delete-point&point_id2=<? print($point['id']); ?>" method="post">Delete Point</a>

  </div>
  <?
} else {
  $point = array();
}

if($act == "enter-new-point"){
  if($user_id2 == null or $user_id2 == ""){ $user_id2 = $_SESSION['id']; }
  ?>
  <p>
  <div class="simple-block">
  <h3> New Point Details </h3>
  <form action="?go=admin&s=points" method="post">
  <input type="hidden" name="act" value="make-new-point">
  <table border="0">
  <tr><td align="right" valign="top">
  <? print_required_field($title2, "title"); ?>:
  </td><td>
  <input type="text" name="title2" size="40" value="<? print(htmlentities($title2)); ?>">
  </td></tr>
  <tr><td align="right">body:</td>
  <td><input type="text" name="body2" size="30" value="<? print(htmlentities($body2)); ?>"></td></tr>
  <tr><td align="right">point_type:</td>
  <td><input type="text" name="point_type2" size="30" value="<? print(htmlentities($point_type2)); ?>"></td></tr>
  <tr><td align="right">user_id:</td>
  <td><input type="text" name="user_id2" size="20" value="<? print(htmlentities($user_id2)); ?>"></td></tr>
  <tr><td align="right">action_body:</td>
  <td><input type="text" name="abody" size="20" value="<? print(htmlentities($abody)); ?>"></td></tr>
  <tr><td colspan="2" align="center">
  <br>
  <input class="greenbutton" type="submit" value="Make new point"> &nbsp;&nbsp; <a class="redbutton" href="?go=admin&s=points">Cancel</a></td></tr>
  </table>
  </form>
  </div>
  <?
} else {
  ?><p><a href="?go=admin&s=points&act=enter-new-point">Make a new point</a></p>
<?
}
?>
