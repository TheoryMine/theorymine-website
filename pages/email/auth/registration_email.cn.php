<meta http-equiv="content-type" content="text/html; charset=gb2312" />
<? $url = "http://" . $_SERVER['SERVER_NAME'] . $_SERVER['PHP_SELF']; 
$finish = $url . "?go=unlock&email=" . urlencode($val['email']) . "&unlock=" . $val['userkey'];
$abuse = $url . "?go=abuse&email=" . urlencode($val['email']) . "&act=registration";
?>
<? include("pages/email/email_header.php"); ?>
<p>
�𾴵� <? print $val['firstname']; ?> <? print $val['lastname']; ?>, 
<p>���� <a href="http://theorymine.co.uk">theorymine.co.uk</a>ע���˻�Ա.</p>
<p>
���ע�ᣬ��Ҫ���������:<br>
<code><a href="<? print $finish; ?>"><? print $finish; ?></a></code>
<p>
�����û��ע�ᣬ˵����������������������ע�ᣬ�����Ժ��Ը��ʼ����������Ƿ�����<br>
<code><a href="<? print $abuse; ?>"><? print $abuse; ?></a></code>
</p>
<p>
 
<?include("pages/email/email_footer.php"); ?>
