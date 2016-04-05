<script>
function alert()
{
  alert("Error: Please ensure you have completed both fields before submiting the form. Also ensure that there is only one email address.");
}
</script>

<?php


$here_link = "?go=send_mail";


// This function checks for email injection. Specifically, it checks for carriage returns - typically used by spammers to inject a CC list.
function isInjected($str) {
	$injections = array('(\n+)',
	'(\r+)',
	'(\t+)',
	'(%0A+)',
	'(%0D+)',
	'(%08+)',
	'(%09+)'
	);
	$inject = join('|', $injections);
	$inject = "/$inject/i";
	if(preg_match($inject,$str)) {
		return true;
	}
	else {
		return false;
	}
}

// Load form field data into variables.
$email_address = $_REQUEST['email_address'] ;
$comments = $_REQUEST['comments'] ;

// If the user tries to access this script directly, redirect them to feedback form,
if (!isset($_REQUEST['email_address'])) {
//header( "Location: ?go=overview" );
}

// If the form fields are empty, redirect to the error page.
elseif (empty($email_address) || empty($comments)) {
//header( "Location: ?go=errorm" );
include 'pages/common_parts/header.php';
?>
<h1>Oops!</h1>
<p>Please ensure you have completed both fields before submitting the form. Also ensure that there is only one email address.</p>
<p><a href="JavaScript:history.go(-1);">Back</a></p>
<?
}

// If email injection is detected, redirect to the error page.
elseif ( isInjected($email_address) ) {
  include 'pages/common_parts/header.php';
?>
<h1>Oops!</h1>
<p>Please ensure you have completed both fields before submitting the form.
Also ensure that there is only one email address.</p>
<p><a href="JavaScript:history.go(-1);">Back</a></p>
<?


}

// If we passed all previous tests, send the email!
else {
mail( "support@theorymine.co.uk", "Feedback Form Results",
  $comments, "From: $email_address" );
//header( "Location: ?go=thankyou" );
include 'pages/common_parts/header.php';
?>

<h1>Thank you!</h1>


<p>Thanks for your feedback!</p>
<p>We appreciate that you took the time to send us feedback.</p>
<p><a href="?go=overview">Back to homepage</a>.</p>
<?
}
?>
