<?
//require_once('tcpdf/config/lang/eng.php');
//require_once('tcpdf/tcpdf.php'); 

// Certificate ID 
$cert_id = sql_str_escape($_REQUEST['cid']);
/* dockind is 'certificate' when we generate the certifiate, 'theory' for a pdf of the theory, 'theorem' for PDF of the theorem only */
$doc_kind = sql_str_escape($_REQUEST['dockind']);

// check that we have the right certificate
$order_point = get_from_point_related_to_title($cert_id,'order.','has_certificate.');
if($order_point == null) {
  include("pages/bad_cert_id.php"); 
  exit();
} else {
  // avoid footer HTML appearing is our latex!
  $nofooter = 1;

  $thm_point = get_point_related_from($order_point,'thm.','named.');
  //get_point_in_type($pid, 'thm.named.' );
  $thy_point = get_point_related_from($thm_point,"thy.","inthy.");
  $proof = get_point_related_to($thm_point,"proof.","proof.");

  $myObj->date = sqltimestamp_to_str($thm_point['time_stamp']);
  $myObj->thm_title=  ( $thm_point['title']) ;
  $myObj->thm_body=  utf8_encode ($thm_point['body']);
  //$thm_body= utf8_encode ("try &alpha;");
  $myObj->proof_body = utf8_encode ("Proof outline: ". $proof['body']);
  $myObj->thy_title= utf8_encode ($thy_point['title']);
  $myObj->thy_body=  utf8_encode ($thy_point['body']);

  print(json_encode($myObj));
}
?>
