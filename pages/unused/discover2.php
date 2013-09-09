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
1 <div class="main_outline"> 

  <!-- Top menu bar -->
2  <div class="menu_bar"> 
  <a href="?go=overview">Home Page</a>
  <a id="this_page" href="?go=discover">Name a Theorem*</a>
  <a  href="?go=products">Gift Packs</a>
  <a href="?go=faq">F.A.Q</a>
  <a href="?go=testimonials">Testimonials</a>
2/  </div> 

3  <div class="discover_body"> 

4  <div style="width: 100%; margin-bottom:10px;"> 
    
<font style="font: 40px Arial; font-weight: bold; color: #b4975a">  Discover and name a new theorem: &pound 15*</font>
    <br>
    
5    <center>
    <p>
    TheoryMine offers personalized, newly discovered, mathematics as a novelty gift.</p>
    <!-- <p> By naming your very own mathematical theorem, newly discovered by one of the world's most advanced computerised theorem provers (a kind of robot mathematician), you can immortalise your loved ones, teachers, friends and even yourself and your favourite pets. 
    </p> -->
    <font color=#CC0000 size="3px"> *&pound;15: Special offer for August 2010</font> 
    
    <img alt="" border="0" src="https://www.sandbox.paypal.com/en_GB/i/scr/pixel.gif" width="1" height="1">
5/    </center>
4/  </div>


6  <div class="style_image"  >
  <!--<img src="images/certificate_preview.png" align="left" > -->
   
   
  <p id='thm_name'><? print $_POST['tname'] ?> :</p>

    7  <div style=" font-size: 12px;">
   *This is not the certificate that you will receive but just an example. 
   7/  </div>
   6/  </div>


3/</div>
  
1/</div>


</center>
<!--
</div>
</body>
</html>
-->



