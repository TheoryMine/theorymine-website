<?php  

//$email = trim(sql_str_escape($_POST['email']));
//$email2 = trim(sql_str_escape($_POST['email2']));
//$thmname = sql_str_escape($_POST['thmname']);
//$raw_thmname = $_POST['thmname'];




$header_title = "Name a Theorem";
include 'pages/common_parts/header.php';
include('pages/language.php');
?>

<script src="inappropriate.js" type="text/javascript"></script> 
<script type="text/javascript">

function changePreview(){
  var selObj = document.getElementById('certificate_kind');
  if (selObj.options[1].selected){
    document.getElementById("style_image").style.background =
    "white url(images/certificate_preview_valentine.png) no-repeat top center";
  }
  else{
     document.getElementById("style_image").style.background =
    "white url(images/certificate_preview2.png) no-repeat top center";
  }
}
  
</script>
<div class="overview_body"> 

<? 
$dicountcode= 'theorymCC';
if(isset($_POST['check_voucher'])){ ?>
   <?$input_voucher = $_POST['voucher'];?>
<?}?>
<? $price = $thislang['discover_15'];
  $paypalbuttoncode = "X675URX8SLXB4";
?>

<? if(isset($input_voucher)) {
  if ($input_voucher == $dicountcode){
      	    $price = $thislang['discover_5'];
            $paypalbuttoncode = "JX974E8S8H9HG"; }
   }?>
   


<h1><!--Discover and name a new theorem:--><?print $thislang['discover_title'];?> try <font style="color:#CC0000;"><!--Only
&pound;15.00--><?print $price;?></font></h1>

