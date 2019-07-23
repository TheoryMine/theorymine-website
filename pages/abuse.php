<?
$email = set_default(sql_str_escape($_REQUEST['email']),null);
$act = set_default(sql_str_escape($_REQUEST['act']),null);

$message = "Abuse reported by $email: \n\n $act";

// send_email($admin_email, $site_name . ': Abuse', $message);

$header_title = "Reporting Abuse";
include 'pages/common_parts/header.php';
?>

<p><!--Thank your reporting abuse of the system. We will do our best to investigate the incident.--><?print $thislang['abuse'];?></p>


