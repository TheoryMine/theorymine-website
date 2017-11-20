<?php  

//$email = trim(sql_str_escape($_POST['email']));
//$email2 = trim(sql_str_escape($_POST['email2']));
//$thmname = sql_str_escape($_POST['thmname']);
//$raw_thmname = $_POST['thmname'];

$title = "TheoryMine: Discover";
include 'pages/common_parts/header.php';
?>

<script src="inappropriate.js" type="text/javascript"></script> 

<center>

<!-- Main body -->
<div class="main_outline"> 

  <!-- Top menu bar -->
 <div class="menu_bar"> 
  <a href="?go=overview">Home Page</a>
  <a id="this_page" href="?go=discover">Name a Theorem*</a>
  <a  href="?go=products">Gift Packs</a>
  <a href="?go=faq">F.A.Q</a>
  <a href="?go=testimonials">Testimonials</a>
  </div> 

 <div class="discover_body"> 

 <div style="width: 100%; margin-bottom:10px;"> 
    
<font style="font: 40px Arial; font-weight: bold; color: #b4975a">  Discover and name a new theorem: &pound 13.50*</font>
    <br>
    
   <center>
    <p>
    TheoryMine offers personalized, newly discovered, mathematics as a novelty gift.</p>
    <!-- <p> By naming your very own mathematical theorem, newly discovered by one of the world's most advanced computerised theorem provers (a kind of robot mathematician), you can immortalise your loved ones, teachers, friends and even yourself and your favourite pets. 
    </p> -->
    <font color=#CC0000 size="3px"> *&pound;13.50: Special 10% discount for November 2010</font> 
    
    <img alt="" border="0" src="https://www.sandbox.paypal.com/en_GB/i/scr/pixel.gif" width="1" height="1">
   </center>
  </div>
  
  
<table > 
<tr> 
<td> 
 <div class="style_image"  >
 <div class="prop"></div>
  <!--<img src="images/certificate_preview.png" align="left" > -->
   
   
  <p id='thm_name'><? print $_POST['tname'] ?> :</p>
   
  <div style=" font-size: 12px;  position:relative;top:350px;">
   *This is not the certificate that you will receive but just an example. 
 </div>
 
 <div class="clear"></div>
  </div>

 
</td> 

  <?
  // TODO: add javascript to make sure they enter a non-white-space name for a theorem. 
  // TODO: improve $_SERVER['REMOTE_ADDR'] . $_COOKIE['tmp_id']
  //   make function to compute it and strip obvious IP info. 
  ?>
 
