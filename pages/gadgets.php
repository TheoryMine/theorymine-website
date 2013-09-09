<?php  

    
$here_link = "?go=gadgets";
$header_title = "Gadgets";
include 'pages/common_parts/header.php';

$cert_id =sql_str_escape($_REQUEST['cid']);
$order_point = get_from_point_related_to_title($cert_id,'order.','has_certificate.');

$country_to_website = array(
  'Canada' => '.ca',
  'Usa' => '.com',
  'Other' => '.com',
  'Austria' => '.at',
  'Belgium' => '.be',
  'France' => '.fr',
  'Germany' => '.de',
  'Holland' => '.nl',
  'Portugal' => '.pt',
  'Spain' => '.es',
  'Sweden' => '.se',
  'Swiss' => '.ch',
  'Uk' => '.co.uk',
  'Uk2' => '.co.uk',
  'Australia' => '.com.au',
  'Australia2' => '.com.au',
  'Japan' => 'co.jp',
  'New Zealand' => '.co.nz',
  'Brazil' => '.com.br',
  
);

?>
<center>
<h1><!--Gift Items for: --><?print $thislang['gadgets_title'];?><font style="color:#CC0000;"><?print $order_point['title'];?></font></h1>
<br>
<div>
<a href="images/theorymine_t_shirt2.jpg"><img src="images/theorymine_t_shirt2.jpg" width="200"></a>
<a href="images/theorymine_tshirt1.jpg">
<img src="images/theorymine_tshirt1.jpg" width="200"></a>
<a href="images/theorymine_mug.jpg">
<img src="images/theorymine_mug.jpg" width="200"></a>
<a href="images/theorymine_mousepad.jpg"><img src="images/theorymine_mousepad.jpg" width="200"></a>
</div>
<div style="font-size:11pt">
<p>
<!--You can now purchase TheoryMine T-shirts, mouse-pads and mugs! <br>
All gift items are provided by <a href="http://www.zazzle.com/"  target="_blank">Zazzle</a> but are personalized with your TheoryMine theorems.-->
<?print $thislang['gadgets_p1'];?>
</p>
<p>
<!--You can then choose which country you want your items to be shipped from (by choosing the closest country to you, you might get cheaper shipping costs). It is then possible to visit the TheoryMine gift shop where you can purchase items personalized with your selected theorem!
Pick from lots of different styles, colors and sizes and costumize your items!--><?print $thislang['gadgets_p2'];?>
</p>
<p>
<!--For more informations on your  purchase please refer to the instructions <a href="http://www.zazzle.com"  target="_blank">Zazzle</a> and to their <a href="http://www.zazzle.co.uk/mk/policy/user_agreement" target="_blank">User Agreement</a> and <a href="http://www.zazzle.co.uk/mk/policy/privacy_policy"  target="_blank">privacy policy</a>.-->
<?print $thislang['gadgets_p3'];?>
</p>
</div>
<br/>
</center>
<form target='' method='post'>

<!--Please select which country you would like to get your items shipped from:--><?print $thislang['gadgets_wherefrom'];?><br/>

<select name="ship_id" id="shipping_country">
<optgroup label=<?print $thislang['gadgets_northamerica'];?>>
<option value="Canada"><?print $thislang['gadgets_canada'];?></option>
<option value="Usa"><?print $thislang['gadgets_usa'];?></option>
</optgroup>
<optgroup label=<?print $thislang['gadgets_europe'];?>>
<option value="Austria"><?print $thislang['gadgets_austria'];?></option>
<option value="Belgium"><?print $thislang['gadgets_belgium'];?></option>
<option value="France"><?print $thislang['gadget_france'];?></option>
<option value="Germany"><?print $thislang['gadgets_germany'];?></option>
<option value="Netherlands"><?print $thislang['gadgets_netherlands'];?></option>
<option value="Portugal"><?print $thislang['gadgets_portugal'];?></option>
<option value="Spain"><?print $thislang['gadgets_spain'];?></option>
<option value="Sweden"><?print $thislang['gadgets_sweden'];?></option>
<option value="Swiss"><?print $thislang['gadgets_swiz'];?></option>
<option value="Uk"><?print $thislang['gadgets_uk'];?></option>
</optgroup>
<optgroup label=<?print $thislang['gadgets_asia'];?>>
<option value="Australia"><?print $thislang['gadgets_australia'];?></option>
<option value="Japan"><?print $thislang['gadgets_japan'];?></option>
<option value="New Zealand"><?print $thislang['gadgets_newz'];?></option>
</optgroup>
<optgroup label=<?print $thislang['gadgets_latin'];?>>
<option value="Brazil"><?print $thislang['gadgets_brazil'];?></option>
</optgroup>
<option value="Other"><?print $thislang['gadgets_other'];?></option>

</select>
<input type="submit" value=<?print $thislang['select'];?> name="submit"><br />
</form>
<br/>


<?

if($order_point == null) {
  include("pages/bad_cert_id.php"); 
  exit();
} else {
  $gadets_page_url = $site_address."/certificates";
  //$counry_url = $country_to_website[$country]; 
  $counry_url = $country_to_website[($_REQUEST['ship_id'])];
  $thm_point = get_point_related_from($order_point,'thm.','named.');
  $thm_image_loc=$gadets_page_url."/".$cert_id."/thm.jpg";
  $thm_cgi_image_loc= urlencode($thm_image_loc);
  $thy_image_loc=$gadets_page_url."/".$cert_id."/thy.jpg";
  $thy_cgi_image_loc= urlencode($thy_image_loc);
  $thm_cgi_name= urlencode($thm_point['title']);
  $gift_url="http://www.zazzle".$counry_url.
      "/api/create/at-238416837775884316?rf=238416837775884316&ax=DesignBlast&cg=0&ed=true&continueUrl=http%3A%2F%2Fwww.theorymine.co.uk&rut=Go%20back%20to%20TheoryMine%20website&fwd=ProductPage&".
            "thm=".$thm_cgi_image_loc.
            "&thmname=".$thm_cgi_name.
            "&thy=".$thy_cgi_image_loc;
            

}

if(!isset($_POST["ship_id"])){
  }
  else{?>
  <div style="font-size: 12pt; font-style:italic">
   <!--You have selected:--><?print $thislang['gadgets_selection'];?>
   <font color = "#b4975a"><?print $_POST["ship_id"];?>.</font>
    <br/>
   <a href="<? print($gift_url); ?>" target="_blank" style="font-size:25pt"><!--Go to the shop!--><?print $thislang['gadgets_go'];?></a>
    </div>
    <?
  }
  
?>

