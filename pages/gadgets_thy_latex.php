<?

//require_once('tcpdf/config/lang/eng.php');
//require_once('tcpdf/tcpdf.php'); 

// TO DO: change pid to cert; note people have been sent the old URL! 
$cert_id =sql_str_escape($_REQUEST['pid']);
$nofooter = 1;

$order_point = get_from_point_related_to_title($cert_id,'order.','has_certificate.');
if($order_point == null) {
  include("pages/bad_cert_id.php"); 
  exit();
} else {
  $thm_point = get_point_related_from($order_point,'thm.','named.');
  //get_point_in_type($pid, 'thm.named.' );
  $thy_point = get_point_related_from($thm_point,"thy.","inthy.");
  $proof = get_point_related_to($thm_point,"proof.","proof.");
  
  $date = sqltimestamp_to_str($thm_point['time_stamp']);
  $thm_title= utf8_encode ( $thm_point['title']) ;
  $thm_body=  utf8_encode ($thm_point['body']);
  //$thm_body= utf8_encode ("try &alpha;");
  $proof_body = utf8_encode ("Proof outline: ". $proof['body']);
  $thy_title= utf8_encode ($thy_point['title']);
  $thy_body=  utf8_encode ($thy_point['body']);
  
 
  //preg_replace
  $thm_body2 = preg_replace(
              array('\s*<sub>&', ';</sub>\s*', 'omega'), 
              array('_{','}', 'o'),
              $thm_body
               );
 
 
 
 list($thy_parts12, $thy_part3)= split('</table>\s*<table>', $thy_body);
 list($thy_part1, $thy_part2) = split('<br>\s*<br>', $thy_parts12_temp);
 
 $datatype_1 = preg_replace(
              array('\s*<sub>&', ';</sub>\s*', 'omega', '<table>\s*<tr>\s*<td align="center" (width="100%)*>\s*', '\s*<br>\s*', ';'), 
              array('_{','}', 'o', '', '//', ' '),
              $thy_part1
               );
 
 $datatype_2 = preg_replace(
              array('\s*<sub>&', ';</sub>\s*', 'omega', '\s*</td>\s*</tr>\s*</table>', "\s*<br>\s*'';"), 
              array('_{','}', 'o', '', '//',''),
              $thy_part1_temp
               );

  
  /*
  $thm_title=  'FLAMINIA THEOREM';
  $thm_body=  'a+b = b+a';
  $thy_title= 'Material World Theory';
  $thy_body=  'Set T = C1(Bool, Bool) | C2(T ); mw(C1(b1,b2),y) = y;  mw(C2(x),y) = C2(mw(x,y))';
  */
  
  //MAKE CERTIFICATE FROM TEMPLATE
      
  $template_tex_file = "certificates/theory_template.tex";
  
  $data = file_get_contents($template_tex_file);
  if(!$data) {
    die_at_noted_problem("can't find tex file: " . $template_tex_file);
  } else{
    print($data);
    $data = str_replace(array(
              '*********DATA TYPE1*********', 
              '*********DATA TYPE2*********',
              '*********THEORY*********',
              '*********THEOREM*********'
              ), array(
              $datatype_1,
              $datatype_2,
              $thy_body2,
              $thm_body2
              ),
              $data
    );
  
    print($data);
  }
}
?>
