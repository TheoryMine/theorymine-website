<? include("../pages/email/email_header.php"); ?>
<meta http-equiv="content-type" content="text/html; charset=gb2312" />
<? $url = "http://theorymine.co.uk"; ?>
<p>
尊敬的 <? print  $val['firstname']; ?> <? print  $val['lastname']; ?>,  
<p>恭喜您，您在 <a href="<? print $url ?>">TheoryMine.co.uk</a> 的订单已成功<br/>
我们的机器数学家正在奋力的为您的定理工作着: </p>
<p align="center">
<? print $val['thm_name']; ?></p>

<p>这可能要花费48小时. </p>

<p>
一旦您的定理就绪了，我们就会给你发送确认信，信中包含PDF证书（可打印的）的链接。</p>

<p>您可以<a href="<? print $url ?>/?go=login">登录账户</a>查看您订单的状态，访问您的定义和证书的信息或者更改您的账户的详细信息.

<p>您当前订单的paypal的交易ID是:  <? print $val['paypal_txn_id'];?> <br/>
您会收到来自paypal的一封邮件来确认。 

</p>
<p>
如果您需要更多的信息或者帮助，请来联系我们 <a href="mailto:<? print $admin_email; ?>"><? print $admin_email; ?></a> 
或者查看 <a href="<? print $url ?>/?go=faq">常见问题</a>.
</p>
<? include("../pages/email/email_footer.php"); ?>
