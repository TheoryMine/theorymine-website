<?php  
$lastname = sql_str_escape($_POST['lastname']);
$firstname = sql_str_escape($_POST['firstname']);
$email = trim(sql_str_escape($_POST['email']));
$email2 = trim(sql_str_escape($_POST['email2']));
$thmname = sql_str_escape($_POST['thmname']);

// has any data been entered
if ((! empty($lastname)) OR (! empty($firstname)) OR (! empty($thmname)) OR
    (! empty($email2)) OR (! empty($email)))
{ $some_entry = true; } else { $some_entry = false; }

// is any needed data missing
if(empty($email2) or empty($thmname) or empty($email) or empty($lastname) or empty($firstname)) { 
  $missing_entry = true; 
} else { 
  $missing_entry = false;

  if($email == $email2){ 
    $email_mismatch = false;
    if(eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email)) {
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

$title = "TheoryMine: name a theorem";
include 'pages/common_parts/header.php';

if( (! $bad_entry) and $some_entry){
  if(isset($_SESSION['id'])) { $user_id = $_SESSION['id']; }
  else { $user_id = 1; } 
  $point = change_some_point($user_id, "point_type = 'thm.unnamed'", array('point_type' => "thm.named", 'title' => $thmname),  'named.theorem', $point['id'] . " named $thmname by $firstname $lastname ($email)");
  if($point == null){
    ?>Please bear with us while we prove some more mathematics!<? 
  } else {
    ?>Contratulations! you have named a new piece of mathematics:

    <br>
    <b><? print($point['title']); ?></b> (theorem id:  <? print($point['id']);?>):
    <b><? print($point['body']); ?></b>
    was proved on <? print($point['time_stamp']); ?>, named by <? print("$firstname $lastname"); ?>
    <br>
    <?
  }
} else {
  // Need to fill out details to register
  $title = "TheoryMine: buy a personalised theorem";
  include 'pages/common_parts/header.php';
  ?>
  <h2>Buy a personalised theorem </h2>
  <p>
  TheoryMine offers personalized, newly discovered, mathematical theorems as a novelty gift. By naming your very own mathematical theorem, newly generated by one of the world's most advanced computerised theorem provers, you can immortalise your loved ones, teachers, friends and even yourself and your favourite pets.  
  </p>
  <?  
  if($some_entry and $missing_entry) {
    ?> <p><span class="warning">Please enter all the fields marked with *</span></p><?
  } else if($email_mismatch) {
    ?> <p><span class="warning">Your email address entries are not the same, please check them and make sure they both correctly contain your email address.</span></p><?
  } else if($bad_email){
    ?><p><span class="warning">Your email address contains an error, please correct it and retype it. </span></p><?
  }
  ?>
  <p>
  <form action="?go=buy" method="post">
  <? print_required_field($thmname, "Theorem Name");?>: 
  <input type="text" name="thmname" size="60" value="<? print($thmname); ?>">
  <p>
  If you would also like to register <br>
  <? print_required_field($email, "Email address");?>: 
  <input type="text" name="email" size="60" value="<? print($email); ?>">
  <br>
  <? print_required_field($email2, "please retype your email address");?>: 
  <input type="text" name="email2" size="60" value="<? print($email2); ?>">
  <br>
  <br>
  <? print_required_field($firstname, "First Name"); ?>: 
  <input type="text" name="firstname" size="60" value="<? print($firstname); ?>"><br>
  <br>
  <? print_required_field($lastname, "Last Name"); ?>: 
  <input type="text" name="lastname" size="60" value="<? print($lastname); ?>"><br>
  <br>
  <input class="greenbutton" type="submit" value="Buy with Paypal">
  </form>
  </p>
  <?
}
?>
