<meta http-equiv="content-type" content="text/html; charset=gb2312" />
<? $url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['PHP_SELF']; 
$abuse = $url . "?go=abuse&email=" . urlencode($val['email']) . "&act=email_reg_clash";
$login = $url . "?go=login&email=" . urlencode($val['email']);
$change = $url . "?go=change_pass&email=" . urlencode($val['email']);
?>
<? include("pages/email/email_header.php"); ?>
<p>
�𾴵� <? print $val['firstname']; ?> <? print $val['lastname']; ?>, 
<p>
��ʹ������(<? print $val['email']; ?>)  ��<a href="http://theorymine.co.uk">theorymine.co.uk</a>ע�ᡣ
<p>
Ȼ�����������Ѿ���ע����ˣ�����ʹ�� <a href="<? print $change; ?>">�������</a> ��ʹ����������: <br>
<code><a href="<? print $change; ?>"><? print $change; ?></a></code>
<p>
����������ֱ�� <a href="<? print $login; ?>">��¼</a> from:<br>
<code><a href="<? print $login; ?>"><? print $login; ?></a></code>
<p>
�������û��ע�ᣬ����ζ�����˵��û��������������䣬������ <a href="<? print $abuse; ?>">�����쳣��Ϊ</a> �����:<br>
<code><a href="<? print $abuse; ?>"><? print $abuse; ?></a></code>

<?include("pages/email/email_footer.php"); ?>
