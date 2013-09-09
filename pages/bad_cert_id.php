<?
$here_link = "?go=overview";
$header_title = "Bad certificate id";
include 'pages/common_parts/header.php';
?>

<h2><!--Bad certificate id--><?print $thislang['badid_title'];?></h2>

<p><!--It looks like you entered a incomplete or incorrect certificate link. Please check your certificate link. If you continue to have a problem, please--><?print $thislang['badid_p'];?> <a href="mailto:<? print($admin_email); ?>"><!--let us know--><?print $thislang['letusknow'];?></a>.<p/>
