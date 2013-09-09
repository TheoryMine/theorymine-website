<?php  
$here_link = "?go=profile";
$header_title = "Your Profile";
include 'pages/common_parts/header.php';

$fromlink = "?" . $_SERVER['QUERY_STRING'];

if(!isset($_SESSION['id'])) {
  ?> <p><!--You need to <a href="?go=login">login</a> 
  to access you TheoryMine profile.--><?print $thislang['profile_login'];?></p> <?
} else { // logged in already
  $user = try_get_user($_SESSION['id']);
  $act = set_default($_REQUEST['act'], "show");
  if($act == "update"){
    $firstname = set_default(sql_str_escape($_REQUEST['firstname']), null);
    $lastname = set_default(sql_str_escape($_REQUEST['lastname']), null);
    $email = set_default(sql_str_escape($_REQUEST['email']), null);
    $password = set_default(sql_str_escape($_REQUEST['password']), null);

    // IMPROVE: require email confirmation of changes to email addresses
    if($password == null) {
      $edit_result = "missing_password";
      $act = "edit";
    } else if(md5($user['email'] . $password) 
              == sql_str_escape($user['password'])){
      $email_vals = array('email' => $email, 'site_name' => $site_name, 'lastname' => $lastname, 'firstname' => $firstname);
      $emailfile = 'pages/email/auth/changed_details.'.$thislang['lang'].'.php';
      $message = email_of_phpfile($emailfile, $email_vals);
      if($email == $user['email']){
        send_email($email, $site_name . ': Changed Details', $message);
      } else {
        send_email($email, $site_name . ': Changed Details', $message);
        send_email($user['email'], $site_name . ': Changed Details', $message);
      }
      
      edit_user($_SESSION['id'], $email, $firstname, $lastname, 
        $user['userkind'], "updated_details", "none");
      
      $user = try_get_user($_SESSION['id']);
      $edit_result = "updated_user_details";
      $act = "show";
    } else {
      unset($password);
      $edit_result = "wrong_password";
      $act = "edit";
    }
  }
  
  if($act == "edit"){
    $firstname = set_default($_REQUEST['firstname'], $user['firstname']);
    $lastname = set_default($_REQUEST['lastname'], $user['lastname']);
    $email = set_default($_REQUEST['email'], $user['email']);
    
    if($edit_result == "missing_password"){
      ?><p><span class="warning"><!--You need to enter your password to update your details.--><?print $thislang['profile_enterpass'];?></a></p><?
    } else if($edit_result == "wrong_password"){
      ?><p><span class="warning"><!--The password you enetred did not match your old password, enter your old password to update your details.--><?print $thislang['profile_wrongpass'];?></a></p><?
    }
    ?>
    <h3><!--Edit Your Details:--><?print $thislang['profile_edit'];?></h3>
    <p>
    <form action="?go=profile&act=update" method="post">
    <? print_required_field($email, /*"Email"*/$thislang['email']);?>: 
    <input type="text" name="email" size="40" value="<? print $email; ?>">
    <br>
    <? print_required_field($firstname, /*"First Name"*/ $thislang['firstname']); ?>: 
    <input type="text" name="firstname" size="40" value="<? print $firstname; ?>"><br>
    <? print_required_field($lastname, /*"Last Name"*/ $thislang['lastname']);?>: 
    <input type="text" name="lastname" size="40" value="<?$lastname; ?>"><br>
    <br> <!--To make changes you need to enter your --><?print $thislang['profile_needto'];?><? print_required_field($password, /*"password"*/$thislang['password']);?>: 
    <input type="password" name="password" size="20"><br>
    <a href="?go=change_pass&email=<? print $user['email']; ?>"><!--(You can also change your password)--><?print $thislang['profile_changepass'];?> </a>
    <br><br>
    <a class="redbutton" href="?go=profile"><!--Cancel--><?print $thislang['cancel'];?></a> &nbsp;&nbsp;
    <input class="greenbutton" type="submit" value=<?print $thislang['update'];?>>
    </form>
    </p>
    <?
  }
  
  if($act == "show"){
    if($edit_result == "updated_user_details"){
      ?><p><span class="good"><!--Details updated--><?print $thislang['profile_good'];?></a></p><?
    }
    ?> 
    <div class="backarrow"><a href="?"><!--Return to overview--><?print $thislang['profile_return'];?></a></div>

    <table class="profile">
    <tr><td>
      <h3><!--Your Details:--><?print $thislang['profile_details'];?></h3>
      <div class="box">
      <p><!--Email/login--><?print $thislang['email'];?>: <? print $user['email']; ?>
      <br><!--First name--><?print $thislang['firstname'];?>: <? print $user['firstname']; ?>
      <br><!--Last name--><?print $thislang['lastname'];?>: <? print $user['lastname']; ?>
      </p>
      <p>
      (<a href="?go=profile&act=edit"><?print $thislang['edit_details'];?></a>, <a href="?go=change_pass&email=<? print $user['email']; ?>"><?print $thislang['change_password'];?></a>)
      </p>
      </div>
    </td></tr>
    <tr><td>
        <? include("pages/common_parts/discoveries-in-progress.php"); ?>
  
        <? include("pages/common_parts/discovered-theorems.php"); ?>
    </td>
    </tr>
    </table>
    
<!--    <div class="box">
    <p>User id: <? print $user['id']; ?>
 , last action: <? print $user['last_act_kind']; ?>, at: <? print $user['last_act_time']; ?> 
    </p>
    </div> -->
    <?
  }
}
?>

