<?php
$header_title = "Generate Certificate";
restrict_is_admin();
$here_link = "?go=admin&s=certificate2";
include 'pages/common_parts/header.php';


function curPageURL() {
 $pageURL = '';
 //$pageURL = 'http';
 //if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
 //$pageURL .= "://";
 if ($_SERVER["SERVER_PORT"] != "80") {
  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["PHP_SELF"];
 } else {
  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["PHP_SELF"];
 }
 return $pageURL;
}

// TO DO: change pid to cert; note people have been sent the old URL! 
$cert_id =sql_str_escape($_REQUEST['pid']);

$order_point = get_from_point_related_to_title($cert_id,'order.','has_certificate.');
if($order_point == null) {
  include("pages/bad_cert_id.php"); 
  exit();
} else {
?>

<script type="application/x-polyml">
val page_url = "<? print(curPageURL()); ?>";
val cert_id = "<? print($cert_id); ?>";
val codeloc_elem = valOf (DOM.getElementById DOM.document "codeloc");
val state_elem = valOf (DOM.getElementById DOM.document "state");
val doc_kind_elem = valOf (DOM.getElementById DOM.document "dockind");
val info = valOf (DOM.getElementById DOM.document "info");
val () = DOM.setInnerHTML info ("base URL for getting latex request: <code>" ^ page_url ^ "</code>" ^ "<br>\n" ^ "Certificate id: <code>" ^ cert_id ^ "</code>\n" ^ "<br>\n" ^ "Running Locally in directory: <code>" ^ (pwd()) ^ "</code>" ^ "</p>\n");
</script>

<script type="application/x-polyml" src="ML/make_certificate2.sml">

</script>

<script type="application/x-polyml">
val make_cert_button = 
    valOf (DOM.getElementById DOM.document "make_cert_button");
val l1 = DOM.addEventListener make_cert_button DOM.click 
          (DOM.EventCallback (fn(_)=> make_certificate page_url cert_id));
          
         
</script>

<div class="simple-block">
<p id="info">State not yet initialised; is PolyML running on this page?</p>
<p>Location of TheoyMine local scripts code <input type="text" id="codeloc" name="codeloc" value = "/Users/flaminiacavallo/Documents/VTheoremProving/websites/theorymine.co.uk/certificates" size="80"> <br>
e.g. <code>/Users/flaminiacavallo/Documents/VTheoremProving/websites/theorymine.co.uk/certificates</code> </p>
<p>Doc-kind <select name="dockind" id="dockind">
<option value="certificate">certificate</option>
<option value="theory">theory</option>
<option value="theorem">theorem</option>
<option value="certificate_image">certificate image</option>
<option value="brouchure">brouchure</option>
<option value="brouchure_c">brouchure chinese</option>
</select>

</p>
</div>
<br>
<div class="simple-block">
<p id="state">nothing done yet.</p>
</div>
<p>
<input id="make_cert_button" type="button" value="make certificate">
</p>
<p>

<form action="?go=admin&s=uploader&pid=<?print $cert_id?>" method="post"
enctype="multipart/form-data">
<label for="file">Filename:</label>
<input type="file" name="file" id="file">
<input type="submit" name="submit" value="Submit" />
</form>
</p>

<? 
}
?>