<table class="discover_body_table"> 
<tr> 
  <td> 
   <div class="style_image" id ="style_image">
       <p id='thm_name'><? print $_REQUEST['tname'];?>:</p>
       <div style="font-size: 9pt;  position:relative;top:350px;">
         <!--*This is just an illustration, it is not the certificate that you will receive.--><?print $thislang['discover_imagetag'];?>
       </div>
     <div class="clear"></div>
   </div>
  </td> 

  <?
  // TODO: add javascript to make sure they enter a non-white-space name for a theorem. 
  // TODO: improve $_SERVER['REMOTE_ADDR'] . $_COOKIE['tmp_id']
  //   make function to compute it and strip obvious IP info. 
  ?>
 
  <td>
  <p>
  <br>
        <!--By placing an order with TheoryMine, you name a newly discovered mathematical theorem&sup1;. This lets you immortalise your loved ones, teachers, friends and even yourself and your favourite pets!
        <br>
        <br> 
        It may take upto 2 working days (excluding weekends) for our robot mathematicians to discover your theorem. Once discovered we will send you a notification by email that your theorem is ready! You will then be able to download a certificate of the discovery by logging in the website.-->
       <?print $thislang['discover_part1'];?>
       (<a href="?go=certificate_example"><!--See an example certificate-->
         <?print $thislang['discover_see_ex'];?></a>).
        <br>
       <!--To buy our gift items you first need topurchase a theorem. Once you receive this you will be able to follow the &quot;Gift Items Shop&quot;  link next to your theorem name on your profile page-->
       <?print $thislang['discover_part2'];?>
   </p>
   <p>
      <font style="color:#CC0000;; size:10pt">
      <!--N.B.: If you would like your theorem to be sent to a specific email address, please make sure you are registered and logged in with this email address. Otherwise TheoryMine will use your PayPal email address.-->
      <?print $thislang['discover_part3'];?>
      
      </font>
      </p>
    <p>
  


    <?
    $lang1 = $thislang['lang'];
      switch($lang1){
      case "en":
      $paypallang = "uk";
      break;
      case "cn":
      $paypallang = "cn";
      break;
      case "sp":
      $paypallang = "es";
      break;}    
    ?>



    <table class="outer-preview">
    <tr>
      <td>
        <form name= "buy" action="https://www.paypal.com/cgi-bin/webscr"  method="post" onSubmit="return checkForm();">
        <!-- ADDED THIS BELOW -->
        <?// $paypalbuttoncode = "X675URX8SLXB4";?>
        <?//$paypalbuttoncode5 = "JX974E8S8H9HG"; ?>
        <!-- Untill here -->
        
          <input type="hidden" name="on0" value="Theorem name">
          <input type="hidden" name="cmd" value="_s-xclick">
          <input type="hidden" name="lc" value=<? print $paypallang; ?> >
          <!-- WAS THIS -->
          <!--<input type="hidden" name="hosted_button_id" value="X675URX8SLXB4"> --> <!--15 pounds-->
          <!-- UNTILL HERE -->
          
           <!-- ADDED THIS BELOW -->
          <input type="hidden" name="hosted_button_id" value=<? print $paypalbuttoncode; ?>>
          <!-- Untill here -->
        
          <!--<input type="hidden" name="hosted_button_id" value="88FTQSLXPT64N">-->
          <!--0.01 pounds>-->
  
          <? if(!isset($_SESSION['id'])) { ?>
          <input type="hidden" name="custom" value="<? print($_SERVER['REMOTE_ADDR'] . $_COOKIE['tmp_id']); ?>">
          <? } ?>
      
          <div class="preview">
                <font style="font-weight: bold; "> <!--Choose your Theorem Name:--><?print $thislang['discover_choose'];?> </font><? include("pages/common_parts/question-mark.php"); ?>
                <br /> 
                <input class="field" type="text" name="os0" value = "<?print $_REQUEST['tname'] ?>" size="50" oninput='changeThmName()' />
                
                 <div class="small">
                     <!--e.g. Tom's theorem, or The Bucklesham lemma--><?print $thislang['discover_eg'];?><br/>
                     N.B.: You need to include the word "Theorem" or "Lemma" in the name if you want it to be there!
                     <br/> <br/> 
                     <!--Choose the language for your brochure:--><?print $thislang['discover_choose2'];?>
                     <input type="hidden" name="on1" value="Language">
                     <select name="os1" id="certificate_kind";>
                     <option value="en">English</option>
                     <option value="cn">Chinese</option>
                     <!--<option value="sp">Spanish</option>-->
                    </select>
                    <br/>
                    <br/>
                    <div style="width:60%; margin:0 20% 0 20%;">
                   <?//if ($thislang ['lang'] == 'cn'){
                     ?>
                     <form action="" method="post">
                         <?print $thislang['discover_voucher'];?>
                         <input class="field" type="text" name="voucher" maxlength="10" size="5" width="10%"> 
                         <input type="submit" value="<?print $thislang['discover_confirm'];?>" name = "check_voucher"> <br/>
                         <p style="font-size: 10pt; color:#CC0000;">
                         <? if(isset($input_voucher)) {
                           if ($input_voucher == $dicountcode){
                                        
                                       print $thislang['discover_voucher_valid'];
                           }
                                      
                           else{
                                         print $thislang['discover_voucher_invalid'];
                           }
                         }?> <br/>
                         </p>
                     </form>
                   <? //}
                   ?> 
                   </div>
                   
                    
                     
                      <input type="checkbox" name="tc"><!--I have read and accept the--><?print $thislang['discover_tc1'];?>
                      <a href="?go=tc" target="_black"><!--Terms and Conditions of the TheoryMine website--><?print $thislang['discover_tc2'];?></a>
                  </div> 
   
                 <input type="image" src="https://www.paypalobjects.com/en_US/GB/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal Ñ The safer, easier way to pay online."><img alt="" border="0" src="https://www.paypalobjects.com/en_GB/i/scr/pixel.gif" width="1" height="1">
    
                <p style="font-size: 10pt; color:#CC0000;">(If you don't have a PayPal account, you can pay with your credit or debit card as a PayPal guess.)</p>
                <!--<input type="image" src="https://www.paypal.com/en_US/GB/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online.">
                <img alt="" border="0" src="https://www.paypal.com/en_GB/i/scr/pixel.gif" width="1" height="1">-->
                
          
              
           </div>


 
         </div> 
         </form> 
         </td></tr>
         <tr><td>
            <p>
            <!--Once your order has been placed, you will receive a user name and password to--><?print $thislang['discover_sub1'];?> <a href="?go=login"><!--login--><?print $thislang['login'];?></a> <!--to this website. You can login at any time to view the status of your order, view the theorems you have named,  as well as download the certificate for these theorems.--><?print $thislang['discover_sub2'];?>
            </p>
      
            <p class="small">
            &sup1; <!--Theorems are discovered by our robot mathematicians, we make every effort to ensure that the discovered theorems have never been published before, and we guarantee that every theorem we discover has not been previously recorded in our database of theorems. In the unlikely event that that there is a mathematics article publishing the theorem prior to our discovery, then we will give two additional new theorems to the owner of the old theorem. For more information on how we ensure that our discovered mathematics is new, see the--><?print $thislang['discover_sub3'];?> <a href="?go=faq"><!--FAQ--><?print $thislang['faq'];?> </a>.
            </p>
         </td> </tr>  



         <!-- &sup2; Your email address will not be given to a third party and we will not send you spam email. If you would like to receive more information, see which later theorems use your one, and see the status of the discoveries been carried out for you, then you can <a href="?go=register">register for free</a> with TheoryMine. -->
    </table>
  
    </td>
  </tr>  
</table>


</div>
</div>

<!--
</div>
</body>
</html>
-->



