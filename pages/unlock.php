<?php
// forced log-out.
//force_logout();

// $fromlink = set_default($_REQUEST['fromlink'], null);
// think: use: $_SERVER['HTTP_REFERER'] ?

$email = set_default(sql_str_escape($_REQUEST['email']),null);
$unlock = set_default(sql_str_escape($_REQUEST['unlock']),null);

// check/try login/unlocking of user account. 
if ((!isset($_SESSION['id'])) and $email and $unlock) {
  $user = try_unlock_user($email, $unlock);
  // Successful Login
  //print_r($user);
  if($user != NULL) {
    //$_SESSION['id'] = $user['id'];
    //$_SESSION['firstname'] = $user['firstname'];
    //$_SESSION['lastname'] = $user['lastname'];
    //$_SESSION['email'] = $user['email'];
    //$_SESSION['userkind'] = $user['userkind'];
    $unlocked_ok = true;
  } else {
    // unlock failed 
    $user = null; 
    $unlocked_ok = false;
    // IMPROVE: give user admin warning? 
    // question: might it be legit: multiple locks/unlocks?
  }
} else {
  $unlocked_ok = false;
  // IMPROVE: give user admin warning? 
}

if($unlocked_ok) {
  $header_title = "Account Unlocked";
  include 'pages/common_parts/header.php';
?>
  <center>
  <h2><!--Account Unlocked!--><?print $thislang['unlock_title'];?></h2>
  <center>
  <br>
  (<code><? print $email; ?></code>): <!--Your account has been unlocked and you can now--><?print $thislang['unlock_success'];?> <a href="?go=login&email=<? print urlencode($email); ?>"><!--login--><?print $thislang['login'];?></a>.-->
  </div>
  <? 
} elseif(isset($_SESSION['id'])){
  $header_title = "Account Unlock... ";
  include 'pages/common_parts/header.php';
  ?><p><!--You are already logged in. You need to <a href="?go=logout">logout</a> before you can unlock an account.--><?print $thislang['unlock_login'];?></p> <?
  // if unlocked ok: 
} else { 
  $header_title = "Account Unlock Failed";
  include 'pages/common_parts/header.php';
  //print "unlock = $unlock";
  ?>
  <!--<h2> Account Unlock Failed </h2>
  <p> Your account is either not locked, or has been locked again since you followed this link.</p> <p> If your account is unlocked, you can simply--><?print $thislang['unlock_failed1'];?> <a href="?go=login&email=<? print urlencode($email); ?>"><!--login--><?print $thislang['login'];?></a>. <!--Otherwise check your email. If you have forgotten your password you can make a--><?print $thislang['unlock_failed2'];?><a href="?go=change_pass&email=<? print urlencode($email); ?>"><!--request to change your password--><?print $thislang['unlock_failed3'];?></a>.
  </p>
  <?
}
?>
