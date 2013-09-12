<?
  //res = get_users_named_theorem($_SESSION['id']);
   $res = get_all_user_points_in_type($_SESSION['id'], 'order.hasthm.');
   $gadets_page_url= $site_address."/gadgets/";
  //$res = get_all_user_points_in_type($_SESSION['id'], 'sub.sent.unreviewed');
  if($res['rowcount'] == 0) {
  } else {
    ?><div class="discovered_theorems" id="discovered_theorems"><?
    if($res['rowcount'] == 1) {
      ?><h4><!--You have named one theorem--><?print $thislang['dicoveredthm_one'];?></h4><?
    } else {
      ?><h4><!--You have named --><?print $thislang['dicoveredthm_younamed'];?><? print($res['rowcount']); ?> <!--theorems:--><?print $thislang['theorems'];?></h4><?
    }
    ?>
    <br/>
    <table><?
    foreach($res['rows'] as $row) {
      $thm = get_point_related_from($row,  'thm.named.', 'named.' );  
      $cert =  get_point_related_from($row,'certificate.','has_certificate.');      
      ?><tr>
      <td style="padding:0 15px 0 20px;">
      - &nbsp;
      <a href="?go=theorem&pid=<? print($thm['id']); ?>">
      <? print $row['title']; ?></a> (THM-<? print($thm['id']);  ?>) 
      </td>
      <td>
      <!--FIXME Change pid to cid -->
      &nbsp <a href="?go=certificate_special&pid=<? 
      //print($row['id'].md5($row['title'])) ; 
      print($cert['title']); ?>"><!--View Certificate--><?print $thislang['dicoveredthm_viewcert'];?></a> &nbsp; | &nbsp; </td>
      <td>
      <a href="<?print $site_address;?>/certificates/<?print($cert['title']);?>/brouchure.pdf"
      >
      <!--View Brochure--><?print $thislang['dicoveredthm_viewbro'];?></a>&nbsp; | &nbsp;</td>
      <td>
       <a href="?go=gadgets&cid=<?print($cert['title']);?>"><!--Gift Items Shop--><?print $thislang['dicoveredthm_shop'];?></a>
        &nbsp;
      </td>
      <td><?
      $ord_id = $row['id'];
      $facebook_link = "http://www.facebook.com/plugins/like.php?href=http%3A%2F%2Fwww.theorymine.co.uk%2F%3Fgo%3Dcert_image%26pid%3D".$ord_id ."&amp;send=false&amp;layout=button_count&amp;width=50&amp;show_faces=false&amp;action=like&amp;colorscheme=light&amp;font&amp;height=21";
 ?>     
      
      
    
      
      <iframe src=<?print $facebook_link?> scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:150px; height:21px;" allowTransparency="true"></iframe>
     
      </td>
      </tr>
      
      <?
    }
    ?></table>
   <!--<iframe src="http://www.facebook.com/plugins/like.php?href=http%3A%2F%2Fwww.theorymine.co.uk%2Fcertificates%2F8052c0549e5ae90e56b8816d3e39ff6943%2Fcertificate.pdf&amp;send=true&amp;layout=standard&amp;width=450&amp;show_faces=true&amp;action=like&amp;colorscheme=light&amp;font&amp;height=80" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:450px; height:80px;" allowTransparency="true"></iframe>
      <br/>
      <script src="http://connect.facebook.net/en_US/all.js#xfbml=1"></script><fb:like href="http://www.theorymine.co.uk/certificates/8052c0549e5ae90e56b8816d3e39ff6943/certificate_image.jpg" send="false" layout="button_count" width="450" show_faces="false" font=""></fb:like>
    
    
   --> <?
    
  }?>
  </div>

