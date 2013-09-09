<meta http-equiv="content-type" content="text/html; charset=gb2312" />
<? $url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['PHP_SELF']; 
$abuse = $url . "?go=abuse&email=" . urlencode($val['email']) . "&act=email_reg_clash";
$login = $url . "?go=login&email=" . urlencode($val['email']);
$change = $url . "?go=change_pass&email=" . urlencode($val['email']);
?>
<? include("pages/email/email_header.php"); ?>
<p>
尊敬的 <? print $val['firstname']; ?> <? print $val['lastname']; ?>, 
<p>
您使用邮箱(<? print $val['email']; ?>)  在<a href="http://theorymine.co.uk">theorymine.co.uk</a>注册。
<p>
然而您的邮箱已经被注册过了，请您使用 <a href="<? print $change; ?>">变更密码</a> 斌使用如下链接: <br>
<code><a href="<? print $change; ?>"><? print $change; ?></a></code>
<p>
或者您可以直接 <a href="<? print $login; ?>">登录</a> from:<br>
<code><a href="<? print $login; ?>"><? print $login; ?></a></code>
<p>
如果您并没有注册，那意味着有人盗用或者滥用您的邮箱，您可以 <a href="<? print $abuse; ?>">反馈异常行为</a> 并点击:<br>
<code><a href="<? print $abuse; ?>"><? print $abuse; ?></a></code>

<?include("pages/email/email_footer.php"); ?>
