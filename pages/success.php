<?
$here_link = "?go=overview";
$header_title = "Order Successful!";
include 'pages/common_parts/header.php';
?>

<!--`<h1>Congratulations!</h1>

<p>
Your order was successful.<p/>
<p> Our robot mathematicians are now working on your theorem. This usually takes up to 5 working days (excluding weekends). </p>
<p>
As soon as the theorem is ready, you will receive a confirmation email.<br/>
</p>--><?print $thislang['success'];?>

<!--
 <form action="process_reg.php" method="post">
          
 <div class="reg_box">
  <h4>What is your age range?</h4>
   <input type="checkbox" name="18" /> Under 18
   <input type="checkbox" name="1825" /> 18 - 25
  <input type="checkbox" name="2535" /> 25 - 35
  <input type="checkbox" name="3550" /> 35 - 50
  <input type="checkbox" name="5065" /> 50 - 65
  <input type="checkbox" name="65" /> Above 65
 </div>
 
  <div class="reg_box">
  <h4>Who did you buy the theorem for?</h4>
   <input type="checkbox" name="me" /> Yourself
   <input type="checkbox" name="friend" /> A friend
  <input type="checkbox" name="family" /> A member of family
  <input type="checkbox" name="collegue" /> A collegue
 </div>
 
           </select>
          <option>Administrative</option> 
          <option>Computing</option> 
          <option>Engineering</option> 
          <option>Finance</option> 
          <option>General Management</option> 
          <option>Legal</option> 
          <option>Logisitics</option> 
          <option>Manufacturing</option> 
          <option>Marketing</option> 
          <option>RD</option> 
          <option>Research</option>
          <option>Sales</option> 
          <option>Technical</option> 
          <option>Personnel</option> 
          <option>Other</option> 
          <option>Student</option> 
        </select>
 
<p align=left><input type="submit" name="send" class="submit_button clear" value="Sumbit!" /></p>
 
 </form>
          
-->

<?
/* 
// TODO: check what the user just purchased, set their $_SESSION email address and user id (log them in?), show them their transaction id? and explain how they can login. If they are logged in, we can just show them their order status.

$res = get_rowsandsummary("SELECT * FROM $db_paypal_payment_info WHERE custom='" . sql_str_escape($_SERVER['REMOTE_ADDR'] . $_COOKIE['tmp_id']). "'");

// below also checks time: that's overkill, and it breaks late page-reloads
//$res = get_rowsandsummary("SELECT * FROM $db_paypal_payment_info WHERE custom='" . sql_str_escape($_SERVER['REMOTE_ADDR'] . session_id()). "' AND TIMESTAMPDIFF(SECOND,time_stamp,NOW()) < 120");
if($res['rowcount'] != 1) {
  // FIXME: record/treat error correctly. Somehow: two people with the same session ID and IP both made orders in the last minute: should be imposssible/very unlikely.  
  ?><p><span class=warning>To see the state of your orders <a href="?go=login">login</a> to see your orders.</span>
  <? //print($_SERVER['REMOTE_ADDR'] . session_id()); 
  ?></p><?
} else {
  $row = $res['rows'][0];
  ?><p><span class="green">Your TheoryMine Invoice Number is:</span> <span class="good"><? print($row['last_name']) ?><? print($row['point_id']); ?></a> 
  </p><?
}?>
*/

// this is skipped 
/*
if(0 and (! $bad_entry) and $some_entry){ 
  // 
  ensure_user_has_account($email);

  if(isset($_SESSION['id'])) { $user_id = $_SESSION['id']; }
  else { $user_id = 1; } 
  $point = change_some_point($user_id, "point_type = 'thm.unnamed'", array('point_type' => 'thm.named', 'title' => $thmname),  'named.theorem', $point['id'] . " named by $email");
  if($point == null){
    $point = array();
    $point['title'] = $thmname;
    $point['point_type'] = 'order.promised';
    $point['body'] = genRandomString(10);
    $point['id'] = create_point($user_id, $point['point_type'], $point['title'] , $point['body'], 'created promise');
    ?>
    <h2>Discovery in progress!</h2>
    <p>
    Thanks for choosing to name a new discovery in mathematics. Our robot mathematicians are now working away for you. As soon as they disover a new theorem it will be named: 
    <p align="center">
    <b><i><? print(stripslashes($point['title'])); ?></i></b>
    <p>
    It typically takes less than 24 hours for them to discover a new theorem.   Once this has happened, we will add your theorem to the TheoryMine database of formally proved mathematics, and email you a PDF of the certificate which officially confirms the discovery.</p><p>
 Your reference code for the discovery is: <p align="center"><code><? print($point['body'] . $point['id']); ?></code></p><p>We have emailed you the reference code and this information. If you do not get this shortly, please email <a href="mailto:<? print $admin_email; ?>"><? print $admin_email; ?></a>  and quote your reference code. <p>
    <br><br>Love mathematics as much as we do? You could <a class="greenbutton" href="?go=discover">name more theorems</a>, read our <a class="greenbutton" href="/faq.html">frequently asked questions (FAQ)</a>, or learn more about mathematics on <a href="http://en.wikipedia.org/wiki/Mathematics">Wikipedia</a>. 
  <? 
  } else {
    ?>Congratulations you have named a new discovary in mathematics:
    <br>
    <b><? print(stripslashes($point['title'])); ?></b> (theorem id:  <? print($point['id']);?>):
    <b><? print(stripslashes($point['body'])); ?></b>
    was proved on <? print($point['time_stamp']); ?>, named by <? print("$email"); ?>
    <br>
    <?
  }
}
*/
?>
