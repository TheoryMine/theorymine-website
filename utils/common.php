<?php

// Undo evil php magic quotes
if (get_magic_quotes_gpc()) {
  // die_at_noted_problem("This does not work PHP magic_quotes_gpc; make you need to turn that off. ");
  $_POST = array_map('stripslashes_rec', $_POST);
  $_GET = array_map('stripslashes_rec', $_GET);
  $_COOKIE = array_map('stripslashes_rec', $_COOKIE);
  $_REQUEST = array_map('stripslashes_rec', $_REQUEST);
}


// require user to be logged in: have a non-null user_id
function restrict_is_logged_in(){
  if(isset($_SESSION['id'])){ return true; }
  else{ fail_page(); };
}

function restrict_is_admin(){
  if($_SESSION['userkind'] == 'admin')
  { return true; } else { fail_page(); };
}

/* add message to global messages list (info messages for user)*/
function new_msg($s) {
  global $msgs;
  if($msgs == null) {
    $msgs = array();
  }
  array_push($msgs,$s);
}

function fail_page(){
  global $register_link, $login_link, $profile_link, $logout_link;
  include('pages/timeout.php');
  include('pages/common_parts/footer.php');
  die("");
}

function str_starts($big, $sml) {
  $l1 = strlen($big);
  $l2 = strlen($sml);
  return ($l1 >= $l2 and ($sml == substr($big, 0, $l2)));
}

function subtype($s1,$s2){
  return str_starts($s1,$s2);
}

function in_debug_mode() {
  global $_SESSION;
  //return false;
  return ($_SESSION['debug'] == "on");
}

function print_debug_array($a) {
  if(in_debug_mode()) {
    ?><div class="debug-point-footer">DBG MSG: -- <?
    foreach(array_keys($a) as $k) {
      ?> <nobr> <? print $k; ?>: <? print $a[$k]; ?> </nobr> -- <?
    }
    ?></div><?
  }
}

function print_debug_point($a) {
  if(in_debug_mode()) {
    ?><div class="debug-point-footer">DBG MSG: -- <?
    foreach(array_keys($a) as $k) {
      if($k != 'title' and $k != 'body') {
        ?> <nobr><? print $k; ?>: <? print $a[$k]; ?> </nobr> -- <?
      }
    }
    ?></div><?
  }
}



function stripslashes_rec($value)
{
  $value = is_array($value) ?
  array_map('stripslashes_rec', $value) :
  stripslashes($value);
  return $value;
}


// set default value:
//it means if $value then returne $value else return $default
function set_default($value, $default){
    return $value ? $value : $default;
}

function print_required_field($field,$s){
  if(empty($field)){
    ?><span class="warning"><? print $s; ?> *</span><?
  } else {
    print $s;
  }
}



// user input error: it's not a bug, just badly formed input from the user.
function note_user_error($message){
  global $admin_email, $site_name, $act, $email, $header_printed;

  if(!$header_printed) {
    include 'pages/common_parts/header.php';
  }
  ?>
  <div class="warning">Error! <code><? print $message; ?></code></div><?
}

function die_at_user_error($message){
  note_user_error($message);
  include 'pages/common_parts/footer.php';
  die("");
}

// email admin with bug message
function noted_problem($message){
  global $admin_email, $site_name, $act, $email, $header_printed;

  if(!$header_printed) {
    include 'pages/common_parts/header.php';
  }

  $full_msg =
  "Bug reported by user_id: " . $_SESSION['id'] .
  "\n SERVER: " .print_r($_SERVER, true) .
  "\n SESSION: " . print_r($_SESSION,true)
  . "\n -- \n" . $message . "\n -- \n"
  . "\n\nbacktrace: \n"
  . print_r(debug_backtrace(),true)
  /* . "\n\nGlobals: \n"
  . print_r($globals, true) */
  . "\n ---- \n";

  if(in_debug_mode() or $_SESSION['userkind'] == admin) {
    str_replace('\n','<br>',$full_msg);
    ?><div class="bug"><code><? print $full_msg; ?></code></div><?
  } else {
    // send_email($admin_email, $site_name . ': Bug', $full_msg);
  }

  ?>
  <h1>Bug!</h1>
  <p>Oops, this website has gone wrong! The bug has been reported; but feel free to tell us more about what you were trying to do, and how it went wrong by emailing us at: <a href="mailto:<? print $admin_email; ?>"><? print $admin_email; ?></a>.</p>
  <?
}

// email admin with bug message
function die_at_noted_problem($message){
  noted_problem($message);
  include 'pages/common_parts/footer.php';
  die("");
}


