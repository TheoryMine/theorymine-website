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



<h1><!--Discover and name a new theorem: try--><?print $thislang['discover_title'];?> <font style="color:#CC0000;"><!--Only
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
       (<a href="?go=certificate_example"><?print $thislang['discover_see_ex'];?></a>).
        <br>
       <!--To buy our gift items you first need topurchase a theorem. Once you receive this you will be able to follow the &quot;Gift Items Shop&quot;  link next to your theorem name on your profile page-->
       <?print $thislang['discover_part2'];?>
   </p>
   <!-- <p>
      <font style="color:#CC9900; font-size:10pt;">
        <- N.B.: If you would like your theorem to be sent to a specific email address, please make sure you are registered and logged in with this email address. Otherwise TheoryMine will use your PayPal email address. ->
      <?print $thislang['discover_part3'];?>
      </font>
      </p>
    <p> -->

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
      <!--You can place an order by <a href="https://www.paypal.me/theorymine/15">sending us a payment of 15 UK pounds using Paypal</a>. Please indicate you theorem name in "special instructions to seller" section, and we will send you a newly discovered theorem with your name within 4 business days.--><?print $thislang['discover_part4'];?>
      <!-- <font style="color:#CC0000; font-size:18pt;">
      Sorry, but TheoryMine is not currently taking any orders. We are working on updating our payment processing code to work with updates to PayPal. Please come back later, or send us an email (to info@theorymine.co.uk) and we will let you know when we are taking orders again.
      </font> -->
      </td>
    </tr>
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



