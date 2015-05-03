<?php
$header_title = "Generate Certificate";
restrict_is_admin();
$here_link = "?go=admin&s=certificate3";
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

<p>
To create certificates run the <code>run_certificate_generation.sh <? print $cert_id ?></code> from a clone of
the website repository.
</p>
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
