<meta http-equiv="content-type" content="text/html; charset=gb2312" />
<? $url = "http://" . $_SERVER['SERVER_NAME'] . $_SERVER['PHP_SELF']; 
$abuse_url = $url . "?go=abuse&email=" . urlencode($val['email']) . "&act=change_details";
?>
<? include("pages/email/email_header.php"); ?>
<p>
�𾴵� <? print $val['firstname']; ?>, 
<p>
���ʼ�����������<a href="http://theorymine.co.uk">TheoryMine.co.uk</a> �˻� (<code><? print $val['email']; ?></code>)
������Ϣ�ѱ�����Ϊ: 
<p>
�����ַ: <code><? print $val['email']; ?></code>
<br>��: <? print $val['firstname']; ?>
<br>��: <? print $val['lastname']; ?>
<br>ʱ��: <? print date("H:i:s, j M Y"); ?>
</p>
<p>
�����û�и������ϣ�����ζ�������˶�����������������Ƿ�����һ�¼���<br>
<code><a href="<? print $abuse_url; ?>"><? print $abuse_url; ?></a></code>
</p>
<? include("pages/email/email_footer.php");?>
