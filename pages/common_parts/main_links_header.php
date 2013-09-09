<script type="text/javascript">
if (document.images)
{
  pic0= new Image(768,515); 
  pic0.src="url(images/home_backgroundcrop.png)"; 
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
backImage[0] = "white url(images/buy_backgroundcrop.png) no-repeat top right";
backImage[1] = "white url(images/journal_backgroundcrop.png) no-repeat top right";
backImage[2] = "white url(images/products_backgroundcrop.png) no-repeat top right";
backImage[3] = "white url(images/faq_backgroundcrop.png) no-repeat top right";
backImage[4] = "white url(images/testimonials_backgroundcrop.png) no-repeat top right";


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
document.getElementById("main_buttons").style.background = 'white url(images/homebackgroundcrop3.png) no-repeat top right';
//document.getElementById("main_buttons").style.background = "url('/images/homebackground.png') no-repeat top right"; 
}
</script>


<div class="more-menu-links">
<?  // username stuff
if(isset($_SESSION['id'])) {
	?> 
  <!--Logged in as --><?print $thislang['links_loggedin'];?>
 <? print $_SESSION['firstname'] . " " . $_SESSION['lastname'] . " [" . $_SESSION['email'] . "] " ?> | 
  <a href="?go=profile"><!--Profile--><?print $thislang['links_profile'];?></a> | 
  <a href="<? print($logout_link); ?>"><!--Logout--><?print $thislang['links_logout'];?></a>
	<?
}	else {
  // otherwise present option to login
	?>  
	<a href="<? print($login_link); ?>"><!--Login--><?print $thislang['links_login'];?></a> 
  <?
}
?>
&nbsp; &nbsp; |   &nbsp; &nbsp;
	<a href="index.php?lang=en"><img class="logo" src="images/english_flag.gif" width="27px"></a> &nbsp; &nbsp;
  <a href="index.php?lang=cn"><img class="logo" src="images/chinese_flag.gif" width="27px"></a> &nbsp; &nbsp;
  <a href="index.php?lang=sp"><img class="logo" src="images/spanish_flag.gif" width="27px"></a>
</div>


<div class="menu_bar">
  <ul class="menu-toolbar">
    <li <? if($page == "overview") { ?>class="selected"<? } 
             else { ?>class="link"<? } ?>>
      <a href="?go=overview" onMouseOver="mouseover(0)" onMouseOut="mouseout()"><!--Home Page--><?print $thislang['links_home'];?></a></li>
    
      <li <? if($page == "discover") { ?>class="selected"<? } 
             else { ?>class="link"<? } ?>>
      <a href="?go=discover" onMouseOver="mouseover(1)" onMouseOut="mouseout()"><!--Name a Theorem--><?print $thislang['links_disc'];?></a></li>
    
    <li <? if($page == "faq") { ?>class="selected"<? } 
             else { ?>class="link"<? }  ?>>
      <a href="?go=faq" onMouseOver="mouseover(3)" onMouseOut="mouseout()"><!--F.A.Q.--><?print $thislang['links_faq'];?></a></li>
      
      
    <li <? if($page == "about") { ?>class="selected"<? } 
             else { ?>class="link"<? }  ?>>
      <a href="?go=about" onMouseOver="mouseover(2)" onMouseOut="mouseout()"><!--About Us--><?print $thislang['links_about'];?></a></li>
      
      
    <li <? if($page == "products") { ?>class="selected"<? } 
             else { ?>class="link"<? }  ?>>
      <a href="?go=products" onMouseOver="mouseover(2)" onMouseOut="mouseout()"><!--Gift Packs--><?print $thislang['links_gift'];?></a></li>
    
    <li <? if($page == "testimonials") { ?>class="selected"<? } 
             else { ?>class="link"<? }  ?>>
      <a href="?go=testimonials" onMouseOver="mouseover(4)" onMouseOut="mouseout()"><!--Testimonials--><?print $thislang['links_test'];?></a></li>
  </ul>
</div>	

