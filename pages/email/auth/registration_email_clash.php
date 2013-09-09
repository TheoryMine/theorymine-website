<? $url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['PHP_SELF']; 
$abuse = $url . "?go=abuse&email=" . urlencode($val['email']) . "&act=email_reg_clash";
$login = $url . "?go=login&email=" . urlencode($val['email']);
$change = $url . "?go=change_pass&email=" . urlencode($val['email']);
?>
<? include("pages/email/email_header.php"); ?>
<p>
Dear <? print $val['firstname']; ?> <? print $val['lastname']; ?>, 
<p>
It appears you tried to register your email address (<? print $val['email']; ?>) with <a href="http://theorymine.co.uk">theorymine.co.uk</a>.
<p>
However, your email address is aleady registered, if you have forgotten your password, you can <a href="<? print $change; ?>">change your password</a> using the following link: <br>
<code><a href="<? print $change; ?>"><? print $change; ?></a></code>
<p>
Otherwise you can simply <a href="<? print $login; ?>">login</a> from:<br>
<code><a href="<? print $login; ?>"><? print $login; ?></a></code>
<p>
If you did not try to re-register, someone else has tried to register you. You can <a href="<? print $abuse; ?>">report the strange behaviour</a> by going to:<br>
<code><a href="<? print $abuse; ?>"><? print $abuse; ?></a></code>

<?include("pages/email/email_footer.php"); ?>
