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
To create certificates run the <code>run_certificate_generation.sh <? print $cert_id ?></code> from a clone of
the website repository.
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
