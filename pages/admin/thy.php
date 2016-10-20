<?
restrict_is_admin();

$here_link = "?go=admin&s=thy";

$act = set_default($_REQUEST['act'], 'search');
$search = set_default($_REQUEST['search'], null);
$offset = set_default(sql_str_escape($_REQUEST['offset']), 0);
$limit = set_default(sql_str_escape($_REQUEST['limit']), 10);

if($act == "edit-thy" or $act == "show-edit-thy") {
  $point_id = set_default($_REQUEST['point_id'], null);
  $point = get_point($point_id);
}

if($act == "edit-thy" or $act == "make-new-thy"){
  $thy_name = set_default(sql_str_escape($_REQUEST['thy_name']), null);
  $thy_body = set_default(sql_str_escape($_REQUEST['thy_body']), null);
  $thy_status = set_default(sql_str_escape($_REQUEST['thy_status']), null);
  $user_id = set_default(sql_str_escape($_REQUEST['user_id']), null);
  $abody = set_default(sql_str_escape($_REQUEST['abody']), null);

  if($act == "edit-thy"){
    edit_point($user_id, $point, $thy_status, $thy_name, $thy_body, $abody);
    $act='search';
    $search="p.id='$point_id'";
    $limit = 1;
  }

  if($act == "make-new-thy"){
    $point_id = create_point($user_id, $thy_status, $thy_name, $thy_body, $abody);
    $point = get_point($point_id);
    $act='search';
    $search="p.id='$point_id'";
    $limit = 1;
  }
}

