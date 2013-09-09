<? $url = "http://" . $_SERVER['SERVER_NAME'] . $_SERVER['PHP_SELF']; 
$finish = $url . "?go=unlock&email=" . urlencode($val['email']) . "&unlock=" . $val['userkey'];
$abuse = $url . "?go=abuse&email=" . urlencode($val['email']) . "&act=registration";
?>
<? include("pages/email/email_header.php"); ?>
<p>
Dear <? print $val['firstname']; ?> <? print $val['lastname']; ?>, 
<p>You are almost registered with <a href="http://theorymine.co.uk">theorymine.co.uk</a>.</p>
<p>
To complete the registration process goto:<br>
<code><a href="<? print $finish; ?>"><? print $finish; ?></a></code>
<p>
If you did not register, someone else has registered you. If so, you can ignore this email, or report the invalid registration attempt by going to:<br>
<code><a href="<? print $abuse; ?>"><? print $abuse; ?></a></code>
</p>
<p>
 
<?include("pages/email/email_footer.php"); ?>
