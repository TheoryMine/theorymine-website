<?php

// url of file to get: e.g. http://theorymine.co.uk/vtp2/certificates/CERT_ID/cert_img.jpg, 
// or http://localhost:8888/theorymine.co.uk/images/logo.jpg
$url = $argv[1];

// OPTIONAL ARGUMENTS for http auth
// value of pass of post field e.g. vtppassU1 
$url_pass = $argv[2]; 
// empty for none, or LOGIN:PASS e.g. vtp:ca3nyH9ewgHR
$httpauth = $argv[3]; 

// get the final bit of the URL to create the filename which we'll save
$path = parse_url($url,PHP_URL_PATH);
$save_filename = basename(urldecode($path));

// start building the HTTP query 
$ch = curl_init();

// set the url (GET part)
curl_setopt($ch, CURLOPT_URL, $url);

// set the http auth, if we've been given the arguments for it. 
if($httpauth != null){ 
  curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
  curl_setopt($ch, CURLOPT_USERPWD, $httpauth);
}

// pretend we are a normal browser -- probably not needed
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; en-US; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13");

// print out what's going on to console for debugging
curl_setopt($ch, CURLOPT_VERBOSE, true);

// so that exec saves the result to the filename
$fp = fopen($save_filename, 'wb');
curl_setopt($ch, CURLOPT_FILE, $fp);
curl_setopt($ch, CURLOPT_HEADER, 0);

// run the query
if(curl_exec($ch)) {
  $status_string = "Downloaded successfully";
} else {
  $error_no = curl_errno($ch);
  $status_string = "*** Error: " . $error_no;
}
curl_close($ch);
fclose($fp);

$fp = fopen('log.txt', 'a');
date_default_timezone_set(date_default_timezone_get());
fwrite($fp, date('Y-M-d H:i:s').": Tried to download: '$url', result: $status_string\n");
fclose($fp);

?>
