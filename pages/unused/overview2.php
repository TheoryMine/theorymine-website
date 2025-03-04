<?
$here_link = "?go=overview";
$title = "TheoryMine: Home Page";
include 'pages/common_parts/header.php';
?>



<script type="text/javascript">

if (document.images)
{
  pic1= new Image(768,515); 
  pic1.src="url(images/buy_backgroundcrop.png)"; 
   pic2= new Image(768,515); 
  pic2.src="url(images/journal_backgroundcrop.png)"; 
   pic3= new Image(768,515); 
  pic3.src="url(images/products_backgroundcrop.png)"; 
   pic4= new Image(768,515); 
  pic4.src="url(images/faq_backgroundcrop.png)"; 
   pic5= new Image(768,515); 
  pic5.src="url(images/testimonials_backgroundcrop.png)"; 
 
}

var backImage = new Array();
backImage[0] = "white url(images/buy_backgroundcrop.png) no-repeat bottom right";
backImage[1] = "white url(images/journal_backgroundcrop.png) no-repeat bottom right";
backImage[2] = "white url(images/products_backgroundcrop.png) no-repeat bottom right";
backImage[3] = "white url(images/faq_backgroundcrop.png) no-repeat bottom right";
backImage[4] = "white url(images/testimonials_backgroundcrop.png) no-repeat bottom right";


function mouseover(image) 
{ 
// document.getElementById("main_buttons").src = "images/buy_background.png";
//document.getElementById("main_buttons").style.backgroundColor='red'; 
//document.getElementById("main_buttons").src = "images/buy_background.png";
document.getElementById("main_buttons").style.background = backImage[image];
//document.getElementById("main_buttons").style.background = "url('/images/buy_background.png') no-repeat top right"; 
} 
function mouseout() 
{ 
document.getElementById("main_buttons").style.background = 'white url(images/home_backgroundcrop.png) no-repeat bottom right';
//document.getElementById("main_buttons").style.background = "url('/images/homebackground.png') no-repeat top right"; 
}
</script>


<div class="main_outline" > 


<!-- Top menu bar -->

<div class="menu_bar">

<a id="this_page" href="?go=overview">Home Page</a>
<a href="?go=discover" onMouseOver="mouseover(0)" onMouseOut="mouseout()">Name a Theorem*</a>
<a href="?go=products" onMouseOver="mouseover(2)" onMouseOut="mouseout()">Gift Packs</a>
<a href="?go=faq" onMouseOver="mouseover(3)" onMouseOut="mouseout()">F.A.Q</a>
<a href="?go=testimonials" onMouseOver="mouseover(1)" onMouseOut="mouseout()" >Testimonials</a>

</div>	

<div class="main_body" >

<div class="main_buttons" id="main_buttons" >

<div class="prop"></div>

<div class="welcome" style="width: 600px">
<p class="hometext">
TheoryMine offers personalized, newly discovered, mathematical theorems as a novelty gift. <br/><br/>
<font size="2px">
By naming your very own mathematical theorem, newly generated by one of the world's most advanced computerised theorem provers (a kind of robot mathematician), 
you can immortalise your loved ones, teachers, friends and even yourself and your favourite pets. 
</font> </p>
<font color=#CC0000 size="3px"> *&pound;15: Special offer for August 2010</font> 
</div> <br/>


