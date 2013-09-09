<?php  
$lastname = sql_str_escape($_POST['lastname']);
$firstname = sql_str_escape($_POST['firstname']);
$password = sql_str_escape($_POST['password']);
$password2 = sql_str_escape($_POST['password2']);
$email = sql_str_escape($_POST['email']);

// has any data been entered
if ((! empty($lastname)) OR (! empty($firstname)) OR
    (! empty($password)) OR (! empty($password2)) OR (! empty($email)))
{ $some_entry = true; } else { $some_entry = false; }

// is any needed data missing
if(empty($password) or empty($password2) or empty($email) or empty($lastname) or empty($firstname))
{ 
  $missing_entry = true; 
  $password = null; $password2 = null; 
  $password_mismatch = false;
}
else 
{ 
  if($password == $password2){ 
    $password_mismatch = false;
  }
  else { 
    $password_mismatch = true; 
    $password = null; $password2 = null; 
  } 
  $missing_entry = false;
}
$bad_entry = $missing_entry || $password_mismatch;

$email = sql_str_escape($_REQUEST['email']);

// FIXME the POST_submit button is not recognized as being pressed
// once the 'register' button is pressed check the fields
//if(isset($_POST['submit'])) {
// not all fields are filled in
if (! $bad_entry) {
  $email_clash = false;
  // Check if email is still available
  if(isset($email) and $email != null) {
    $row = try_get_user_by_email($email);
    if($row != null /*and $row['last_act_kind'] != 'paypal_creation'*/) {
      $email_clash = true;
    }
  }
}
//else { 
//  print "missing entry!<br>";
//}
  
// if user can be successfull registered
include 'pages/common_parts/header.php';
if(! $bad_entry){
  $email_vals = array('email' => $email, 'site_name' => $site_name, 'lastname' => $lastname, 'firstname' => $firstname);
  if($email_clash) {
     $emailfile= "pages/email/auth/registration_email_clash." . ($thislang['lang']) . ".php";
    $message = email_of_phpfile($emailfile, $email_vals);
    send_email($email, $site_name . ': Registration', $message);
  } else { 
    $user_key = make_new_user($lastname, $firstname, $email, $password);
    //added $email_vals below 
    $email_vals = array('email' => $email, 'site_name' => $site_name, 'lastname' => $lastname, 'firstname' => $firstname, 'userkey' => $user_key );
    $emailfile= "pages/email/auth/registration_email." . ($thislang['lang']) . ".php";
    $message = email_of_phpfile($emailfile, $email_vals);
    send_email($email, $site_name . ': Registration', $message);
  }
    
  $header_title = "Registration Submitted";
  ?> <p><!--Thank you, registration is nearly complete. An email has been sent to: --> <?print $thislang['register_nearly'];?><code><? print($email); ?></code> <p> <!--The next thing you need to do is <span class="good">check your email</span>. The email contains a link to unlock your account and complete the registration process.--><?print $thislang['register_checkemail'];?></p> 

  <!-- <p>Once you have done that, you can <a href="?go=login&email=<? print urlencode($email); ?>">login</a>.</p> -->
  <?
} else {
  // Need to fill out details to register
  $header_title = "Register";
  include 'pages/common_parts/header.php';
  ?>
  <h2><!--Register with TheoryMine--><?print $thislang['register_title'];?></h2>
  <p>
   <!--Register with TheoryMine to get news about our products (e.g. forthcoming T-shirts and mouse-mats with your theorem, as well as an online journal!) and to see how your theorem(s) are related to other people's. --><?print $thislang['register_p1'];?>
  </p>
  <?  
  if($some_entry and $missing_entry) {
    ?> <p><span class="warning"><!--Please fill the fields marked with * and re-enter your password.--><?print $thislang['register_missing_entry'];?></span></p><?
  } else if($password_mismatch) {
    ?> <p><span class="warning"><!--Your passwords did not match, please re-enter it in the fields marked *.--><?print $thislang['register_mismatch'];?></span></p><?
  } 
  ?>
  <p>
  <form action="?go=register" method="post">
  <? print_required_field($firstname, /*"First Name"*/ $thislang['firstname']); ?>: 
  <input type="text" name="firstname" size="60" value="<? print htmlentities($firstname, ENT_QUOTES, 'UTF-8'); ?>"><br>
  <br>
  <? print_required_field($lastname, /*"Last Name"*/$thislang['lastname']); ?>: 
  <input type="text" name="lastname" size="60" value="<? print htmlentities($lastname, ENT_QUOTES, 'UTF-8'); ?>"><br>
  <br>
  <? print_required_field($email, /*"Email address"*/$thislang['email']);?>: 
  <input type="text" name="email" size="60" value="<? print(htmlentities($email, ENT_QUOTES, 'UTF-8')); ?>">
  <br>
  <br>
  <? print_required_field($password, /*"Password"*/$thislang['password']);?>: 
  <input type="password" name="password" size="40"><br>
  <? print_required_field($password2, /*"Retyped password:"*/ $thislang['register_retype1']);?>: 
  <nobr><input type="password" name="password2" size="40"> (<!--please retype your password here--><?print $thislang['register_retype2'];?>)</nobr>
  <br><br>

  <input class="greenbutton" type="submit" value="<?print $thislang[ 'register']?>">
  </form>
  </p>
  <?
}
?>

<p class="small">
<!--Information on how we use the personal data that you submit on our site is contained in our <a href="?go=privacy">privacy and cookie policy</a>.--><?print $thislang['register_privacy'];?>
</p>


