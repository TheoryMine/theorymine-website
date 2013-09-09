<?

//require_once('tcpdf/config/lang/eng.php');
//require_once('tcpdf/tcpdf.php'); 

// Certificate ID 
$cert_id = sql_str_escape($_REQUEST['cid']);
/* dockind is 'certificate' when we generate the certifiate, 'theory' for a pdf of the theory, 'theorem' for PDF of the theorem only */
$doc_kind = sql_str_escape($_REQUEST['dockind']);

// check that we have the right certificate
$order_point = get_from_point_related_to_title($cert_id,'order.','has_certificate.');
if($order_point == null) {
  include("pages/bad_cert_id.php"); 
  exit();
} else {
  // avoid footer HTML appearing is our latex!
  $nofooter = 1;

  $thm_point = get_point_related_from($order_point,'thm.','named.');
  //get_point_in_type($pid, 'thm.named.' );
  $thy_point = get_point_related_from($thm_point,"thy.","inthy.");
  $proof = get_point_related_to($thm_point,"proof.","proof.");
  
  $date = sqltimestamp_to_str($thm_point['time_stamp']);
  $thm_title=  ( $thm_point['title']) ;
  $thm_body=  utf8_encode ($thm_point['body']);
  //$thm_body= utf8_encode ("try &alpha;");
  $proof_body = utf8_encode ("Proof outline: ". $proof['body']);
  $thy_title= utf8_encode ($thy_point['title']);
  $thy_body=  utf8_encode ($thy_point['body']);
  
  
$latex_remplacements= array(
'&#913;' =>'A' ,'&#914;' =>'B' ,'&#915;' =>'\Gamma' ,'&#916;' =>'\Delta' ,'&#917;' =>'E' ,'&#918;' =>'Z' ,'&#919;' =>'H' ,'&#920;' =>'\Theta' ,'&#921;' =>'I' ,'&#922;' =>'K' ,'&#923;' =>'\Lambda' ,'&#924;' =>'M' ,'&#925;' =>'N' ,'&#926;' =>'\Xi' ,'&#927;' =>'O' ,'&#928;' =>'\Pi' ,'&#929;' =>'P' ,'&#931;' =>'\Sigma' ,'&#932;' =>'T' ,'&#933;' =>' Y' ,'&#934;' =>'\Phi' ,'&#935;' =>'X' ,'&#936;' =>'\Psi' ,'&#937;' =>'\Omega' ,'&#945;' =>'\alpha' ,'&#946;' =>'\beta' ,'&#947;' =>'\gamma' ,'&#948;' =>'\delta' ,'&#949;' =>'\epsilon' ,'&#950;' =>'\zeta' ,'&#951;' =>'\eta' ,'&#952;' =>'\theta' ,'&#953;' =>'\iota' ,'&#954;' =>'\kappa' ,'&#955;' =>'\lambda' ,'&#956;' =>'\mu' ,'&#957;' =>'\nu' ,'&#958;' =>'\xi' ,'&#959;' =>' o' ,'&#960;' =>'\pi' ,'&#961;' =>'\rho' ,'&#963;' =>'\sigma' ,'&#964;' =>'\tau' ,'&#965;' =>'\upsilon' ,'&#966;' =>'\phi' ,'&#967;' =>'\chi' ,'&#968;' =>'\psi' ,'&#969;' =>'\omega' ,'&#962;' =>'\varsigma' ,'&#977;' =>'\vartheta' ,'&#982;' =>'\varpi' ,'&#8230;' =>'\ldots' ,'&#8242;' =>'\prime' ,'&#8254;' =>'-' ,'&#8260;' =>'/' ,'&#8472;' =>'\wp' ,'&#8465;' =>'\Im' ,'&#8476;' =>'\Re' ,'&#8501;' =>'\aleph' ,'&#8226;' =>'\bullet' ,'&#8482;' =>'^{\rm TM}'  ,'&#8592;' =>'\leftarrow' ,'&#8594;' =>'\rightarrow' ,'&#8593;' =>'\uparrow' ,'&#8595;' =>'\downarrow' ,'&#8596;' =>'\leftrightarrow' ,'&#8629;' =>'\hookleftarrow' ,'&#8657;' =>'\Uparrow' ,'&#8659;' =>'\Downarrow' ,'&#8656;' =>'\Leftarrow' ,'&#8658;' =>'\Rightarrow' ,'&#8660;' =>'\Leftrightarrow' ,'&#8704;' =>'\forall' ,'&#8706;' =>'\partial' ,'&#8707;' =>'\exists' ,'&#8709;' =>'\emptyset' ,'&#8711;' =>'\nabla' ,'&#8712;' =>'\in' ,'&#8715;' =>'\ni' ,'&#8713;' =>'\notin' ,'&#8721;' =>'\sum' ,'&#8719;' =>'\prod' ,'&#8722;' =>'-' ,'&#8727;' =>'\ast' ,'&#8730;' =>'\surd' ,'&#8733;' =>'\propto' ,'&#8734;' =>'\infty' ,'&#8736;' =>'\angle' ,'&#8743;' =>'\wedge' ,'&#8744;' =>'\vee' ,'&#8745;' =>'\cup' ,'&#8746;' =>'\cap' ,'&#8747;' =>'\int' ,'&#8756;' =>'\therefore'  ,'&#8764;' =>'\sim' ,'&#8776;' =>'\approx' ,'&#8773;' =>'\cong' ,'&#8800;' =>'\neq' ,'&#8801;' =>'\equiv' ,'&#8804;' =>'\leq' ,'&#8805;' =>'\geq' ,'&#8834;' =>'\subset' ,'&#8835;' =>'\supset' ,'&#8838;' =>'\subseteq' ,'&#8839;' =>'\supseteq' ,'&#8836;' =>'\nsubset'  ,'&#8853;' =>'\oplus' ,'&#8855;' =>'\otimes' ,'&#8869;' =>'\perp' ,'&#8901;' =>'\cdot' ,'&#8968;' =>'\rceil' ,'&#8969;' =>'\lceil' ,'&#8970;' =>'\lfloor' ,'&#8971;' =>'\rfloor' ,'&#9001;' =>'\rangle' ,'&#9002;' =>'\langle' ,'&#9674;' =>'\lozenge'  ,'&#9824;' =>'\spadesuit' ,'&#9827;' =>'\clubsuit' ,'&#9829;' =>'\heartsuit' ,'&#9830;' =>'\diamondsuit' ,'&#38;' =>'\@AMP' ,'&#34;' =>'@DOUBLEQUOT' ,'&#169;' =>'\copyright' ,'&#60;' =>'@LT' ,'&#62;' =>'@GT' ,'&#338;' =>'\OE' ,'&#339;' =>'\oe' ,'&#352;' =>'\v{S}' ,'&#353;' =>'\v{s}' ,'&#376;' =>'\"Y' ,'&#710;' =>'\textasciicircum' ,'&#732;' =>'\textasciitilde' ,'&#8211;' =>'--' ,'&#8212;' =>'---' ,'&#8216;' =>'`' ,'&#8217;' =>'@QUOT' ,'&#8220;' =>'``' ,'&#8221;' =>'@QUOT@QUOT' ,'&#8224;' =>'\dag' ,'&#8225;' =>'\ddag' ,'&#8240;' =>'\permil'  ,'&#8364;' =>'\euro'  ,'&#8249;' =>'\guilsinglleft' ,'&#8250;' =>'\guilsinglright' ,'&#160;' =>'\nolinebreak' ,'&#161;' =>'\textexclamdown' ,'&#163;' =>'\pounds' ,'&#164;' =>'\currency'  ,'&#165;' =>'\textyen'  ,'&#166;' =>'\brokenvert'  ,'&#167;' =>'\S' ,'&#171;' =>'\guillemotleft' ,'&#187;' =>'\guillemotright' ,'&#174;' =>'\textregistered' ,'&#170;' =>'\textordfeminine' ,'&#172;' =>'\neg' ,'&#176;' =>'\degree'  ,'&#177;' =>'\pm' ,'&#180;' =>'@QUOT' ,'&#181;' =>'\mu' ,'&#182;' =>'\P' ,'&#183;' =>'\cdot' ,'&#186;' =>'\textordmasculine' ,'&#162;' =>'\cent'  ,'&#185;' =>'^1' ,'&#178;' =>'^2' ,'&#179;' =>'^3' ,'&#189;' =>'\frac{1}{2}' ,'&#188;' =>'\frac{1}{4}' ,'&#190;' =>'\frac{3}{4}' ,'&#192;' =>'\`A' ,'&#193;' =>'\@QUOTA' ,'&#194;' =>'\^A' ,'&#195;' =>'\~A' ,'&#196;' =>'\@DOUBLEQUOTA' ,'&#197;' =>'\AA' ,'&#198;' =>'\AE' ,'&#199;' =>'\cC' ,'&#200;' =>'\`E' ,'&#201;' =>'\@QUOTE' ,'&#202;' =>'\^E' ,'&#203;' =>'\@DOUBLEQUOTE' ,'&#204;' =>'\`I' ,'&#205;' =>'\@QUOTI' ,'&#206;' =>'\^I' ,'&#207;' =>'\"I' ,'&#208;' =>'\eth'  ,'&#209;' =>'\~N' ,'&#210;' =>'\`O' ,'&#211;' =>'\@QUOTO' ,'&#212;' =>'\^O' ,'&#213;' =>'\~O' ,'&#214;' =>'\@DOUBLEQUOTO' ,'&#215;' =>'\times' ,'&#216;' =>'\O' ,'&#217;' =>'\`U' ,'&#218;' =>'\@QUOTU' ,'&#219;' =>'\^U' ,'&#220;' =>'\@DOUBLEQUOTU' ,'&#221;' =>'\@QUOTY' ,'&#222;' =>'\Thorn'  ,'&#223;' =>'\ss' ,'&#224;' =>'\`a' ,'&#225;' =>'\@QUOTa' ,'&#226;' =>'\^a' ,'&#227;' =>'\~a' ,'&#228;' =>'\@DOUBLEQUOTa' ,'&#229;' =>'\aa' ,'&#230;' =>'\ae' ,'&#231;' =>'\cc' ,'&#232;' =>'\`e' ,'&#233;' =>'\@QUOTe' ,'&#234;' =>'\^e' ,'&#235;' =>'\@DOUBLEQUOTe' ,'&#236;' =>'\`i' ,'&#237;' =>'\@QUOTi' ,'&#238;' =>'\^i' ,'&#239;' =>'\@DOUBLEQUOTi' ,'&#240;' =>'\eth'  ,'&#241;' =>'\~n' ,'&#242;' =>'\`o' ,'&#243;' =>'\@QUOTo' ,'&#244;' =>'\^o' ,'&#245;' =>'\~o' ,'&#246;' =>'\@DOUBLEQUOTo' ,'&#247;' =>'\divide' ,'&#248;' =>'\o ' ,'&#249;' =>'\`u' ,'&#250;' =>'\@QUOTu' ,'&#251;' =>'\^u' ,'&#252;' =>'\@DOUBLEQUOTu' ,'&#253;' =>'\@QUOTy' ,'&#254;' =>'\thorn'  ,'&#255;' =>'\@DOUBLEQUOTy' , '&#978;' =>' Y' ,
'&Alpha;' =>'A' ,'&Beta;' =>'B' ,'&&#Gamma;' =>'\Gamma' ,'&&#Delta;' =>'\Delta' ,'&Epsilon;' =>'E' ,'&Zeta;' =>'Z' ,'&Eta;' =>'H' ,'&Theta;' =>'\Theta' ,'&Iota;' =>'I' ,'&Kappa;' =>'K' ,'&Lambda;' =>'\Lambda' ,'&Mu;' =>'M' ,'&Nu;' =>'N' ,'&Xi;' =>'\Xi' ,'&Omicron;' =>'O' ,'&Pi;' =>'\Pi' ,'&Rho;' =>'P' ,'&Sigma;' =>'\Sigma' ,'&Tau;' =>'T' ,'&Upsilon;' =>' Y' ,'&Phi;' =>'\Phi' ,'&Chi;' =>'X' ,'&Psi;' =>'\Psi' ,'&Omega;' =>'\Omega' ,'&alpha;' =>'\alpha' ,'&beta;' =>'\beta' ,'&gamma;' =>'\gamma' ,'&delta;' =>'\delta' ,'&epsilon;' =>'\epsilon' ,'&zeta;' =>'\zeta' ,'&eta;' =>'\eta' ,'&theta;' =>'\theta' ,'&iota;' =>'\iota' ,'&kappa;' =>'\kappa' ,'&lambda;' =>'\lambda' ,'&mu;' =>'\mu' ,'&nu;' =>'\nu' ,'&xi;' =>'\xi' ,'&omicron;' =>' o' ,'&pi;' =>'\pi' ,'&rho;' =>'\rho' ,'&sigma;' =>'\sigma' ,'&tau;' =>'\tau' ,'&upsilon;' =>'\upsilon' ,'&phi;' =>'\phi' ,'&chi;' =>'\chi' ,'&psi;' =>'\psi' ,'&omega;' =>'\omega' ,'&sigmaf;' =>'\varsigma' ,'&thetasym;' =>'\vartheta' ,'&piv;' =>'\varpi' ,'&hellip;' =>'\ldots' ,'&prime;' =>'\prime' ,'&oline;' =>'-' ,'&frasl;' =>'/' ,'&weierp;' =>'\wp' ,'&image;' =>'\Im' ,'&real;' =>'\Re' ,'&alefsym;' =>'\aleph' ,'&bull;' =>'\bullet' ,'&trade;' =>'^{\rm TM}'  ,'&larr;' =>'\leftarrow' ,'&rarr;' =>'\rightarrow' ,'&uarr;' =>'\uparrow' ,'&darr;' =>'\downarrow' ,'&harr;' =>'\leftrightarrow' ,'&crarr;' =>'\hookleftarrow' ,'&uArr;' =>'\Uparrow' ,'&dArr;' =>'\Downarrow' ,'&lArr;' =>'\Leftarrow' ,'&rArr;' =>'\Rightarrow' ,'&hArr;' =>'\Leftrightarrow' ,'&forall;' =>'\forall' ,'&part;' =>'\partial' ,'&exist;' =>'\exists' ,'&empty;' =>'\emptyset' ,'&nabla;' =>'\nabla' ,'&isin;' =>'\in' ,'&ni;' =>'\ni' ,'&notin;' =>'\notin' ,'&sum;' =>'\sum' ,'&prod;' =>'\prod' ,'&minus;' =>'-' ,'&lowast;' =>'\ast' ,'&radic;' =>'\surd' ,'&prop;' =>'\propto' ,'&infin;' =>'\infty' ,'&ang;' =>'\angle' ,'&and;' =>'\wedge' ,'&or;' =>'\vee' ,'&cup;' =>'\cup' ,'&cap;' =>'\cap' ,'&int;' =>'\int' ,'&there4;' =>'\therefore'  ,'&sim;' =>'\sim' ,'&asymp;' =>'\approx' ,'&cong;' =>'\cong' ,'&ne;' =>'\neq' ,'&equiv;' =>'\equiv' ,'&le;' =>'\leq' ,'&ge;' =>'\geq' ,'&sub;' =>'\subset' ,'&sup;' =>'\supset' ,'&sube;' =>'\subseteq' ,'&supe;' =>'\supseteq' ,'&nsub;' =>'\nsubset'  ,'&oplus;' =>'\oplus' ,'&otimes;' =>'\otimes' ,'&perp;' =>'\perp' ,'&sdot;' =>'\cdot' ,'&rceil;' =>'\rceil' ,'&lceil;' =>'\lceil' ,'&lfloor;' =>'\lfloor' ,'&rfloor;' =>'\rfloor' ,'&rang;' =>'\rangle' ,'&lang;' =>'\langle' ,'&loz;' =>'\lozenge'  ,'&spades;' =>'\spadesuit' ,'&clubs;' =>'\clubsuit' ,'&hearts;' =>'\heartsuit' ,'&diams;' =>'\diamondsuit' ,'&amp;' =>'\@AMP' ,'&quot;' =>'@DOUBLEQUOT' ,'&copy;' =>'\copyright' ,'&lt;' =>'@LT' ,'&gt;' =>'@GT' ,'&OElig;' =>'\OE' ,'&oelig;' =>'\oe' ,'&Scaron;' =>'\v{S}' ,'&scaron;' =>'\v{s}' ,'&Yuml;' =>'\"Y' ,'&circ;' =>'\textasciicircum' ,'&tilde;' =>'\textasciitilde' ,'&ndash;' =>'--' ,'&mdash;' =>'---' ,'&lsquo;' =>'`' ,'&rsquo;' =>'@QUOT' ,'&ldquo;' =>'``' ,'&rdquo;' =>'@QUOT@QUOT' ,'&dagger;' =>'\dag' ,'&Dagger;' =>'\ddag' ,'&permil;' =>'\permil'  ,'&euro;' =>'\euro'  ,'&lsaquo;' =>'\guilsinglleft' ,'&rsaquo;' =>'\guilsinglright' ,'&nbsp;' =>'\nolinebreak' ,'&iexcl;' =>'\textexclamdown' ,'&pound;' =>'\pounds' ,'&curren;' =>'\currency'  ,'&yen;' =>'\textyen'  ,'&brvbar;' =>'\brokenvert'  ,'&sect;' =>'\S' ,'&laquo;' =>'\guillemotleft' ,'&raquo;' =>'\guillemotright' ,'&reg;' =>'\textregistered' ,'&ordf;' =>'\textordfeminine' ,'&not;' =>'\neg' ,'&deg;' =>'\degree'  ,'&plusmn;' =>'\pm' ,'&acute;' =>'@QUOT' ,'&micro;' =>'\mu' ,'&para;' =>'\P' ,'&middot;' =>'\cdot' ,'&ordm;' =>'\textordmasculine' ,'&cent;' =>'\cent'  ,'&sup1;' =>'^1' ,'&sup2;' =>'^2' ,'&sup3;' =>'^3' ,'&frac12;' =>'\frac{1}{2}' ,'&frac14;' =>'\frac{1}{4}' ,'&frac34;' =>'\frac{3}{4}' ,'&Agrave;' =>'\`A' ,'&Aacute;' =>'\@QUOTA' ,'&Acirc;' =>'\^A' ,'&Atilde;' =>'\~A' ,'&Auml;' =>'\@DOUBLEQUOTA' ,'&Aring;' =>'\AA' ,'&AElig;' =>'\AE' ,'&Ccedil;' =>'\cC' ,'&Egrave;' =>'\`E' ,'&Eacute;' =>'\@QUOTE' ,'&Ecirc;' =>'\^E' ,'&Euml;' =>'\@DOUBLEQUOTE' ,'&Igrave;' =>'\`I' ,'&Iacute;' =>'\@QUOTI' ,'&Icirc;' =>'\^I' ,'&Iuml;' =>'\"I' ,'&ETH;' =>'\eth'  ,'&Ntilde;' =>'\~N' ,'&Ograve;' =>'\`O' ,'&Oacute;' =>'\@QUOTO' ,'&Ocirc;' =>'\^O' ,'&Otilde;' =>'\~O' ,'&Ouml;' =>'\@DOUBLEQUOTO' ,'&times;' =>'\times' ,'&Oslash;' =>'\O' ,'&Ugrave;' =>'\`U' ,'&Uacute;' =>'\@QUOTU' ,'&Ucirc;' =>'\^U' ,'&Uuml;' =>'\@DOUBLEQUOTU' ,'&Yacute;' =>'\@QUOTY' ,'&THORN;' =>'\Thorn'  ,'&szlig;' =>'\ss' ,'&agrave;' =>'\`a' ,'&aacute;' =>'\@QUOTa' ,'&acirc;' =>'\^a' ,'&atilde;' =>'\~a' ,'&auml;' =>'\@DOUBLEQUOTa' ,'&aring;' =>'\aa' ,'&aelig;' =>'\ae' ,'&ccedil;' =>'\cc' ,'&egrave;' =>'\`e' ,'&eacute;' =>'\@QUOTe' ,'&ecirc;' =>'\^e' ,'&euml;' =>'\@DOUBLEQUOTe' ,'&igrave;' =>'\`i' ,'&iacute;' =>'\@QUOTi' ,'&icirc;' =>'\^i' ,'&iuml;' =>'\@DOUBLEQUOTi' ,'&eth;' =>'\eth'  ,'&ntilde;' =>'\~n' ,'&ograve;' =>'\`o' ,'&oacute;' =>'\@QUOTo' ,'&ocirc;' =>'\^o' ,'&otilde;' =>'\~o' ,'&ouml;' =>'\@DOUBLEQUOTo' ,'&divide;' =>'\divide' ,'&oslash;' =>'\o' ,'&ugrave;' =>'\`u' ,'&uacute;' =>'\@QUOTu' ,'&ucirc;' =>'\^u' ,'&uuml;' =>'\@DOUBLEQUOTu' ,'&yacute;' =>'\@QUOTy' ,'&thorn;' =>'\thorn'  ,'&yuml;' =>'\@DOUBLEQUOTy'  ,'&upsih;' =>' Y',
'&#279;'=>'e'
);

$rempl2= array(
  
  );
  $thy_parts =   
          preg_split('/\s*<\s*\/\s*table\s*>\s*<\s*table\s*>\s*/',
                $thy_body);
  
   
  $thy_parts2 = preg_split('/\s*<\s*br\s*\/*\s*>\s*<\s*br\s*\/*\s*>\s*/',
               $thy_parts[0]);
             
          
 
  function replace_html_structure($string, $rempl){
     $remplacement= str_replace((array_keys($rempl)),
                             (array_values($rempl)), $string ); 
        $remplacement2 = preg_replace(
              array('/\_/',
                   '/\s*<\s*sub\s*>\s*/',
                    '/\s*<\s*\/\s*sub\s*>\s*/', 
                    '/\s*<\s*\/\s*tr\s*>\s*<\s*tr\s*>\s*/', 
                    '/omega/',
                    '/\s*<\s*\/*(td|table|tr)(\s*|\s[^>]*)>\s*/',
                    '/\s*\&\s*\#8594\s*;\s*/',
                    '/\s*\&\s*\#8469\s*;\s*/',
                    '/\s*\&\s*times\s*;\s*/',
                    '/\s*<\s*br\s*\/*\s*>\s*/',
                    '/\s*=\s*/',
                    '/\s*\&\s*\#163\s*;\s*/',
                    '/\s&\s/',
                    '/ƒ/'
                    ), 
              array('\\\\_',
                     '_{',
                     '}', 
                    "\\\\\\\\",
                    'o',
                    '',
                    '\\\\rightarrow ',
                    '\\\\nat ',
                    '\\\\times ',
                    "\\\\\\\\",
                    '&=&',
                    '\\\\pounds',
                    '\&',
                    '\\\\\'{E}'
                    ),
              $remplacement
               ); 
        
       
        return $remplacement2;
        
  }
  
  $thy_body2 =  replace_html_structure($thy_parts[1], $latex_remplacements);
  $datatype1 =  replace_html_structure($thy_parts2[0],  $latex_remplacements);
  $datatype2 =  replace_html_structure($thy_parts2[1],  $latex_remplacements);       
  $thm_body2 = replace_html_structure($thm_body,  $latex_remplacements);
  $thm_title2 =  replace_html_structure($thm_title,  $latex_remplacements);
  
  //MAKE CERTIFICATE FROM TEMPLATE
  if( ($doc_kind == "certificate") || ($doc_kind == "certificate_image") ){
    $template_tex_file = "certificates/certificate_template2.tex";
    $processed_tex_file = $id."processed.tex";
    
    $data = file_get_contents($template_tex_file);
    if(!$data) {
      die_at_noted_problem("can't find tex file: " . $template_tex_file);
    } else{
      $data = str_replace(array(
                '*********THEOREM NAME*********',
                '*********DATA TYPE1*********', 
                '*********DATA TYPE2*********',
                '*********THEORY*********',
                '*********THEOREM*********',
                '*********PROOF*********',
                '*********DATE*********'
                ), array(
                $thm_title2,
                $datatype1,
                $datatype2,
                $thy_body2,
                $thm_body2,
                $proof_body,
                $date
                /*
                $thm_title,
                $datatype_1,
                $datatype_2,
                $thy_body2,
                $thm_body2,
                $proof_body,
                $date*/
                ), $data
      );
    }
  } else if($doc_kind == 'theory') {
    $template_tex_file = "certificates/thy_template.tex";
    $processed_tex_file = $id."processed.tex";
    
    $data = file_get_contents($template_tex_file);
    if(!$data) {
      die_at_noted_problem("can't find tex file: " . $template_tex_file);
    } else{
    $data = str_replace(array(
              '*********DATA TYPE1*********', 
              '*********DATA TYPE2*********',
              '*********THEORY*********',
              '*********THEOREM*********'
              ), array(
              $datatype1,
              $datatype2,
              $thy_body2,
              $thm_body2
              ),
              $data
      );
    }
  } else if($doc_kind == 'theorem'){ 
    $template_tex_file = "certificates/thm_template.tex";
    $processed_tex_file = $id."processed.tex";
    
    $data = file_get_contents($template_tex_file);
    if(!$data) {
      die_at_noted_problem("can't find tex file: " . $template_tex_file);
    } else {
    $data = str_replace(array(
              '*********THEOREM*********'),
              array(
              $thm_body2),
              $data
      );
    }
  } else if($doc_kind == "brouchure"){
    $template_tex_file = "certificates/brochure_template.tex";
    $processed_tex_file = $id."processed.tex";
    
    $data = file_get_contents($template_tex_file);
    if(!$data) {
      die_at_noted_problem("can't find tex file: " . $template_tex_file);
    } else{
      $data = str_replace(array(
                '*********THEOREM NAME*********',
                '*********DATA TYPE1*********', 
                '*********DATA TYPE2*********',
                '*********THEORY*********',
                '*********THEOREM*********',
                '*********PROOF*********'
                ), array(
                $thm_title2,
                $datatype1,
                $datatype2,
                $thy_body2,
                $thm_body2,
                $proof_body
      
                ), $data
      );
    } 
  } 
  else if($doc_kind == "brouchure_c"){
    $template_tex_file = "certificates/brochure_template_c.tex";
    $processed_tex_file = $id."processed.tex";
    
    $data = file_get_contents($template_tex_file);
    if(!$data) {
      die_at_noted_problem("can't find tex file: " . $template_tex_file);
    } else{
      $data = str_replace(array(
                '*********THEOREM NAME*********',
                '*********DATA TYPE1*********', 
                '*********DATA TYPE2*********',
                '*********THEORY*********',
                '*********THEOREM*********',
                '*********PROOF*********'
                ), array(
                $thm_title2,
                $datatype1,
                $datatype2,
                $thy_body2,
                $thm_body2,
                $proof_body
      
                ), $data
      );
    } 
  } 
  
  else{
    print "ERROR- this pdf ID doesn't exist: " . $doc_kind . "; need to give either 'certificate', 'theorem', 'theory'.";
  }
  print($data);
}
?>
