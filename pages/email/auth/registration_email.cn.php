<meta http-equiv="content-type" content="text/html; charset=gb2312" />
<? $url = "http://" . $_SERVER['SERVER_NAME'] . $_SERVER['PHP_SELF']; 
$finish = $url . "?go=unlock&email=" . urlencode($val['email']) . "&unlock=" . $val['userkey'];
$abuse = $url . "?go=abuse&email=" . urlencode($val['email']) . "&act=registration";
?>
<? include("pages/email/email_header.php"); ?>
<p>
尊敬的 <? print $val['firstname']; ?> <? print $val['lastname']; ?>, 
<p>您在 <a href="http://theorymine.co.uk">theorymine.co.uk</a>注册了会员.</p>
<p>
完成注册，需要您点击链接:<br>
<code><a href="<? print $finish; ?>"><? print $finish; ?></a></code>
<p>
如果您没有注册，说明其他人滥用您的邮箱来注册，您可以忽略该邮件或者向我们反馈：<br>
<code><a href="<? print $abuse; ?>"><? print $abuse; ?></a></code>
</p>
<p>
 
<?include("pages/email/email_footer.php"); ?>
