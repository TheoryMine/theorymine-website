
<?php



// TO DO: change pid to cert; note people have been sent the old URL!
$cert_id =sql_str_escape($_REQUEST['pid']);

$order_point = get_from_point_related_to_title($cert_id,'order.','has_certificate.');
if($order_point == null) {
  include("pages/bad_cert_id.php");
  exit();
} else {
  $name = $order_point['title'];
  $name = preg_replace('/[\s]+/', '_', $name);
  $name = preg_replace('/[^a-zA-Z0-9_]/', '', $name);
  $pdf_file_dir = 'certificates/' . $cert_id;
  $pdf_file_loc = $pdf_file_dir . '/certificate.pdf';
}

// if file doesn't exist, make dir
if(!file_exists($pdf_file_loc)
   or ((file_exists($pdf_file_loc) and $_SESSION['userkind'] == 'admin'
        and $_REQUEST['fresh'] == "yes" )))
{
  if(is_dir($pdf_file_dir) or mkdir($pdf_file_dir)) { // if made directory successfully, make & save pdf
    print "OPS - you are trying to look at a cerificate that doesn't exist!";
  } else {
    die_at_noted_problem("no dir and couldn't create the certificate dir");
  }
}

// File should have been created now, in which case we can send them the pdf...
if(file_exists($pdf_file_loc)) {
  if (ob_get_contents()) {
    die_at_noted_problem('Some data has already been output, can\'t send PDF file');
  }
  header('Content-Description: File Transfer');
  if (headers_sent()) {
    die_at_noted_problem('Some data has already been output to browser, can\'t send PDF file');
  }
  header('Cache-Control: public, must-revalidate, max-age=0'); // HTTP/1.1
  header('Pragma: public');
  header('Expires: Sat, 26 Jul 1997 05:00:00 GMT'); // Date in the past
  header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
  // force download dialog
  header('Content-Type: application/force-download');
  header('Content-Type: application/octet-stream', false);
  header('Content-Type: application/download', false);
  header('Content-Type: application/pdf', false);
  // use the Content-Disposition header to supply a recommended filename
  header('Content-Disposition: attachment; filename="'.basename($name).'";');
  header('Content-Transfer-Encoding: binary');

  $handle = fopen($pdf_file_loc, "r");
  if($handle == null) {
    die_at_noted_problem("Couldn't open certificate");
  }
  $contents = fread($handle, filesize($pdf_file_loc));
  fclose($handle);

  header('Content-Length: '.strlen($contents));
  echo $contents;
} else {
  die_at_noted_problem("No such pdf");
}
?>
