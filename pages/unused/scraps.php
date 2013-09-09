
//$giftlock = sql_str_escape($_POST['l']);
//$giftid = sql_str_escape($_POST['pid']);

// has any data been entered
//if ((! empty($thmname)) OR (! empty($email2)) OR (! empty($email)))
//{ $some_entry = true; } else { $some_entry = false; }

// logged in, so ignore any email values. 
//if(isset($_SESSION['id'])) {
//  $email = $_SESSION['email'];
//  $email2 = $_SESSION['email'];
//}

// is any needed data missing
if(empty($email2) or empty($thmname) or empty($email)) { 
  $missing_entry = true; 
} else { 
  $missing_entry = false;

  if($email == $email2){ 
    $email_mismatch = false;
    if(eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})\$", $email)) {
      $bad_email = false;
    } else{
      $bad_email = true;
      $email2 = "";
    }
  } else { 
    $email_mismatch = true; 
  } 
}
$bad_entry = $missing_entry || $email_mismatch || $bad_email;

<!--   <p>To discover and name a new theorem we need the following information: 
  </p>

  <?  
  if($some_entry and $missing_entry) {
    ?> <p><span class="warning">Please enter all the fields marked with *</span></p><?
  } else if($email_mismatch) {
    ?> <p><span class="warning">Your email address entries were not the same, please check them and make sure they both correctly contain your email address.</span></p><?
  } else if($bad_email){
    ?><p><span class="warning">The email address you entered is not a valid email address.</span> We need your email address in order to send you the  naming cirtificate for the discovered theorem. </p><?
  }
  ?>
  <p>
-->
<!-- 
  <form action="?go=discover" method="post">
  <p align="center">
  <table>
  <tr><td align="right" valign="top">
  <? print_required_field($thmname, "a name for the theorem");?>:</td><td valign="top">
  <input type="text" name="thmname" size="60" value="<? print($raw_thmname); ?>">
  <br> e.g. Tom's theorem, or The Bucklesham lemma</td></tr>
<? 
  if(!isset($_SESSION['id'])) {
?>
  <tr><td align="right">
  <? print_required_field($email, "your email address");?>:&sup2; </td><td>
  <input type="text" name="email" size="60" value="<? print($email); ?>">
  </td></tr>
  <tr><td align="right">
  <? print_required_field($email2, "please retype your email address");?>: </td><td>
  <input type="text" name="email2" size="60" value="<? print($email2); ?>">
  </td></tr>
<? 
  }
?>
  </table>
  <input class="greenbutton" type="submit" value="Discover and name it!"><br>(you will be asked to pay by paypal)
  </p>
  </form>
-->

<!-- 
<form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="FS95R5C5T67QE">
<table>
<tr><td><input type="hidden" name="on0" value="Theorem name">The new theorem is to be named:</td></tr><tr><td><input type="text" name="os0" maxlength="80" size="80"></td></tr>
</table>
<input type="image" src="https://www.sandbox.paypal.com/en_US/GB/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online.">
<img alt="" border="0" src="https://www.sandbox.paypal.com/en_GB/i/scr/pixel.gif" width="1" height="1">
</form>
-->


<h3>Submissions:</h3>
<?

$type = set_default(sql_str_escape($_REQUEST['type']), null);
$body = set_default(sql_str_escape($_REQUEST['body']), null);
$act = set_default(sql_str_escape($_REQUEST['act']), null);
if($act=='make_action'){
  create_action(0, 0, 0, $type, $body, $_SESSION['id']);
}

?>
<p>
<? 
$res = get_active_submissions($_SESSION['id']);
if($res['rowcount'] == 0) {
  ?>You have no sumbissions under review<?
} else {
  ?><table><?
  foreach($res['rows'] as $row) {
    ?><tr><td>
    (<a href="?go=overview&act=review&id=<? print $row['id']; ?>">read/review</a>) <? print $row['title']; ?></a>
    </td></tr><?
  }
  ?></table><?
}
?>
</p>

<p>
<? 
$res = get_old_submissions($_SESSION['id']);
if($res['rowcount'] == 0) {
  ?>You have no previously reviewed submissions.<?
} else {
  ?><table><?
  foreach($res['rows'] as $row) {
    ?><tr><td>
    (<a href="?go=overview&act=read&id=<? print $row['id']; ?>">read</a>) <? print $row['title']; ?></a>
    </td></tr><?
  }
  ?></table><?
}
?>
</p>



