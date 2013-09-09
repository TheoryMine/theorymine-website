<?
restrict_is_admin();

$here_link = "?go=admin&s=certificates";

$act = set_default($_REQUEST['act'], 'search');
$search = set_default($_REQUEST['search'], null);
$offset = set_default(sql_str_escape($_REQUEST['offset']), 0);
$limit = set_default(sql_str_escape($_REQUEST['limit']), 10);

// actions that need full info on a single point
if( $act == "show-edit-thm") {
  $point_id = set_default($_REQUEST['id'], null);
  if($point_id == null) { die("no id given for edit point"); }
  $point = get_point($point_id);
  $cert_point=get_point_related_from($point,"certificate.","has_certificate.");
}


?>
<h1>CERTIFICATES</h1>
  <form action="?go=admin&s=certificates" method="post">
  <input type="hidden" name="act" value="search" size="70">
  <b>Search:</b> <input type="text" name="search" value="<? print $search; ?>" size="70"><br>
  Offset: 
  <input type="text" name="offset" value="<? print $offset; ?>" size="10">; Limit: <input type="text" name="limit" value="<? print $limit; ?>" size="10">  
  <input class="greenbutton" type="submit" value="Search!"> &nbsp; <a class="greenbutton" href="?go=admin&s=certificates">Show All</a><br>
  SQL added to WHERE e.g. <code>p.id = '3'</code> for finding points with id of 3, <code>p.title >= 'pants'</code> for finding all points where the title contains the substring 'pants'.
  </form>

<?
if($act == 'search') {
  $res = get_from_points_and_actions_and_user($search, "AND p.point_type LIKE 'certificate.%'", $offset, $limit);
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
      <div class="edit-btns-right">
      <a href="?go=certificate&pid=<? print $point['id']; ; ?>">certificate</a> |
      <a href="?go=admin&s=certificates&search=<? print urlencode("p.id='" . $point['id'] . "'"); ?>&limit=1">show</a></div>

      id: <? print $point['id']; ?>; certificate name: <? print $point['title']; ?><br>
      <? 
      if($res['rowcount'] == 1 and $limit == 1) {
        $thm_point=get_point_related_from($point,"certificate.","has_certificate.");
        ?></div>
        of theorem: (id: <? print $thm_point['id']; ?>) <? print $thm_point['title']; ?><br>
       
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


 else {
  $point = array();
}


?>