<?if(!isset($_SESSION['id'])) {?>
<div class="hpage_button2"  onMouseOver="mouseover(3)" onMouseOut="mouseout()" > 
<a class="number_button2" href="?go=discover" onMouseOver="mouseover(3)" onMouseOut="mouseout()">  1 </a>
<font color= #b4975a><b>YOU</b></font> <br/>
<a style="color: black; text-decoration:none;" href="?go=discover">&nbsp; &nbsp; &nbsp; &nbsp; choose a name* </a>
</div>
<br/>


 <div class="hpage_button" onMouseOver="mouseover(0)" onMouseOut="mouseout()"style="margin-left:40px;" > 
<a class="number_button2" href="?go=faq" onMouseOver="mouseover(0)" onMouseOut="mouseout()"> 2 </a>
<font color= #b4975a><b>WE</b></font><br/>
 &nbsp; &nbsp; &nbsp; &nbsp; make a theorem
</div>
<br/>




 <div class="hpage_button" onMouseOver="mouseover(2)" onMouseOut="mouseout()" style="margin-left:80px;"> 
<a class="number_button2" href="?go=products" onMouseOver="mouseover(2)" onMouseOut="mouseout()"> 3 </a>
<font color= #b4975a><b>THEY</b></font><br/>
 &nbsp; &nbsp; &nbsp; &nbsp;  receive a great gift 
</div>





<? } 
else {
?>

<p>
<font  size="4px" weight="bold">
Discover new mathematics and 
<a  href="?go=discover" onMouseOver="mouseover(0)" onMouseOut="mouseout()">
name a  theorem.</a> </font>
</p>
<!-- 
<li>If you have have been given a name-a-theorem gift, then you can <a class="greenbutton" href="?go=gifted">enter your gift-code</a> to name your own theorem.
</li>-->
<font  size="3px">

<?
  $res = get_all_user_points_in_type($_SESSION['id'], 'order.new.');
  //$res = get_all_user_points_in_type($_SESSION['id'], 'sub.sent.unreviewed');
  if($res['rowcount'] == 0) {
  } else {
    if($res['rowcount'] == 1) {
      ?><h4>You have one discovery in progress</h4><?
    } else {
      ?><h4>You have <? print($res['rowcount']); ?> discoveries in progress</h4><?
    }
    ?>
    <ul><?
    foreach($res['rows'] as $row) {
      ?><li>
      <? print htmlentities($row['title']); ?> (ORDER-<? print htmlentities($row['id']); ?>)
      </li><?
      
    }
    ?></ul><?
  }
  ?>
</p>



<?
  //res = get_users_named_theorem($_SESSION['id']);
   $res = get_all_user_points_in_type($_SESSION['id'], 'order.hasthm.');
  //$res = get_all_user_points_in_type($_SESSION['id'], 'sub.sent.unreviewed');
  if($res['rowcount'] == 0) {
  } else {
    if($res['rowcount'] == 1) {
      ?><h4>You have named one theorem</h4><?
    } else {
      ?><h4>You have named <? print($res['rowcount']); ?> theorems</h4><?
    }
    ?>
    <ul><?
    foreach($res['rows'] as $row) {
      $thm = get_point_related_from($row,  'thm.named.', 'named.' );
      ?><li>
      <a href="?go=theorem&pid=<? print($thm['id']); ?>">
      <? print htmlentities($row['title']); ?></a> (THM-<? print($row['id']); ?>)
      &nbsp <a href="?go=certificate&pid=<? print $row['id']; ; ?>"> View Certificate</a>
      </li><?
     
      
   
     
    }
    ?></ul><?
  }
}?>
</p> </font>


<div class="clear"></div>
</div> <!-- finish upper part page  -->




						
<table class="home_footer"  cellspacing="0"  >
<tr>

<td class="top"> 
<font class="gold">Many ideas for a special and personalised gift.</font>
<p class="small">TheoryMine offers a range of different gift packages to suit evevryone! <br/>
You will be able to name a theorem, publish it in our journal, purchase a gift package, and even have your theorem printed on a t-shirt, mug, or mouse-mat!  

</p>
</td>


<!--<center><a class="img_link" href="products.html"><img src="images/gift_button.jpg" width="100px"></a> -->

<td width="1px" bgcolor="white">
</td>



<td class="top">
<font class="gold">The Latest Discovered Theorems: </font>


<p class="small" style="font:3px">
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
?>
</p>
</td>

<td width="1px" bgcolor="white">
</td>

<td class="top"><font class="gold">Pythagora, Kepler, Einstein... you could be the next!</font>
<p class="small">
TheoryMine offers personalised, newly discovered, mathematical theorems! <br/> By naming your very own mathematical theorem, newly generated by one of the world's most advanced computerised theorem provers, you can immortalise your loved ones, teachers, friends and even yourself and your favourite pets. 
</p>
</td>

</tr>

<tr>

<td class="center">
<center><a class="products_button" href="products.html" > gift packages</a>
</center>
</td>

<td width="1px" bgcolor="white">
</td>

<td class="center">
<center>


</ul><div class="more-theorems"><?
  if($res['more']){
  	  
    ?><center><a class="products_button" href="?go=theorems"> see more...</a>
   </center><?
  }
  
  ?> </div>


</center>
</td>

<td width="1px" bgcolor="white">
</td>

<td class="center">
<center><a class="buynow_button" href="buy.html">name a theorem</a>
</center>
</td>

</tr>

<tr>

<td class="bottom">
<center>
<a class="certificate_image" href="certificate.html"><img src="images/certificates.png" style="width:280;">
<span><img src="images/certificate3.png" style="width:280;"></span> </a>
</center>
</td>

<td width="1px" bgcolor="white">
</td>

<td class="bottom">
<center>

</center>
</td>

<td width="1px" bgcolor="white">
</td>

<td class="bottom">
<center>
<img src="images/mathematicians.png" style="width: 280;  height:140; " >

</center>
</td>

</tr>

</table>

</div>
</div>
</center>






<?
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




