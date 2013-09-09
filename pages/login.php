<?

$login_tried = set_default(sql_str_escape($_POST['tried']),null);
$email2 = set_default(sql_str_escape($_REQUEST['email']),null);
$email = str_replace(' ', '', $email2);
$password = set_default(sql_str_escape($_POST['password']),null);

// check/try login/unlocking of user account. 
if ($email && $password) {
  force_logout();
  $user = try_login($email, $password);
  // Successful Login
  //print_r($user);
  if($user != NULL) {
    if($user['last_act_kind'] == 'new_user' 
      or $user['last_act_kind'] == 'locked_user')
    {
      // needs unlocking...
      $login_failed = false;
      $user = null; 
      $login_waiting_email = true;
    } else {
      $_SESSION['id'] = $user['id'];
      $_SESSION['firstname'] = $user['firstname'];
      $_SESSION['lastname'] = $user['lastname'];
      $_SESSION['email'] = $user['email'];
      $_SESSION['userkind'] = $user['userkind'];

      // not locked, so we can login!
      $login_failed = false; 
      $just_logged_in = true; // set only if just sent login info
      new_msg("Welcome! You have logged in successfully.");
    }
  } else {
    $login_failed = true;
  }
} elseif(!$login_tried or (!$email and !$password)) {
  $login_failed = false; // they did not even try! just ognore them
} else {
  $login_failed = true;
}

// if logged in...
if(isset($_SESSION['id'])) {
  include 'pages/overview.php';
} else {
  $header_title = "Login";
  include 'pages/common_parts/header.php';
  ?>
  
  <h1><!--Login to TheoryMine--><?print $thislang['login_title'];?></h1>
  
  <p>
  <?
  if($login_failed) {
    ?> <p><!--<span class="warning">Login failed</span>, check your email address/login name and re-enter your password.</p><p>If you have forgotten your password, we can email you a link to--><?print $thislang['login_failed'];?><a href="?go=change_pass&email=<? print urlencode($email); ?>"><!--reset your password--><?print $thislang['login_resetpassword'];?></a>.</p><?
  }
  if($login_waiting_email) {
    ?> <p><!--<span class="warning">You need to read your email</span>, you have been emailed a link that you need in order to login. <p>This is because you  newly registered this address, or because you have changed your login details. <p> If you have lost the email we sent you, you can <a href="?go=change_pass">request a change of password</a> to get sent a new code with which you can login.--><?print $thislang['login_waiting_email'];?></p><?
  } else {
    ?>
    <p><!--Don't have an account?--><?print $thislang['login_noaccount'];?>
    <a href="?go=register&email=<? print urlencode($email); ?>"><!--Register--><?print $thislang['register'];?></a>.</p>
    </p><p>
    <form action="?go=login" method="post">
    <input type="hidden" name="tried" value="yes">
    <input type="hidden" name="unlock" value="<? print $unlock; ?>">
    <!--Email:--><?print $thislang['email'];?>: <input type="text" name="email" size="50" value="<? print $email; ?>"><br>
    <br>
    <!--Password: --><?print $thislang['password'];?><input type="password" name="password" value="<? print $password; ?>"><br>
    (<!--forgot your password? --><a href="?go=change_pass&email=<?print urlencode($email);?>"><?print $thislang['login_pass_forgot'];?></a>)<br>
    <br>
    <input type="submit" value="<?print $thislang['login'];?>">
    </form>
    </p>
    
    <?
  }
}
?>
