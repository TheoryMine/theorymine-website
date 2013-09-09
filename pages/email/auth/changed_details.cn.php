<meta http-equiv="content-type" content="text/html; charset=gb2312" />
<? $url = "http://" . $_SERVER['SERVER_NAME'] . $_SERVER['PHP_SELF']; 
$abuse_url = $url . "?go=abuse&email=" . urlencode($val['email']) . "&act=change_details";
?>
<? include("pages/email/email_header.php"); ?>
<p>
尊敬的 <? print $val['firstname']; ?>, 
<p>
该邮件提醒您，您<a href="http://theorymine.co.uk">TheoryMine.co.uk</a> 账户 (<code><? print $val['email']; ?></code>)
具体信息已被更新为: 
<p>
邮箱地址: <code><? print $val['email']; ?></code>
<br>名: <? print $val['firstname']; ?>
<br>姓: <? print $val['lastname']; ?>
<br>时间: <? print date("H:i:s, j M Y"); ?>
</p>
<p>
如果您没有更新资料，那意味着其他人恶意操作。请您向我们反馈这一事件到<br>
<code><a href="<? print $abuse_url; ?>"><? print $abuse_url; ?></a></code>
</p>
<? include("pages/email/email_footer.php");?>
