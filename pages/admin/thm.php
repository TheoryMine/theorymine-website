<?
restrict_is_admin();

$here_link = "?go=admin&s=thm";

$act = set_default($_REQUEST['act'], 'search');
$search = set_default($_REQUEST['search'], null);
$offset = set_default(sql_str_escape($_REQUEST['offset']), 0);
$limit = set_default(sql_str_escape($_REQUEST['limit']), 10);

// actions that need full info on a single point
if($act == "edit-thm" or $act == "show-edit-thm" or $act == "delete-thm") {
  $point_id = set_default($_REQUEST['id'], null);
  if($point_id == null) { die("no id given for edit point"); }
  $point = get_point($point_id);
  $proof_point = get_point_related_to($point,"proof.","proof.");
  $thy_point = get_point_related_from($point,"thy.","inthy.");
}

if($act == "edit-thm" or $act == "make-new-thm"){
  $user_id = set_default(sql_str_escape($_REQUEST['user_id']), null);
  $abody = set_default(sql_str_escape($_REQUEST['abody']), null);

  $thm_name = set_default(sql_str_escape($_REQUEST['thm_name']), null);
  $thm_statement = set_default(sql_str_escape($_REQUEST['thm_statement']), null);
  $thm_proof = set_default(sql_str_escape($_REQUEST['thm_proof']), null);
  $thm_thyid = set_default(sql_str_escape($_REQUEST['thm_thyid']), null);
  $thm_status = set_default(sql_str_escape($_REQUEST['thm_status']), null);

  $newthy_point = get_point_of_type($thm_thyid, "thy.");
  if($newthy_point == null) {
    die_at_user_error("not a valid theory: " . $thm_thyid);
    //die_at_noted_problem("not a valid theory: " . $thm_thyid);
  }

  if($act == "edit-thm"){
    // edit the theorem 
    edit_point($user_id, $point, $thm_status, $thm_name, $thm_statement, $abody);
    // edit it's proof
    edit_point($user_id, $proof_point, $proof_point['point_type'], $thm_statement, $thm_proof, $abody);
    // if it's been moved to a different theory, edit the relation. 
    if($thm_thyid != $thy_point['id']) {
      $inthy_rel = array('src_obj_id' => $point_id, 
             'dst_obj_id' => $thm_thyid, 
             'relation_type' => 'inthy.');
      $old_inthy_rel = array(
             'id' => $thy_point['r_id'],
             'prev_id' => $thy_point['r_prev_id'],
             'history_id' => $thy_point['r_history_id'], 
             'src_obj_id' => $thy_point['src_obj_id'],
             'dst_obj_id' => $thy_point['dst_obj_id'], 
             'relation_type' => $thy_point['relation_type'], 
             );
      edit_rel($user_id, $old_inthy_rel, $inthy_rel, $abody);
    }
    $thy_point = $newthy_point;
    $act='search';
    $search="p.id='$point_id'";
    $limit = 1;
  }
  
  if($act == "make-new-thm"){
    $thy_point = $newthy_point;
    $point_id = create_point($user_id, $thm_status, $thm_name, $thm_statement, $abody);

    $proof_point_id = create_point($user_id, 'proof.', $thm_statement, $thm_proof, $abody);

    $proof_rel = array('src_obj_id' => $proof_point_id, 
                 'dst_obj_id' => $point_id, 
                 'relation_type' => 'proof.');
    $proof_rel_id = create_rel($user_id, $proof_rel, $abody);

    $inthy_rel = array('src_obj_id' => $point_id, 
                 'dst_obj_id' => $thm_thyid, 
                 'relation_type' => 'inthy.');
    $inthy_rel_id = create_rel($user_id, $inthy_rel, $abody);

    $point = get_point($point_id);
    $act='search';
    $search="p.id='$point_id'";
    $limit = 1;
  }
}

