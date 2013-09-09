<? $url = "http://" . $_SERVER['SERVER_NAME'] . $_SERVER['PHP_SELF']; 
$abuse_url = $url . "?go=abuse&email=" . urlencode($val['email']) . "&act=change_details";
?>
<? include("pages/email/email_header.php"); ?>
<p>
Dear <? print $val['firstname']; ?>, 
<p>
This is a notification that the details for your <a href="http://theorymine.co.uk">TheoryMine.co.uk</a> account (<code><? print $val['email']; ?></code>)
were updated to: 
<p>
Email: <code><? print $val['email']; ?></code>
<br>First name: <? print $val['firstname']; ?>
<br>Last name: <? print $val['lastname']; ?>
<br>Time and Date: <? print date("H:i:s, j M Y"); ?>
</p>
<p>
If you did not update your details, someone else has. If so, you can report 
abuse of the system by going to: <br>
<code><a href="<? print $abuse_url; ?>"><? print $abuse_url; ?></a></code>
</p>
<? include("pages/email/email_footer.php");?>
