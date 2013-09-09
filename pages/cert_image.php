<?php  

$here_link = "?go=cert_image";
$header_title = "My theorem Certificates";
//include 'pages/common_parts/header.php';

$nofooter = 1;
$pid =sql_str_escape($_REQUEST['pid']);
$thm_point = get_point_in_type($pid, 'order.hasthm.');
if($thm_point  == null) {
  include("pages/bad_cert_id.php"); 
  exit();
} else {

  //$thm = get_point_related_from($pid,  'thm.named.', 'named.' ); 
  
  
  $cert =  get_point_related_from($thm_point,'certificate.','has_certificate.'); 
  $certid = $cert['title'];
  //$certid = "8052c0549e5ae90e56b8816d3e39ff6943";
  $cert_im_url = "http://www.theorymine.co.uk/certificates/".$certid."/certificate_image.jpg";

}
?>
<head>
<title>TheoryMine | My Theorem's Certificate</title>
</head>

<img src="<?print $cert_im_url?>" width=500px>
<p>
TheoryMine gives you the chance to name a newly discovered and unique mathematical theorem after your loved ones, teachers, pets and even yourself. 
So if you know someone who dreams of becoming the next Pythagoras but balks at the thought of thousands of hours of study, a personalised theorem could be the perfect gift!
</p>
