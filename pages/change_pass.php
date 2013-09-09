<?
$header_title = "Requesting Password Change";
include 'pages/common_parts/header.php';

// FIXME: respect fromlink
$fromlink = set_default($_REQUEST['fromlink'], null);
$email = set_default(sql_str_escape($_REQUEST['email']),null);
$password = set_default(sql_str_escape($_POST['password']),null);
$unlock = set_default(sql_str_escape($_REQUEST['unlock']),null);
// dont leak failure info when unlocked
if($unlock == 'none') {$unlock = '';}
$act = set_default(sql_str_escape($_REQUEST['act']),null);

$user = get_user_from_email_and_lock($email,$unlock);
$gone_wrong = (($user==null and $act != null and $act != 'send_pass_reset_key')
  or ($user!=null and $user['last_act_kind'] != 'change_pass_req'));
if($gone_wrong){
  ?><p><span class="warning"><!--You have used an invalid code or email address.</span> Perhaps this was a code from an old attempt to change your password. If you need to change your password, please enter your email address below and click on the "Request password change" button.--><?print $thislang['changepass_invalid'];?> </p><? 
}

if($act == 'send_pass_reset_key'){
  $user = try_get_user_by_email($email);
  if($user != null) {
    $user_key = pass_reset_req_user($user['id']);
    // print("k: $user_key");
    $firstname = $user['firstname'];
    $lastname = $user['lastname'];
    //$message = 
    $emailfile = 'pages/email/auth/change_pass_email.'.$thislang['lang'].'.php';
    $message = email_of_phpfile($emailfile,
      array(
        'site_name' => $site_name,
        'user_key' => $user_key,
        'email' => $email,
        'firstname' => $user['firstname'], 
        'lastname' => $user['lastname']));
    send_email($email, $site_name . ': Password Reset', $message);
  }
  ?><p><!--An email has been sent to--><?print $thislang['changepass_emailsent1'];?><code><? print $email; ?></code><!--with a link that will allow your to change your password.</p> <p><span class=good>You should now check your email</span>.</p> <p>If you remember your old password, you can simply ignore the email, and--><?print $thislang['changepass_emailsent2'];?> <a href="?go=login&email=<? print urlencode($email); ?>"><!--login as normal--><?print $thislang['changepass_loginasnormal'];?></a></p><?
} elseif($user != null and ($act == 'reset' or $act == 'request_new_pass')){
  if($act == 'reset') {
    if($password) { // password reset
      reset_user_password($user['id'], md5($user['email'] . $password));
      ?>
      <p><p class="good"><!--Your password has been changed!--><?print $thislang['changepass_passchanged'];?></p>
      <? if(!isset($_SESSION['id'])) {
        ?><p><a href="?go=login&email=<? print urlencode($user['email']); ?>"><!--You can now login.--><?print $thislang['changepass_login'];?></a></p>
        <?
      } else {
        ?><!--<div class="backarrow"><a href="?">Return to overview</a></div> <div class="backarrow"><a href="?go=profile">Return to profile</a></div>--><?print $thislang['changepass_return'];?><?
      }
    } else {
      ?><span class="warning"><!--You have to enter a blank password!--><?print $thislang['changepass_blankpass'];?></span><?
      $act == 'request_new_pass';
    }
  }
  if($act == 'request_new_pass'){
    ?>
    <p class="good"><!--You can now change your password.--><?print $thislang['changepass_doit'];?></p>
    <p><!--Type in your new password:--><?print $thislang['changepass_newpass'];?></p> 
    <form action="?go=change_pass" method="post">
    <input type="hidden" name="act" value="reset">
    <input type="hidden" name="email" value="<? print $email; ?>">
    <input type="hidden" name="unlock" value="<? print $unlock; ?>">
    <? print_required_field(null, "Password");?>: 
    <input type="password" name="password" size="20"><br>
    <input class="greenbutton" type="submit" value=<?print $thislang['change_password'];?>>
    </form>
    <?
  }
} else { 
  if(isset($_SESSION['id'])) {
    ?><!--<div class="backarrow"><a href="?">Return to overview</a></div> <div class="backarrow"><a href="?go=profile">Return to profile</a></div>--><?print $thislang['changepass_return'];?><?
  } else {
    ?><div class="backarrow"><a href="?go=login"><!--Return to login page--><?print $thislang['changepass_returnlogin'];?></a></div><?
  }
  ?>
  <p>
  <!--This page allows you to request a change your TheoryMine password.-->
  <?print $thislang['changepass_title'];?>
  </p><form action="?go=change_pass&act=send_pass_reset_key" method="post">
  <? print_required_field($email, /*"Enter the email address of your TheoryMine account"*/$thislang['changepass_enteremail']);?>:<br>
  <input type="text" name="email" value="<? print $email; ?>" size="60"><br><br>
  <input class="greenbutton" type="submit" value=<?print $thislang['changepass_requestpass'];?>>
  </form>
  <p><!--Once you have requested to change your password, we will email you with a special link that will let you type in a new password for your account.</p><p> If you remember your old password, you can simply ignore the email, and--><?print $thislang['changepass_p'];?><a href="?go=login&email=<? print urlencode($email); ?>"><!--login as normal.--><?print $thislang['changepass_loginasnormal'];?></a> 
  </p>
  <?
}
?>
