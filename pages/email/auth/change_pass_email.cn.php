<meta http-equiv="content-type" content="text/html; charset=gb2312" />
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
�𾴵� <? print $val['firstname']; ?> <? print $val['lastname']; ?>, 
<p>
������������<a href="http://theorymine.co.uk">TheoryMine.co.uk</a> ���룬����Ϊ: <code><? print $val['email']; ?></code> 
<p>
Ҫ���������������� <br>
<code><a href="<? print $reset_url; ?>"><? print $reset_url; ?></a></code>
<p>
�������δ�����������룬����ζ�������˶�������������ݣ�����ͨ���������ٱ�:<br>
<code><a href="<? print $abuse_url; ?>"><? print $abuse_url; ?></a></code>

<? include("pages/email/email_footer.php"); ?>