<td style=" text-align: left; font: 14px Arial;  margin-top: 10px;">

    
      
    <p>
      We charge &pound;13.50 (UK pounds) for the discovery and naming of a new  theorem&sup1;. <br/>
      For this price a new theorem will be discovered and named as you specified. <br/>
      You will receive a user name and password to log in to this website. You will be able to view your theorem and certificate at any time by login in our site.  <a href="?go=certificate_example">View Example Certificate </a> <br/><br/>
      It will take a max of 5 working days (excluding weekends) for our robot matehmaticians to discover your theorem.<br/> You will recieve a notification email when your theorem is ready!
      <br/>
    
    </p>
  


    
  <form name= "buy" action="https://www.paypal.com/cgi-bin/webscr"  method="post" onSubmit="return checkForm();">
    <input type="hidden" name="cmd" value="_s-xclick">
    <input type="hidden" name="hosted_button_id" value="88FTQSLXPT64N">

    <? if(!isset($_SESSION['id'])) { ?>
    <input type="hidden" name="custom" value="<? print($_SERVER['REMOTE_ADDR'] . $_COOKIE['tmp_id']); ?>">
    <? } ?>
      <!--<div class="center" style="min-height: 100px; border: 2px solid #999999; background-color: #f3efe5; margin: 55px 10px; border-radius: 15px;  -moz-border-radius: 5px; -webkit-border-radius: 5px;"> -->
     <div class="preview" style="margin: 45px 20px; padding: 10px">
      
      <!--<div style="float:center;  margin: 10px 20px; <text-align: center; padding: 0.5em; font-size: 13pt;">
        <input type="hidden" name="on0" value="Theorem name">
        Choose your Theorem Name
      
           <a class="questionmark" href="buy.html"><img src="images/question_icon.png" alt="?" width="15" height="15"> 
                 <span> 
                 This is the name that will be given to your theorem forever. <br/> 
                 It can be either a name, a nick name or any other thing as long as it meets the following criteria:<br/><br/> 
                 - &nbsp It must be a max. of 30 characters.<br/> 
                 - &nbsp It must NOT be inappropriate, libellous, defamatory, blashemous, obscene, offensive to public morality or an incitement to racial hatred or terrorism. <br/><br/>
                 Theorems name will be filtered and if repoted and recognised to be inappropriate will be removed from our detabase and records without refound. <br/><br/> 
               </span>
            </a><br/> -->
    <div> 
      <h2 style="text-align: center;"> 
      <input type="hidden" name="on0" value="Theorem name">
      Choose your Theorem Name <a class="questionmark" href=""><img src="images/question_icon.png" alt="?" width="15" height="15"> 
			 <span> 
			 This is the name that will be given to your theorem forever. <br/> 
			 It can be either a name, a nick name or any other thing as long as it meets the following criteria:<br/><br/> 
			 - &nbsp It must be a max. of 30 characters.<br/> 
			 - &nbsp It must NOT be inappropriate, libellous, defamatory, blasphemous, obscene, offensive to public morality or an incitement to racial hatred or terrorism. <br/><br/>
			 Theorems name will be filtered and if reported and recognised to be inappropriate will be removed from our database and records without refund. <br/><br/> 
	
			 </span></a><br/> 
    </h2> 
</div>     
        <!--<div style="margin-top:5px;">
          <div style="float:left;">
            <input type="text" name="os0" value = "<?print $_POST['tname'] ?> " maxlength="80" size="56" style="height:32px; border:1px solid #999999;">
          </div> 
   
          <div style="float: right;">
            <input type='button' value="Preview Your Theorem!" onclick='changeThmName()' style="height:32px; margin-left:-3px; border:1px solid #999999; border-left:none; background-color:#B4975A; font-size:1em;">
          </div>
        </div> <br/> -->
        
  <table > 
      <tr> 
        <td  > 
          <input class="field" type="text" name="os0" value = "<?print $_POST['tname'] ?> " size="50"> 
        </td> 
        <td> 
          <input class="button" type='button' value="Preview Your Theorem!" onclick='changeThmName()' > 
        </td> 
      </tr> 
   </table> 
    
  <div class="small">
      e.g. Tom's theorem, or The Bucklesham lemma
   </div><br>
  
    <input type="checkbox" name="tc"  > <font size="1.5px"> I have read and accept the <br/>
    <a href="?go=tc" target="_black">Terms and Conditions of the TheoryMine website</a><br>       
     
    
 

  <div class="buy-btn-box" style="margin: 8px 0 8px 0;">
      <input type="image" src="https://www.paypal.com/en_US/GB/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online.">
      <img alt="" border="0" src="https://www.paypal.com/en_GB/i/scr/pixel.gif" width="1" height="1">
     </div>
   </div>      
  </form>
   <p class="small"  >
  &sup1; Theorems are discovered by our robot mathematicians, we make every effort to ensure that the discovered theorems have never been published before, and we guarantee that every theorem we discover has not been previously recorded in our database of theorems. In the unlikely event that that there is a mathematics article publishing the theorem prior to our discovery, then we will give two additional new theorems to the owner of the old theorem. For more information on how we ensure that our discovered mathematics is new, <a href="?go=faq">see the FAQ</a>.
  </p>


 
</td>  



  <!-- &sup2; Your email address will not be given to a third party and we will not send you spam email. If you would like to receive more information, see which later theorems use your one, and see the status of the discoveries been carried out for you, then you can <a href="?go=register">register for free</a> with TheoryMine. -->



</tr> 
</table>
</div>
  


</div>

<!--
</div>
</body>
</html>
-->



