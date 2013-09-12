<?php  
$header_title = "Theorems";
include 'pages/common_parts/header.php';
$viewed_theorems= $_REQUEST['viewed_theorems'];
$this_limit= 10; 
//print "fristr" . $viewed_theorems . "<br>"; 
//print "second" .$this_limit  . "<br>"; ;
$theorems=get_all_points_in_type('thm.named', $viewed_theorems, $this_limit );

?>


<?


if($theorems['rowcount'] == 0) {
  ?>
  <div class="short-theorem-list-title">Be the first the name a theorem!</div><?
} 
else {
  ?>
  <ul class="short-theorem-list">
  <?
  foreach($theorems['rows'] as $row) {
    ?>
    <li style= "line-height: 40px;">
    <a href="?go=theorem&pid=<? print $row['id']; ?>"><? print htmlentities($row['title']); ?>
    </a><span class="date"> [<? print sqltimestamp_to_str($row['time_stamp']); ?>]</span> 
    <?
      $ord_id = $row['id'];
      $facebook_link = "http://www.facebook.com/plugins/like.php?href=http%3A%2F%2Fwww.theorymine.co.uk%2F%3Fgo%3Dcert_image%26pid%3D".$ord_id ."&amp;send=false&amp;layout=button_count&amp;width=50&amp;show_faces=false&amp;action=like&amp;colorscheme=light&amp;font&amp;height=21";
      ?>
      <iframe src=<?print $facebook_link?> scrolling="no" frameborder="0"
      style="border:none; overflow:hidden; width:100px; height:21px; padding-left:  30px;" allowTransparency="true"></iframe>
    </li><?
    
      }
  ?></ul><div class="more-theorems"><?
  
   if ($viewed_theorems  >=  $this_limit){
   $viewed_theorems2 = $viewed_theorems - $this_limit;
    ?><a href="?go=theorems&viewed_theorems=<? print $viewed_theorems2 ; ?>">Previous</a><?
  }
  
  if (($viewed_theorems  >=  $this_limit) && ($theorems['more'])){?>
  	  |
  <?}
  
  if($theorems['more']){

    $viewed_theorems2 = $viewed_theorems + $this_limit;
    ?> <a href="?go=theorems&viewed_theorems=<? print $viewed_theorems2 ; ?>">Next</a><? 
  } ?>
  
  </div>
  
 <?
 
}
  
?>






