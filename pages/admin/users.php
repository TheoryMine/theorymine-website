<?
restrict_is_admin();

$here_link = "?go=admin&s=users";

$act = set_default($_REQUEST['act'], null);
$search = set_default($_REQUEST['search'], null);
$offset = set_default(sql_str_escape($_REQUEST['offset']), 0);
$limit = set_default(sql_str_escape($_REQUEST['limit']), 10);
$act = set_default($_REQUEST['act'], 'search');

if($act == "edit-user" or $act == "show-edit-user") {
  $user_id2 = set_default($_REQUEST['user_id2'], null);
  $user = get_user($user_id2);
}

if($act == "edit-user" or $act == "make-new-user"){
  $email2 = set_default($_REQUEST['email2'], null);
  $password2 = set_default($_REQUEST['password2'], null);
  $firstname2 = set_default($_REQUEST['firstname2'], null);
  $lastname2 = set_default($_REQUEST['lastname2'], null);
  $userkind2 = set_default($_REQUEST['userkind2'], null);
  $last_act_kind2 = set_default($_REQUEST['last_act_kind2'], null);
  $last_act_code2 = set_default($_REQUEST['last_act_code2'], null);

  if($act == "edit-user"){
    
    if($email2 != $user['email'] and try_get_user_by_email($email2) != null){
      $email_clash = true; 
    } else { 
      $email_clash = false; 
    }
    
    if($email_clash or $email2 == null){
      $act = "show-edit-user";
      $user['email'] = $email2;
      $user['password'] = $password2;
      $user['firstname'] = $firstname2;
      $user['lastname'] = $lastname2;
      $user['userkind'] = $userkind2;
      $user['last_act_kind'] = $last_act_kind2;
      $user['last_act_code'] = $last_act_code2;
    } else {
      edit_user($user_id2, sql_str_escape($email2), sql_str_escape($firstname2), sql_str_escape($lastname2), sql_str_escape($userkind2), sql_str_escape($last_act_kind2), sql_str_escape($last_act_code2), sql_str_escape($password2));
      $act='search';
      $search="id='$user_id2'";
    }
  }
  
  if($act == "make-new-user"){
    if($email2 == null or $password2 == null){
      $act = "enter-new-user";
    } elseif(try_get_user_by_email($email2)){
      $email_clash = true;
      $act = "enter-new-user";
    } else {
      $email_clash = false;
      make_new_user(sql_str_escape($lastname2), sql_str_escape($firstname2), sql_str_escape($email2), sql_str_escape($password2), sql_str_escape($userkind2));      
      $act='search';
      $search="email='$email2'";
    }
  }
}


if($act == "delete-user" or $act == "lock-user"){
  $user_id2 = set_default($_REQUEST['user_id2'], null);
  $user = try_get_user($user_id2);
  
  if($user != null){
    if($act == "delete-user" and delete_user($user_id2) != null) {
        ?><h3 class="warning">Deleted user:</h3><?
        $act='search';
        $search=null;
    } elseif($act == "lock-user" and renew_user_lock($user_id2) != null){
      ?><h3 class="warning">Locked user:</h3><?
      $act='search';
      $search="id='$user_id2'";
    }
    ?>
    <div class="red-block">
    (<? print $user['id']; ?>) <? print htmlentities($user['email']); ?><br>
    Firstname: <? print htmlentities($user['firstname']); ?><br>
    Lastname: <? print htmlentities($user['lastname']); ?><br>
    Kind: <? print $user['userkind']; ?><br>
    Lact action: <? print $user['last_act_time']; ?> : 
    <? print $user['last_act_kind']; ?> : <? print $user['last_act_code']; ?>
    </div>
    <?
  } else {
    ?><p>
    <span class="warning"><? print $act; ?> (<? print $user_id2; ?>) failed.</span>
    </p><?
    $act='search';
    $search=null;
  }
}
?>
<h3>Search</h3>

  <form action="?go=admin&s=users" method="post">
  <input type="hidden" name="act" value="search" size="70">
  <input type="text" name="search" value="<? print $search; ?>" size="70">
  <input class="greenbutton" type="submit" value="Search!"> &nbsp; <a class="greenbutton" href="?go=admin&s=users">Show All</a><br>
  e.g. <code>id = '3'</code> for finding user with id of 3, <code>email >= 'ed.ac.uk'</code> for finding all email addresses containing the substring 'ed.ac.uk'.
  </form>

<?
if($act == 'search') {
  $where = interpret_search($search);
  ?><p>SQL
  <? if($where == "") {
    ?><span class="warning">bad/empty query</span><?
  } ?>:
  <code><? print $where; ?></code></p><?
  $res = get_users_where($where);
  $rows = $res['rows'];
  if($rows != null) { ?>
    <h3>Found Users:</h3>
    <div class="simple-border">
    <?
    $toggle = true;
    $fst = true;
    foreach($rows as $user) {
      $toggle = !$toggle;
      if($fst){ $fst = false; 
        ?><div class="simple-list0"><?
      } else if($toggle){ 
        ?><div class="simple-list1"><?
      } else {
        ?><div class="simple-list2"><?
      } ?>
      <div class="edit-btns-right">
        <a href="?go=admin&s=users&act=show-edit-user&user_id2=<? print $user['id']; ?>">edit</a>
      </div>
      id: <? print $user['id']; ?>; 
      <? print htmlentities($user['email']); ?><br>
      Name: <? print htmlentities($user['firstname']); ?> <? print htmlentities($user['lastname']); ?><br>
      Kind: <? print $user['userkind']; ?><br>
      Lact action: <? print $user['last_act_time']; ?> : 
      <? print $user['last_act_kind']; ?> : <? print $user['last_act_code']; ?>
      </div>
      <?
    }
    ?>
    </div>
    <?
    // rows != null
  } else {
    ?><p><span class="warning">No entries</span></p><?
  }
}