<!-- 
<h3>Actions:</h3>
<?
$res = get_user_actions($_SESSION['id']);
// print_r($res);
if($res['rowcount'] > 0){
  ?><table border="1">
  <tr><th>action_id</th><th>timestamp</th><th>id</th><th>hid1</th><th>hid2</th>
  <th>type</th><th>body</th></tr>
  <?
  foreach($res['rows'] as $row){
    ?><tr>
    <td><? print $row['id']; ?></td>
    <td><? print $row['time_stamp']; ?></td>
    <td><? print $row['obj_id']; ?></td>
    <td><? print $row['history_id']; ?></td>
    <td><? print $row['action_type']; ?></td>
    <td><? print $row['body']; ?></td>
    </tr><?
  } ?>
  </table>
  <?
}
?>

<h3>Make an action:</h3>

    <form action="?go=profile" method="post">
    <? print_required_field($type, "type");?>:
    <input type="hidden" name="act" size="20" value="make_action">
    <input type="text" name="type" size="20" value="<? print($type); ?>">
    <br><br>
    <? print_required_field($body, "body");?>: 
    <? place_inputbox('body',$body); ?> 
    <br><br>
    <input type="submit" value="Act!">
    </form>
-->
<?
  /*
  $lastname2 = sql_str_escape(set_default($_POST['lastname'],null));
  $firstname2 = sql_str_escape(set_default($_POST['firstname'],null));
  $password2 = sql_str_escape(set_default($_POST['password'],null));
  $email2 = sql_str_escape(set_default($_POST['email'],null));
  
  // has any data been entered
  if ((! empty($lastname2)) OR (! empty($firstname2)) OR
    (! empty($password2)) OR (! empty($email2)))
  { $some_entry = true; } else { $some_entry = false; }
  
  // is any needed data missing
  if(empty($password2) or empty($email2) or empty($lastname2) 
    or empty($firstname2))
  { $missing_entry = true; $password = null; }
  else 
  { $missing_entry = false; }
  
  // FIXME the POST_submit button is not recognized as being pressed
  // once the 'register' button is pressed check the fields
  //if(isset($_POST['submit'])) {
  // not all fields are filled in
  if (! $missing_entry) {
    $email_clash = false;
    // Check if email is still available
    if(isset($email2) and $email2 != null) {
      $row = try_get_email($email2);
      if($row != null) { $email_clash = true; }
    }
  }
  //else { 
  //  print "missing entry!<br>";
  //}
  
  $email_edit_key = mt_rand();
  
  // if user can be successfull registered
  if( (! $missing_entry) and (! $email_clash) ) {
    update_user($_SESSION['id'], $lastname2, $firstname2, $username2,
      $email2, $password2);
    $title = "Registered as: $email";
    include 'pages/common_parts/header.php';
    ?> <p><span class="good">You have successfully registered</span> your email address has been recorded as: <? print($email); ?></p>
    You can now <a href="?go=login&email=<? print $email; ?>">login</a>.
    <?
  } else {
    $title = "Register";
    include 'pages/common_parts/header.php';
    // Need to fill out details to register
    ?>
    <center>
    <h2> Register </h2>
    <?  
    if($some_entry and $missing_entry) {
      ?> <p><span class="warning">Please fill the fields marked with * and re-enter your password.</span></p><p> <?
    }
    if($email_clash){
      ?><p><span class="warning">E-Mail '<? print($email); ?>' is already registered.</span> Please check your email for registration details or register with a different email address.</p>
      <?
    }
    ?>
    <p>
    <form action="?go=register" method="post">
    <? print_required_field($email, "Email address");?>: 
    <input type="text" name="email" size="40" value="<? print($email); ?>">
    <? if($email_clash){ ?>
      <br>This email address is already registered: just <a href="<? print $login_link; ?>">login</a>; or request a new password. <? 
    } ?>
    <br>
    <br>
    <? print_required_field($password, "Password");?>: 
    <input type="password" name="password" size="16"><br>
    <br>
    <? print_required_field($firstname, "First Name"); ?>: 
    <input type="text" name="firstname" size="40" value="<? print $firstname; ?>"><br>
    <br>
    <? print_required_field($lastname, "Last Name"); ?>: 
    <input type="text" name="lastname" size="40" value="<? print $lastname; ?>"><br>
    <br>
    <input type="submit" value="Register">
    </form>
    </p>
    </center>
    <?
  }
  */
  
?>
