<?
 $header_title = "Report Badly Named Theorem";
 include 'pages/common_parts/header.php';
?>
 
 <script type="text/javascript">
 function report() {

	alert("This theorem name has just been reported as abusive. Thank you for your help. ");

	}
 
 </script> 
 
 <?

 $pid= $_REQUEST['pid'];
 $thm_point = get_point_in_type($pid, 'thm.named.' );
 $act = $_REQUEST['act'];
 $email = sql_str_escape($_POST['report_email']);
 $reson = sql_str_escape($_POST['report_reson']);

 
 if($act == 'submit') {
   
 //id reporter
  if($_SESSION['id']) {
   $user_id = $_SESSION['id'];
 }
 else {
   $user_id = 0;
 }
// send us an email with details
 $email_vals = array('thmname' => $thm_point['title'], 
                      'pid' => $pid,
                      'email' => $email,
                      'reson' => $reson,
                      'userid' => $user_id );
 $message = email_of_phpfile('pages/email/report_name_email.php', $email_vals);
 send_email($admin_email, 'TheoryMine : Report Abusive Name', $message);
 }
 
 //create a new point in the database

 
$abuse_point_id = create_point($user_id, 'abuse.', 
  "Theorem ". $pid . " :ABUSE REPORT", 
  "email reporter : " . $email . " REASON: " .$reson, 
  'reported abuse');

$abuse_rel = array('src_obj_id' => $abuse_point_id, 
                 'dst_obj_id' => $pid, 
                 'relation_type' => 'abuse.');

$abuse_rel_id = create_rel($user_id, $abuse_rel, $abody);
 

?>
 
 <h1>
 <?print  $thm_point['title']?>: <!--Abusive name report form.--><?print $thislang['repotname_title'];?> 
 </h1>
 
 
<form action="?go=report_name&pid=<? print ($pid) ;?>" method="post" style="margin:40px 10px 40px 10px;" onSubmit='report()'>
<input type="hidden" name="act" value="submit" >

<font style="font: 17px Arial; ">
<!--Why do you think this theorem name is abuvise?--><?print $thislang['repotname_q1'];?> <br/>

<textarea  class="info_box"  cols="60" rows="5"  type="text" name="report_reson"  maxlength="400" valing = "top"></textarea><br/>

<!--Your Email address:--><?print $thislang['repotname_q2'];?> <br/>
</font> 
<input class="info_box"  type="text" name="report_email" size="78" >
<br/><br/>

<input type="submit" value="<?print $thislang['repotname_button'];?>">
</form>


