<div class="discoveries_in_progress">
<?
  $res = get_all_user_points_in_type($_SESSION['id'], 'order.new.');
  //$res = get_all_user_points_in_type($_SESSION['id'], 'sub.sent.unreviewed');
  if($res['rowcount'] == 0) {
      ?><p><!--You have no discoveries in progress, would you like to <a href="?go=discover">name a new theorem</a>?--><?print $thislang['progress_none'];?></p><?
  } else {
    if($res['rowcount'] == 1) {
      ?><h4><!--You have one discovery in progress:--><?print $thislang['progress_one'];?></h4><><?
    } else {
      ?><h4><!--You have --><?print $thislang['progress_youhave'];?><? print($res['rowcount']); ?> <!-- discoveries in progress:--><?print $thislang['progress_ndisc'];?></h4><?
    }
    ?>
    <br/>
    <table><?
    foreach($res['rows'] as $row) {
      ?><tr><td style="padding:0 50px 0 20px;">
      - &nbsp;
      <span class="theorem-name-title"><? print $row['title']; ?></span>. <!--This order was placed on --><?print $thislang['progress_placedon'];?><? print sqltimestamp_to_str($row['time_stamp']); ?><!-- and has order-id:  --><?print $thislang['progress_ordid'];?><code>U<? print $_SESSION['id'] . "N" . $row['id']; ?></code>.
      </td></tr><?
    }
    ?></table><?
  }
  ?>
  
  
</div>