if($act == "show-edit-user"){
  ?>
  <p>
  <div class="simple-block">
  <h3> Edit User </h3>
  
  <p> 
  <h4>Change user details: </h4>
  <form action="?go=admin&s=users" method="post">
  <input type="hidden" name="act" value="edit-user">
  <input type="hidden" name="user_id2" value="<? print($user['id']); ?>">

  <table border="0">
  
  <tr><td align="right">user_id:</td>
  <td><? print($user['id']); ?></td></tr>
 
  <tr><td align="right" valign="top"><? print_required_field($user['email'], "email"); ?>:
</td>
  <td><input type="text" name="email2" size="40" value="<? print(htmlentities($user['email'])); ?>">
  <? 
  if($email_clash){
    ?><br><span class="warning">This email address clashes with an existing registered user.</span><?
  }
  ?>
  </td></tr>
  
  <tr><td align="right">first name:</td>
  <td><input type="text" name="firstname2" size="40" value="<? print(htmlentities($user['firstname'])); ?>"></td></tr>
  
  <tr><td align="right">last name:</td>
  <td><input type="text" name="lastname2" size="40" value="<? print(htmlentities($user['lastname'])); ?>"></td></tr>
  
  <tr><td align="right">user kind:</td>
  <td><input type="text" name="userkind2" size="40" value="<? print(htmlentities($user['userkind'])); ?>"></td></tr>
  
  <tr><td align="right">last action kind:</td>
  <td><input type="text" name="last_act_kind2" size="40" value="<? print(htmlentities($user['last_act_kind'])); ?>"></td></tr>
  
  <tr><td align="right">last action code:</td>
  <td><input type="text" name="last_act_code2" size="40" value="<? print(htmlentities($user['last_act_code'])); ?>"></td></tr>
  
  <!-- <tr><td align="right">password hash:</td>
  <td><? 
  // print($user['password']); 
  ?></td></tr> -->
  
  <tr><td align="right">change password to <br>(empty for unchanged):</td>
  <td><input type="text" name="password2" size="20" value=""></td></tr>
  <tr><td colspan="2" align="center"><br>
  <input class="greenbutton" type="submit" value="Save changes"> &nbsp;&nbsp; <a class="redbutton" href="?go=admin&s=users&search=<? print urlencode("id='" . $user['id'] . "'"); ?>">Cancel</a></td></tr>
  </table>
  </form>
  
  <h4>Other actions</h4>
  <!-- 
  To lock the account and send the user a new registration email: <br>
  <a class="greenbutton" href="?go=admin&s=users&act=reg-email&user_id2=<? print($user['id']); ?>" method="post">New registration and send email</a> 
  <br><br>
  To allow the user to reset the password and send the email registered address an email with the password change code:<br>
  <a class="greenbutton" href="?go=admin&s=users&act=pass-reset-email&user_id2=<? print($user['id']); ?>" method="post">Do and Send password reset request</a> 
  -->
  <!-- <br><br>
  To update the last-change timestamp to now:<br>
  <a class="greenbutton" href="?go=admin&s=users&act=touch&user_id=<? print($user['id']); ?>" method="post">Update time</a> 
  <br><br> -->
  To lock the account (without notifying the user): 
  <a class="redbutton" href="?go=admin&s=users&act=lock-user&user_id2=<? print($user['id']); ?>" method="post">lock user</a> 
  <br><br>
  To <span class="warning">delete</span> the user: 
  <a class="redbutton" href="?go=admin&s=users&act=delete-user&user_id2=<? print($user['id']); ?>" method="post">Delete User</a>
  
  </div>
  <?
} else {
  $user = array();
}

if($act == "enter-new-user"){
  ?>
  <p>
  <div class="simple-block">
  <h3> New User Details </h3>
  <form action="?go=admin&s=users" method="post">
  <input type="hidden" name="act" value="make-new-user">
  <table border="0">
  <tr><td align="right" valign="top">
  <? print_required_field($email2, "email"); ?>:
  </td><td>
  <? if($email_clash){
    ?><input type="text" name="email2" size="40" value="<? print(htmlentities($email2)); ?>"><br>(<span class="warning">"<? print(htmlentities($email2)); ?>" already registered</span>, enter an unregistered email address)
    <? 
  } else {
    ?><input type="text" name="email2" size="40" value="<? print(htmlentities($email2)); ?>"><br>(Enter an unregistered email address) <?
  } ?>
  </td></tr>
  <tr><td align="right">first name:</td>
  <td><input type="text" name="firstname2" size="30" value="<? print(htmlentities($firstname2)); ?>"></td></tr>
  <tr><td align="right">last name:</td>
  <td><input type="text" name="lastname2" size="30" value="<? print(htmlentities($lastname2)); ?>"></td></tr>
  <tr><td align="right">user kind:</td>
  <td><input type="text" name="userkind2" size="20" value="<? print(htmlentities($userkind2)); ?>"></td></tr>
  <tr><td align="right">
  <? print_required_field($password2, "password"); ?>:</td>
  <td><input type="text" name="password2" size="20" value="<? print(htmlentities($password2)); ?>"></td></tr>
  <tr><td colspan="2" align="center">
  <br>
  <input class="greenbutton" type="submit" value="Make new user"> &nbsp;&nbsp; <a class="redbutton" href="?go=admin&s=users">Cancel</a></td></tr>
  </table>
  </form>
  </div>
  <?
} else {
  ?><p><a href="?go=admin&s=users&act=enter-new-user">Make a new user</a></p><?
}
?>
