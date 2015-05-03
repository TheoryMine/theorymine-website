<?
$act = set_default($_REQUEST['act'], 'view');
$here_link = "?go=admin";
// check already authenticated as admin
if($_SESSION['userkind'] != 'admin'){
  $pass = set_default($_POST['admin_pass'], null);
  //print "<p>given pass: $pass";
  //print "<p>given md5 pass: " . MD5($pass);
  //print "<p>admin md5: " . $admin_pass;
  if(MD5($pass) == $admin_pass) {
    $_SESSION['firstname'] = 'admin';
    $_SESSION['email'] = $admin_email;
    $_SESSION['lastname'] = 'admin';
    $_SESSION['userkind'] = 'admin';
    $_SESSION['id'] = '1';
  }
}

//print "<p>admin pass: $admin_pass";
if($_SESSION['userkind'] == 'admin'){
  if($_REQUEST['s'] == 'users'
     OR $_REQUEST['s'] == 'rels'
     OR $_REQUEST['s'] == 'setup'
     OR $_REQUEST['s'] == 'points'
     OR $_REQUEST['s'] == 'certificates'
     OR $_REQUEST['s'] == 'thm'
     OR $_REQUEST['s'] == 'email_users'
     OR $_REQUEST['s'] == 'thy'
     OR $_REQUEST['s'] == 'orders'
     OR $_REQUEST['s'] == 'gifts'
     OR $_REQUEST['s'] == 'uploader'
     OR $_REQUEST['s'] == 'certificate2'
     OR $_REQUEST['s'] == 'certificate3') {
    $subpage = $_REQUEST['s'];
  } else {
    $subpage = "setup";
  }
  $header_title = 'Admin: ' . $act;
  include 'pages/common_parts/header.php';
  include 'pages/admin/' . $subpage . '.php';
} else {
  $header_title = 'Admin: ' . $act;
  include 'pages/common_parts/header.php';
  include 'pages/admin/login.php';
}
?>
