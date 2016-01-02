<?php
$header_title = "Generate Certificate";
restrict_is_admin();
$here_link = "?go=admin&s=certificate3";
include 'pages/common_parts/header.php';

// TO DO: change pid to cert; note people have been sent the old URL!
$cert_id = sql_str_escape($_REQUEST['pid']);

function fileExists($cert_id, $n) {
  // print ' : certificates/' . $cert_id . '/' . $n . ' : ';
  // Location where uploaded files need to be placed.
  $filename = 'certificates/' . $cert_id . '/' . $n;
  if(file_exists($filename)) {
    print "<a href='" . $filename . "'>" . $n . "</a>";
  } else {
    print $n . " [ Not yet uploaded ]";
  }
}

$order_point = get_from_point_related_to_title($cert_id,'order.','has_certificate.');
if($order_point == null) {
  include("pages/bad_cert_id.php");
  exit();
} else {
?>

<p>
order point id: <? print $order_point['id']; ?><br>
Order to name a theorem: "<? print $order_point['title']; ?>"<br>
date: <?print $order_point['time_stamp']?><br>
Order status: <? print $order_point['point_type']; ?><br>
<br>
Files to upload should be created by:<br>
<code>run_certificate_generation.sh <? print $cert_id ?></code>
</p>

<p>
Needed files:
<ul>
  <li><? fileExists($cert_id, 'brouchure.pdf'); ?></li>
  <li><? fileExists($cert_id, 'certificate.pdf'); ?></li>
  <li><? fileExists($cert_id, 'certificate_image.jpg'); ?></li>
  <li><? fileExists($cert_id, 'thm.pdf'); ?></li>
  <li><? fileExists($cert_id, 'thm.jpg'); ?></li>
  <li><? fileExists($cert_id, 'thy.pdf'); ?></li>
  <li><? fileExists($cert_id, 'thy.jpg'); ?></li>
</ul>
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
