<? include("pages/email/email_header.php"); ?>
<? $url = "http://" . $_SERVER['SERVER_NAME'] . $_SERVER['PHP_SELF'];  ?>
<meta http-equiv="content-type" content="text/html; charset=gb2312" />
�𾴵� <? print $val['firstname']; ?> <? print $val['lastname']; ?>,  
<p>

���Ǽ����ĸ����������ǵĻ�����ѧ���Ѿ�������һ���¶�������Ϊ
<?  print  $val['tname']; ?> </font><br/>

��л������ѧ��ʷ�Ĺ��ף�

</p>

<p>
�����ڿ��Ե��<a href="www.theorymine.co.uk/certificates/<?print htmlentities($val['certificate_id']);?>/certificate.pdf">����pdf֤��</a>���鿴���Ķ���. �������غ������Դ�ӡ���������������Ԥ����һ����Ʒ���Ͱ��������Ǹ����˶��ɡ�
</p>
<p>
�����Ե��<a href="www.theorymine.co.uk/certificates/<?print htmlentities($val['certificate_id']);?>/brouchure.pdf">����</a>�������ֲᣬ���ֲὫ���������õ�������Ķ���
</p>
<p>
�����Ե��<a href="<? print $url ?>?go=login">��¼</a> ��������ϵͳ�����"�鿴֤��"���� "�鿴�ֲ�" ������Ӧ������
</p>

<p>
��ע�ⲻ�����е�PDF�������֧����֤���е���ѧ���š�ĳЩ�ַ�������ʾ����ȷ�������ʹ��Adobe Reader����������أ��Ͳ��������������⡣
</p>
<p>
�����ڿ��Թ�������Ķ�����ص�T������˱������棡���<a href="<? print $url ?>?go=login">��¼</a>�󣬵�����������ұߵġ���Ʒ���ġ���������
</p>
<p>
�������Ҫ�������Ϣ���߰�����������ϵ���� <a href="mailto:<? print $admin_email; ?>"><? print $admin_email; ?></a> 
���߲鿴 <a href="<? print $url ?>/?go=faq">��������</a>.
</p>
<p>
<? include("pages/email/email_footer.php"); ?>