//TODO fckeditor should not ADD html, fix base link base
//TODO limit input length according to DB field limitations
// places an fckeditor textinput box
/* function place_inputbox($name, $value) {
  $ofckeditor 		= new fckeditor($name) ;
  $ofckeditor->BasePath   	= "fckeditor/";
  //	$ofckeditor->Config['SkinPath'] = "fckeditor/editor/skins/silver/";
  $ofckeditor->ToolbarSet 	= "Basic";
  //    $ofckeditor->Width  		= '100%';
  $ofckeditor->Height = '60%';
  //    $ofckeditor->FormatOutput 	= false;
  //	$ofckeditor->Config['EnterMode'] = '';
  $ofckeditor->Value  		= $value;
  $ofckeditor->Create();
} */

/* Send email of a php file; the php file defines the body, and if it sets rthe value $subject, then we send the email in this function call. Else we return the message body, as constructed by the php file.  */
function email_of_phpfile($filename, $val) {
  global $site_name, $admin_email;
  ob_start();
  include($filename);// This file needs to set the subject
  $return_str = ob_get_contents();
  ob_end_clean();
  return $return_str;
}

function send_log_email($m) {
  global $admin_email;
 // $log_email = str_replace("@", "+log@", $admin_email);
  $lfcr = "\r\n";
  mail($admin_email, "TheoryMine: Log (". date("H:i:s, j M Y") . ")",
    "<html><pre>" . $m . "</pre></html>",
    'MIME-Version: 1.0' . $lfcr .
      'Content-type: text/html; charset=iso-8859-1' . $lfcr .
      'From: TheoryMine <' . $admin_email . ">" . $lfcr .
      'Reply-To: ' . $admin_email . $lfcr .
      'Content-Type: text/html;' . $lfcr .
      'X-Mailer: PHP/' . phpversion()
  );
}

function note_in_log($m) {
  ob_start();
  debug_print_backtrace();
  $trace = ob_get_contents();
  ob_end_clean();

  if(in_debug_mode()){
    str_replace('\n','<br>',$m);
    ?>
    <div class="debug">LOG MSG: -- <? print $m; ?></div>
    <?
  } elseif(file_exists("prefs/log.txt")) {
    $f = fopen("prefs/log.txt", "a");
    if($f != null) {
      $m .= "\n<br>Trace:" . $trace;
      fwrite($f, "\n[Date: " . date("H:i:s, j M Y") . "]" . $m);
      fclose($f);
    } else {
      send_log_email("WARNING: log file exists, but can't be written to.<br>" . $m);
    }
  } else {
     send_log_email($m);
  }
}

function log_as_point($subj,$msg,$logkind = '') {
  global $db_points_h, $db_actions;
  global $admin_email;

  $point_h_id = db_new_point($db_points_h, array ('history_id' => 0, 'title' => sql_str_escape($subj), 'body' => sql_str_escape($msg), 'point_type' => 'log.' . sql_str_escape($logkind), 'action_id' => 0));

  //send_email(
  //  "ldixon@inf.ed.ac.uk",
  //  $subj,
  //  "<pre>" . $msg . "</pre>");
}

function send_email($to_email,$subject,$message) {
  global $admin_email;
  global $db_points_h, $db_actions;
  $lfcr = "\r\n";
  $header = 'MIME-Version: 1.0' . $lfcr .
    'Content-type: text/html; charset=iso-8859-1' . $lfcr .
    'From: TheoryMine <' . $admin_email . ">" . $lfcr .
    'Reply-To: ' . $admin_email . $lfcr .
    'Content-Type: text/html;' . $lfcr .
    'X-Mailer: PHP/' . phpversion();
    note_in_log("\nTo: $to_email \nSubject: $subject\nheader:\n" . $header . "\n\nMessage:\n" . $message . "\n\n");
  mail($to_email, $subject, $message, $header, '-f' . $admin_email);
}

function sqlfile_of_phpfile($filename) {
  global $db_tables_prefix,
  $q; // query object can be created
  ob_start();
  include($filename);
  $return_str = ob_get_contents();
  ob_end_clean();
  return $return_str;
}


// removes all session data
function force_logout() {
  foreach(array_keys($_SESSION) as $k) {
    if($k != 'secure') { unset($_SESSION[$k]); }
  }
}

// redirect to $url
function redirect($url){
    if (!headers_sent()){
        header('Location: '.$url);
        exit;
    }
    else{
        echo '<script type="text/javascript">';
        echo 'window.location.href="'.$url.'";';
        echo '</script>';
        echo '<noscript>';
        echo '<meta http-equiv="refresh" content="0;url='.$url.'" />';
        echo '</noscript>';
        exit;
    }
}


/**
 * for passwords, unique values, etc
 */
function genRandomString($length = 8, $characters = '0123456789abcdefghjkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ') {
    $n = strlen($characters);
    $string ='';
    for ($p = 0; $p < $length; $p++) {
        $string .= $characters[mt_rand(0, $n)];
    }
    return $string;
}

?>
