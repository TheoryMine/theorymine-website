<? include("../pages/email/email_header.php"); ?>
<meta http-equiv="content-type" content="text/html; charset=gb2312" />
<? $url = "http://theorymine.co.uk"; ?>
<p>
�𾴵� <? print  $val['firstname']; ?> <? print  $val['lastname']; ?>,  
<p>��ϲ�������� <a href="<? print $url ?>">TheoryMine.co.uk</a> �Ķ����ѳɹ�<br/>
���ǵĻ�����ѧ�����ڷ�����Ϊ���Ķ�������: </p>
<p align="center">
<? print $val['thm_name']; ?></p>

<p>�����Ҫ����48Сʱ. </p>

<p>
һ�����Ķ�������ˣ����Ǿͻ���㷢��ȷ���ţ����а���PDF֤�飨�ɴ�ӡ�ģ������ӡ�</p>

<p>������<a href="<? print $url ?>/?go=login">��¼�˻�</a>�鿴��������״̬���������Ķ����֤�����Ϣ���߸��������˻�����ϸ��Ϣ.

<p>����ǰ������paypal�Ľ���ID��:  <? print $val['paypal_txn_id'];?> <br/>
�����յ�����paypal��һ���ʼ���ȷ�ϡ� 

</p>
<p>
�������Ҫ�������Ϣ���߰�����������ϵ���� <a href="mailto:<? print $admin_email; ?>"><? print $admin_email; ?></a> 
���߲鿴 <a href="<? print $url ?>/?go=faq">��������</a>.
</p>
<? include("../pages/email/email_footer.php"); ?>
