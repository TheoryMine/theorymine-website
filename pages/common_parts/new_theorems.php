<div class="new_theorems">
<h3 style="color:#b4975a;">Latest Discovered Theorems:</h3>
<br/>

<?
$res = get_all_points_in_type('thm.named', 0, 5);
if($res['rowcount'] == 0) {
  ?><div class="short-theorem-list-title">Be the first the name a theorem!</div><?
} else {
  ?>
  <ul class="short-theorem-list"><?
  foreach($res['rows'] as $row) {
    ?><li>
    <a href="?go=theorem&pid=<? print $row['id']; ?>">
    <? print htmlentities($row['title']); ?></a><span class="date">
    [<? print sqltimestamp_to_str($row['time_stamp']); ?>]</span> 
    </li><?
  }

  
}

/*  if($res['more']){
  	  
    ?><br/><a  href="?go=theorems"> See more...</a>
<?
  }*/
?>
</div>

