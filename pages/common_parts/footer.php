<?
if($nofooter!=1) {
  if(in_debug_mode()) {
    ?>
    <div class="debug">
    <b>Recent Actions</b><br>
    <? 
    $res = get_user_recent_actions($_SESSION['id'], 0, 5);
    if($res['rowcount'] == 0) {
      ?>You have no previous actions.<?
    } else {
      ?><ul><?
      foreach($res['rows'] as $row) {
        ?><li>
        [<? print sqltimestamp_to_str($row['time_stamp']); ?>] (<? 
        if($action_types_view[$row['action_type']] == "rel") {
          ?><a href="?go=view_history&k=rel&act=preview&aid=<? print $row['id']; ?>&hid=<? print $row['obj_id']; ?>">view</a><?
        } elseif($action_types_view[$row['action_type']] == "point") {
          ?><a href="?go=view_history&k=point&act=preview&aid=<? print $row['id']; ?>&hid=<? print $row['obj_id']; ?>">view</a><?
        } else {
          ?><a href="?go=view_history&k=unkown&act=preview&aid=<? print $row['id']; ?>&hid=<? print $row['obj_id']; ?>">view</a><?
        }
        ?>) 
        <? if(false) {
        ?>
            id: <? print $row['id']; ?>; 
            user_id: <? print $row['user_id']; ?>;
        <?
        }
        ?>action_type: <? print $row['action_type']; ?>; history_id: <? print $row['history_id']; ?>: obj_id: <? print $row['obj_id'] ?> : 
            <? print htmlentities($row['action_body']); ?>
        </li><?
      }
      ?></ul><?
    }
    ?></div><?
  }
  ?>
  
  
  </div><!-- PAGE -->
  
  <? // don't run google-analytics for internal pages 
     // (danger of leaking information, such JS password sniffing)
  if($page != 'admin' and ! in_debug_mode()
     and $_SESSION['userkind'] != 'admin' 
     and $_SESSION['userkind'] != 'editor') { ?>
  
  <div class="social-media-links">
    <div class="twitter-link"><a href="http://www.twitter.com/theorymine" title="Follow us on Twitter" target="_blank">
      <img src="http://s.twimg.com/a/1289956304/images/business/follow/follow_twitter_button_d.png" alt="Follow us on Twitter" /></a>
    </div>
    <div class="facebook-link">
          <iframe src="http://www.facebook.com/plugins/like.php?href=http%3A%2F%2Fwww.facebook.com%2Fpages%2FTheoryMine%2F169641673060889&amp;layout=standard&amp;show_faces=true&amp;width=450&amp;action=like&amp;font=arial&amp;colorscheme=light&amp;height=80" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:450px; height:70px;" allowTransparency="true"></iframe>
      <!--<iframe  src="http://www.facebook.com/plugins/like.php?href=http%3A%2F%2Ftheorymine.co.uk%2F&amp;layout=standard&amp;show_faces=true&amp;width=450&amp;action=like&amp;font=arial&amp;colorscheme=light&amp;height=80" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:450px; height:80px;" allowTransparency="true"></iframe> -->
      <!--<iframe src="http://www.facebook.com/plugins/like.php?href=http%3A%2F%2Fwww.facebook.com%2Fpages%2FTheoryMine%2F169641673060889&amp;layout=standard&amp;show_faces=true&amp;width=450&amp;action=like&amp;font=arial&amp;colorscheme=light&amp;height=80" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:450px; height:70px;" allowTransparency="true"></iframe>-->
    </div>
    <?
    $tryc = "http://www.theorymine.co.uk/certificates/8052c0549e5ae90e56b8816d3e39ff6943/certificate_image.jpg";
    $tryc2 = urlencode($tryc);
    
    ?>
   <!--<a href="http://www.facebook.com/share.php?u=<?print $tryc2;?>" title="Show on facebook">Facebook</a>
    <a href="http://www.facebook.com/share.php?u=http://www.theorymine.co.uk" title="Show on facebook">Facebook2</a>
  -->
    <br/>
    
    
  </div>
  <? } 
  
  
  ?>
  
  <div class="footer">
    <div class="footer-links">
      <a href="?go=tc"><!--Terms & Conditions--><?print $thislang['footer_tc'];?></a> |
      <a href="?go=contact"><!--Contact Us--><?print $thislang['footer_contact'];?></a> |
      <a href="?go=privacy"><!--Privacy Policy--><?print $thislang['footer_privacy'];?></a> |
      <a href="?go=cancellation"><!--Cancellations--><?print $thislang['footer_cancellations'];?></a>
    </div>
    
    <div id="footer-copyright">
      Copyright 2011 TheoryMine Limited
    </div>
    
    <div id="footer-got-a-question">
      <!--Got a question about TheoryMine? Email us at --><?print $thislang['footer_questions'];?><a href="mailto:<? print $admin_email; ?>"><? print $admin_email; ?></a>.<?
      ?>
    </div>                                    
  </div>
  
  </body>
  </html>
  <? 
}
?>
