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
尊敬的 <? print $val['firstname']; ?> <? print $val['lastname']; ?>, 
<p>
您申请了重置<a href="http://theorymine.co.uk">TheoryMine.co.uk</a> 密码，邮箱为: <code><? print $val['email']; ?></code> 
<p>
要继续重置密码请点击 <br>
<code><a href="<? print $reset_url; ?>"><? print $reset_url; ?></a></code>
<p>
如果您并未申请重置密码，那意味着其他人恶意操作您的数据，请您通过如下来举报:<br>
<code><a href="<? print $abuse_url; ?>"><? print $abuse_url; ?></a></code>

<? include("pages/email/email_footer.php"); ?>