if($act == "delete-thy"){
  $point_id = set_default($_REQUEST['point_id'], null);
  $point = get_point($point_id);

  if($point != null){
    if(delete_point($_SESSION['id'], $point) != null) {
        ?><h3 class="warning">Deleted Theory:</h3><?
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
    <span class="warning"><? print $act; ?> (<? print $point_id; ?>) failed.</span>
    </p><?
    $act='search';
    $search=null;
  }
}
?>
<h1>Theories</h1>
  <form action="?" method="get">
  <input type="hidden" name="go" value="admin">
  <input type="hidden" name="s" value="thy">
  <input type="hidden" name="act" value="search" size="70">
  <b>Search:</b> <input type="text" name="search" value="<? print $search; ?>" size="70"><br>
  Offset:
  <input type="text" name="offset" value="<? print $offset; ?>" size="10">; Limit: <input type="text" name="limit" value="<? print $limit; ?>" size="10">
  <input class="greenbutton" type="submit" value="Search!"> &nbsp; <a class="greenbutton" href="?go=admin&s=thy">Show All</a><br>
  SQL added to WHERE e.g. <code>p.id = '3'</code> for finding theories with id of 3, <code>p.title >= 'pants'</code> for finding all theories where the title contains the substring 'pants'.
  </form>

<p><a href="?go=import_theorems">Import</a></p>

<?
if($act == 'search' && $search != null) {
  $res = get_from_points_and_actions_and_user($search, "AND p.point_type LIKE 'thy.%'", $offset, $limit);
  $rows = $res['rows'];
  if($rows != null) { ?>
    <h3>Found Theories:</h3>
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
      <div class="edit-btns-right"><a href="?go=admin&s=thy&act=show-edit-thy&point_id=<? print $point['id']; ?>">edit</a> | <a href="?go=admin&s=thy&search=<? print urlencode("p.id='" . $point['id'] . "'"); ?>&limit=1">show</a></div>

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
      if($res['rowcount'] == 1 and $limit == 1) {
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


if($act == "show-edit-thy"){
  if($user_id == null or $user_id == ""){ $user_id = $_SESSION['id']; }
  ?>
  <p>
  <div class="simple-block">
  <h3> Edit Theory </h3>

  <p>
  <h4>Change point details: </h4>
  <form action="?" method="get">
  <input type="hidden" name="go" value="admin">
  <input type="hidden" name="s" value="thy">
  <input type="hidden" name="act" value="edit-thy">
  <input type="hidden" name="point_id" value="<? print($point['id']); ?>">

  <table class="input-stuff">
  <tr><td class="descr">
    Theory id: </td><td> <? print($point['id']); ?>
    </td></tr>
  <tr><td class="descr">
    Theory Name:
    </td><td>
    <input type="text" name="thy_name" size="60" value="<? print($point['title']); ?>">
    </td></tr>
  <tr>
    <td class="descr">Theory:</td>
    <td>
    <textarea name="thy_body"rows="10" cols="60"><? print($point['body']); ?></textarea>
    </td></tr>
  <tr>
    <td align="right">theory status:</td>
    <td><select name="thy_status">
    <option value="thy.unnamed." <? if($point['point_type'] == 'thy.unnamed.'){ print("selected"); } ?>>Unnamed</option>
      <option value="thy.named." <? if($point['point_type'] == 'thy.named.'){ print("selected"); } ?>>Named</option>
      <option value="thy.promised." <? if($point['point_type'] == 'thy.promised.'){ print("selected"); } ?>>Promised</option>
    </select></td></tr>
  <tr>
    <td colspan="2"></td></tr>
  <tr>
    <td colspan="2"><br>This action:</td></tr>
  <tr>
    <td class="descr">user_id:</td>
    <td><input type="text" name="user_id" size="20" value="<? print($user_id); ?>"></td></tr>
  <tr>
    <td class="descr">action_description:</td>
    <td><input type="text" name="abody" size="20" value=""></td></tr>
  <tr>
    <td colspan="2" align="center"><br>
    <input class="greenbutton" type="submit" value="Save changes"> &nbsp;&nbsp; <a class="redbutton" href="?go=admin&s=thy&act=search&search=<? print urlencode("p.id='" . $point['id'] . "'"); ?>">Cancel</a></td></tr>
  </table>
  </form>

  <h4>Other actions</h4>
  <!-- <br><br>
  To update the last-change timestamp to now:<br>
  <a class="greenbutton" href="?go=admin&s=thy&act=touch&obj_id=<? print($point['id']); ?>">Update time</a>
  <br><br> -->
  To <span class="warning">delete</span> the theory:
  <a class="redbutton" href="?go=admin&s=thy&act=delete-thy&point_id=<? print($point['id']); ?>" method="get">Delete theory</a>

  </div>
  <?
} else {
  $point = array();
}

if($act == "enter-new-thy"){
  if($user_id == null or $user_id == ""){ $user_id = $_SESSION['id']; }
  ?>
  <p>
  <div class="simple-block">
  <h3> New Theory </h3>
  <form action="?go=admin&s=thy" method="post">
  <input type="hidden" name="act" value="make-new-thy">
  <table class="input-stuff">
  <tr><td class="descr">
    Theory Name:
    </td><td>
    <input type="text" name="thy_name" size="60" value="<? print(htmlentities($thy_name)); ?>">
    </td></tr>
  <tr>
    <td class="descr">Theory:</td>
    <td>
    <textarea name="thy_body"rows="10" cols="60"><? print(htmlentities($thy_body)); ?></textarea>
    </td></tr>
  <tr>
    <td align="right">theory status:</td>
    <td><select name="thy_status">
    <option value="thy.unnamed." <? if($thm_status == 'thy.unnamed.'){ print("selected"); } ?>>Unnamed</option>
      <option value="thy.named." <? if($thm_status == 'thy.named.'){ print("selected"); } ?>>Named</option>
      <option value="thy.promised." <? if($thm_status == 'thy.promised.'){ print("selected"); } ?>>Promised</option>
    </select></td></tr>
  <tr><td colspan="2"></td></tr>
  <tr> <td colspan="2"><br>This action:</td></tr>
  <tr>
    <td class="descr">user_id:</td>
    <td><input type="text" name="user_id" size="20" value="<? print(htmlentities($user_id)); ?>"></td></tr>
  <tr>
    <td class="descr">action_description:</td>
    <td><input type="text" name="abody" size="20" value="<? print(htmlentities($abody)); ?>"></td></tr>
  <tr>
    <td colspan="2" align="center">
    <br>
    <input class="greenbutton" type="submit" value="Make new theory"> &nbsp;&nbsp; <a class="redbutton" href="?go=admin&s=thy">Cancel</a></td></tr>
  </table>
  </form>
  </div>
  <?
} else {
  ?><p><a href="?go=admin&s=thy&act=enter-new-thy">Make a new Theory</a></p>
<?
}
?>
