<?
restrict_is_admin();
$here_link = "?go=admin&s=email_users";
$all = array();

$unsucribed = array(
  "traveliplaza@yahoo.com", 
  "simon-paypal-us@lydiard.net",
  "jeffalstott@gmail.com", 
  "laura.herbst@gmx.de"
  );


$i = 0;
$result =get_users_where(null);
$message=email_of_phpfile('pages/email/promotion/brochure.php','');
//$date = " ca.time_stamp LIKE '2010-12-04%'";

//$date = "(ca.time_stamp LIKE '2010-12-04%' OR ca.time_stamp LIKE '2010-12-05%' OR ca.time_stamp LIKE '2010-12-06%' OR ca.time_stamp LIKE '2010-12-07%' OR ca.time_stamp LIKE '2010-12-08%' OR ca.time_stamp LIKE '2010-12-11%' OR ca.time_stamp LIKE '2010-12-12%' OR ca.time_stamp LIKE '2010-12-13%' OR ca.time_stamp LIKE '2010-12-14%' OR ca.time_stamp LIKE '2010-12-15%')";

//$date = "(ca.time_stamp LIKE '2010-12-04%' OR ca.time_stamp LIKE '2010-12-05%')";

//$result = get_from_points_and_actions_and_user($where = null, "AND p.point_type LIKE 'order.%'", $offset, $limit);
//$rows = $orders['rows'];

foreach($result['rows'] as $r) {
  //print_r($r['email'].", ");
  $email = $r['email'];
  //$user = get_user_of_point($r);
  print($i . ".    " . $email); 
  if($i>=850 && $i<900 && !(in_array($email,$unsucribed))){
    print ": YES! ";
    

    if($_POST['really'] == "yes") { 
   
    //$spl=split("@",$email);
    //$email_vals = array('email' => $spl[0]. "'s Theorem"); 
    //$message=email_of_phpfile('pages/email/promotion/launch.php',$email_vals);
    
    //send_email($email, 'TheoryMine: Download your Theorem Brochure now!', $message);
    print(" SENT ");
    }
  }
 print "<br>";
  $i++;         
}


?>
<br><br>

<?
/*
$db_type = "mysql";
$db_server = "localhost";
$db_user = "theorym_dbuser";
$db_pass = "1pass2word3";
$db = "theorym_theorymine";


$db_link = NULL;


$result = get_rowsandsummary("SELECT * from registrations",0,150);
foreach($result['rows'] as $r) {
  $all[$r['email']] = $r;
  $i++;

} */

//sort($all);

//print("SUM with dups: " . $i . "; sum without dups: " . count($all));

?>

<br><br>
<?
/*
foreach($all as $r){

 //$email = "ldixon@inf.ed.ac.uk";
  
  if($_POST['really'] == "yes") { 
    //$spl=split("@",$email);
    //$email_vals = array('email' => $spl[0]. "'s Theorem"); 
    //$message=email_of_phpfile('pages/email/promotion/launch.php',$email_vals);
    
    send_email($email, 'TheoryMine: order status', $message);
    print(" SENT ");
  }
   
}

*/
?>
<div>
<?
//$message=email_of_phpfile('pages/email/promotion/launch.php',$email_vals);

print($message);
?>
</div>

<FORM action="?go=admin&s=email_users" method="post">
  If you really want to email all users, type in "yes": <input name="really" type="text" value="no!" size="10"> 
  <input class="danger-button" name="submit" type="submit" value="Send emails!">
  </form>

  
