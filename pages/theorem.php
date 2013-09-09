<?php  

$pid = $_REQUEST['pid'];

$thm_point = get_point_in_type($pid, 'thm.named.' );
$proof_point = get_point_related_to($thm_point,"proof.","proof.");
$thy_point = get_point_related_from($thm_point,"thy.","inthy.");

$header_title = "Theorem | " . htmlentities($thm_point['title']);
include 'pages/common_parts/header.php';
?>
<center>
<div class="theorem-view">
<div class="theorem-name-title">
<? print($thm_point['title']); ?>
</div>
Let
<div class="theorem-theory"><? print($thy_point['body']); ?></div>
then
<div class="theorem-statement"><? print($thm_point['body']); ?></div>
<div class="theorem-proof">Proof outline: <? print($proof_point['body']); ?></div>
</div>
</center>

<div class="theorem-you-can-report-abuse">
If you find this theorem name abusive, you can <a href="?go=report_name&pid=
<? sql_str_escape(print ($pid));?>">report it</a>.
</div>

