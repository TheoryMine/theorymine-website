<?
/* set the subject of the email: */
$subject = $val['site_name'] . ': Password Reset';

/* set urls */
$url = "http://" . $_SERVER['SERVER_NAME'] . $_SERVER['PHP_SELF']; 
$reset_url = $url  . "?go=change_pass&email=" . urlencode($val['email']) . "&act=request_new_pass&unlock=" . $val['user_key'];
$abuse_url = $url . "?go=abuse&email=" . urlencode($val['email']) . "&act=request_new_pass";
?><? 
?>
<? include("pages/email/email_header.php"); ?>
<p>
Dear <? print $val['firstname']; ?> <? print $val['lastname']; ?>, 
<p>
A request was made to reset your <a href="http://theorymine.co.uk">TheoryMine.co.uk</a> password for the email address: <code><? print $val['email']; ?></code> 
<p>
To reset your password, go to: <br>
<code><a href="<? print $reset_url; ?>"><? print $reset_url; ?></a></code>
<p>
If you did not ask to reset your password, someone else has. You can report the
abuse by going to:<br>
<code><a href="<? print $abuse_url; ?>"><? print $abuse_url; ?></a></code>

<? include("pages/email/email_footer.php"); ?>
