<?php

// certificate id
$cid = $argv[1];

// type to send to page; says what kind of latex doc to create. 
$dockind = $argv[2];

// url prefix for domain and dir prefix: e.g. theorymine.co.uk/vtp2, 
// or localhost:8888/theorymine.co.uk
$url = $argv[3];

// directory where local theory output lives.) e.g. output
// in which to run (save stuff)
$run_dir = $argv[4]; 

// value of pass of post field e.g. vtppassU1 
$url_pass = $argv[5]; 

// empty for none, or LOGIN:PASS e.g. vtp:ca3nyH9ewgHR
$httpauth = $argv[6]; 

chdir($run_dir);

$ch = curl_init();

$data = array(
  'cid' => $cid,
  'pass' => $url_pass,
  'dockind' => $dockind,
);

curl_setopt($ch, CURLOPT_URL, $url);
//curl_setopt($ch, CURLOPT_URL, '?go=import_theorems');
// 
if($httpauth != null){ 
  curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
  curl_setopt($ch, CURLOPT_USERPWD, $httpauth);
}
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
//curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1) ;
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; en-US; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13");

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_VERBOSE, true);
  
$latex_data = curl_exec($ch);
$error_no = curl_errno($ch);
curl_close($ch);
if ($error_no == 0) {
  $error = "got latex successfully. \n";
} else {
  $error = "error: " . $error_no . "\n";
}

$fp = fopen('certificate.tex', 'w');
fwrite($fp,$latex_data);
fclose($fp);

$fp = fopen('log.txt', 'a');
fwrite($fp,$latex_data);
fwrite($fp,$error);
fclose($fp);

?>
