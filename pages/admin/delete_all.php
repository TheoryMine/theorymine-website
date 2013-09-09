<?
restrict_is_admin();

$here_link = "?go=admin&s=points";

$act = set_default($_REQUEST['act'], null);

if($act == "delete-all"){
  sql_query("DELETE FROM $db_points");
  sql_query("DELETE FROM $db_points_h");
  sql_query("DELETE FROM $db_relations");
  sql_query("DELETE FROM $db_relations_h");
  ?>
  <span class="warning"><? print $act; ?>: Deleted all points, relations, history, etc.</span>
  </p><?
  $act='search';
  $search=null;
}

?>

<p><a href="?go=admin&s=delete_all&act=delete-all">Delete all points and relations</a></p>