if($act == "delete-thm"){
  if($point != null){
    if(delete_point($_SESSION['id'], $point) != null) {
        ?><h3 class="warning">Deleted theorem:</h3><?
        $act='search';
        $search=null;
    }
    if(delete_point($_SESSION['id'], $proof_point) != null) {
        ?><h3 class="warning">(Deleted proof too)</h3><?
    }
    ?>
    <div class="red-block">
    (<? print $point['id']; ?>) <? print $point['title']; ?><br>
    theorem statement: <? print $point['body']; ?><br>
    theorem status: <? print $point['point_type']; ?><br>
    theorem proof: <? print $proof_point['body']; ?><br>
    theorem was in theory: <? print $thy_point['title']; ?><br>
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
<h1>Theorems</h1>
  <form action="?go=admin&s=thm" method="post">
  <input type="hidden" name="act" value="search" size="70">
  <b>Search:</b> <input type="text" name="search" value="<? print $search; ?>" size="70"><br>
  Offset: 
  <input type="text" name="offset" value="<? print $offset; ?>" size="10">; Limit: <input type="text" name="limit" value="<? print $limit; ?>" size="10">  
  <input class="greenbutton" type="submit" value="Search!"> &nbsp; <a class="greenbutton" href="?go=admin&s=thm">Show All</a><br>
  SQL added to WHERE e.g. <code>p.id = '3'</code> for finding points with id of 3, <code>p.title >= 'pants'</code> for finding all points where the title contains the substring 'pants'.
  </form>

<?
if($act == 'search') {
  $res = get_from_points_and_actions_and_user($search, "AND p.point_type LIKE 'thm.%'", $offset, $limit);
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
      <div class="edit-btns-right"><a href="?go=admin&s=thm&act=show-edit-thm&id=<? print $point['id']; ?>">edit</a> | <a href="?go=admin&s=thm&search=<? print urlencode("p.id='" . $point['id'] . "'"); ?>&limit=1">show</a></div>

      id: <? print $point['id']; ?>; theorem name: <? print $point['title']; ?><br>
      theorem status: <? print $point['point_type']; ?><br>
      <? 
      if($res['rowcount'] == 1 and $limit == 1) {
        $proof_point = get_point_related_to($point,"proof.","proof.");
        $thy_point = get_point_related_from($point,"thy.","inthy.");
        ?>theorem statement: <div class="simple-block"><? print $point['body']; ?></div>
        in theory: (id: <? print $thy_point['id']; ?>) <? print $thy_point['title']; ?><br>
        proof: (id: <? print $proof_point['id']; ?>) <? print $proof_point['body']; ?><br>
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


if($act == "show-edit-thm"){
  if($point['user_id'] == null or $point['user_id'] == ""){ 
    $point['user_id'] = $_SESSION['id']; 
  } 
  ?>
  <p>

  <div class="simple-block">
  <h3> Edit Theorem </h3>

  <form action="?go=admin&s=thm" method="post">
  <input type="hidden" name="act" value="edit-thm">
  <input type="hidden" name="id" value="<? print($point['id']); ?>">

  <table border="0">
  <tr><td align="right" valign="top">
  Theorem Name:
  </td><td>
  <input type="text" name="thm_name" size="60" value="<? print(htmlentities($point['title'])); ?>">
  </td>
  </tr><tr>
    <td align="right">Theorem statement:</td>
    <td><input type="text" name="thm_statement" size="60" value="<? print(htmlentities($point['body'])); ?>"></td>
  </tr><tr>
    <td align="right">Proof:</td>
    <td><input type="text" name="thm_proof" size="60" value="<? print(htmlentities($proof_point['body'])); ?>"></td>
  </tr><tr>
    <td align="right">In theory id:</td>
    <td><input type="text" name="thm_thyid" size="60" value="<? print(htmlentities($thy_point['id'])); ?>"></td>
  </tr><tr>
    <td align="right">Theorem status:</td>
    <td><select name="thm_status">
    <option value="thm.unnamed." <? if($point['point_type'] == 'thm.unnamed.'){ print("selected"); } ?>>Unnamed</option>
    <option value="thm.inprocess." <? if($point['point_type'] == 'thm.inprocess.'){ print("selected"); } ?>>Inprocess</option>
      <option value="thm.named." <? if($point['point_type'] == 'thm.named.'){ print("selected"); } ?>>Named</option>
    </select></td>
  </tr><tr>
  <td colspan="2"></td>
  </tr><tr>
  <td colspan="2"><br>This action:</td>
  </tr><tr>
    <td align="right">user_id:</td>
    <td><input type="text" name="user_id" size="20" value="<? print(htmlentities($point['user_id'])); ?>"></td>
  </tr><tr>
    <td align="right">action_description:</td>
    <td><input type="text" name="abody" size="20" value="<? print(htmlentities($abody)); ?>"></td>
  </tr>
  <tr><td colspan="2" align="center"><br>
  <input class="greenbutton" type="submit" value="Save changes"> &nbsp;&nbsp; <a class="redbutton" href="?go=admin&s=thm&act=search&search=<? print urlencode("p.id='" . $point['id'] . "'"); ?>">Cancel</a></td></tr>
  </table>
  </form>
  
  <h4>Other actions</h4>
  <!-- <br><br>
  To update the last-change timestamp to now:<br>
  <a class="greenbutton" href="?go=admin&s=thm&act=touch&obj_id=<? print($point['id']); ?>" method="post">Update time</a> 
  <br><br> -->
  To <span class="warning">delete</span> the theorem: 
  <a class="redbutton" href="?go=admin&s=thm&act=delete-thm&id=<? print($point['id']); ?>" method="post">Delete theorem</a>
  
  </div>
  <?
} else {
  $point = array();
}

if($act == "enter-new-thm"){
  if($user_id == null or $user_id == ""){ $user_id = $_SESSION['id']; } 
  ?>
  <p> 
  <div class="simple-block">
  <h3> New Theorem Details </h3>
  <form action="?go=admin&s=thm" method="post">
  <input type="hidden" name="act" value="make-new-thm">
  <table border="0">
  <tr><td align="right" valign="top">
  Theorem Name:
  </td><td>
  <input type="text" name="thm_name" size="60" value="<? print(htmlentities($thm_name)); ?>">
  </td>
  </tr><tr>
    <td align="right">Theorem statement:</td>
    <td><input type="text" name="thm_statement" size="60" value="<? print(htmlentities($thm_statement)); ?>"></td>
  </tr><tr>
    <td align="right">Proof:</td>
    <td><input type="text" name="thm_proof" size="60" value="<? print(htmlentities($thm_proof)); ?>"></td>
  </tr><tr>
    <td align="right">In theory id:</td>
    <td><input type="text" name="thm_thyid" size="60" value="<? print(htmlentities($thm_thyid)); ?>"></td>
  </tr><tr>
    <td align="right">Theorem status:</td>
    <td><select name="thm_status">
    <option value="thm.unnamed." <? if($thm_status == 'thm.unnamed.'){ print("selected"); } ?>>Unnamed</option>
        <option value="thm.inprocess." <? if($thm_status == 'thm.inprocess.'){ print("selected"); } ?>>Inprocess</option>
      <option value="thm.named." <? if($thm_status == 'thm.named.'){ print("selected"); } ?>>Named</option>
    </select></td>
  </tr><tr>
  <td colspan="2"></td>
  </tr><tr>
  <td colspan="2"><br>This action:</td>
  </tr><tr>
    <td align="right">user_id:</td>
    <td><input type="text" name="user_id" size="20" value="<? print(htmlentities($user_id)); ?>"></td>
  </tr><tr>
    <td align="right">action_description:</td>
    <td><input type="text" name="abody" size="20" value="<? print(htmlentities($abody)); ?>"></td>
  </tr><tr>
    <td colspan="2" align="center">
    <br>
    <input class="greenbutton" type="submit" value="Make new theorem"> &nbsp;&nbsp; <a class="redbutton" href="?go=admin&s=thm">Cancel</a></td>
  </tr>
  </table>
  </form>
  </div>
  <?
} else {
  ?><p><a href="?go=admin&s=thm&act=enter-new-thm">Make a new theorem</a></p> 
<?
}
?>
