fun string_of_file filename = 
    let val instream = TextIO.openIn filename
        fun reader str = 
            (case TextIO.inputLine instream of NONE => str
                | SOME s => reader (str ^ s))
        val str = reader "";
        val _ = TextIO.closeIn instream;
    in str end;

fun string_of_command (cmd,args) = String.concat (separate " " ([cmd] @ args));

fun prova a b = a + b; 

fun msg_of_exit_status exit_status = 
    let 
      val exit_status_str = 
        (case Unix.fromStatus exit_status of 
          Unix.W_EXITSTATUS w => "Error: " ^ (Word8.toString w) 
          ^ "(" ^ (Int.toString (Word8.toInt w)) ^ ")"  
         | Unix.W_EXITED => "seems ok."
         | _ => "seems strange.");
    in "Exit status: " ^ exit_status_str end;

(* *)
exception error_exp of string;

fun run_command cmd = 
    let 
      val exit_status = OS.Process.system (string_of_command cmd);
    in 
      if OS.Process.isSuccess exit_status then () 
      else raise error_exp (msg_of_exit_status exit_status)
    end;

val msg = ref "";
fun update_message () = 
    (DOM.setInnerHTML state_elem (!msg));
fun add_to_message s = 
    (msg := (!msg) ^ s; update_message ());
fun empty_message () = 
    (msg := ""; update_message ());

fun list_directory ()=
let 
 val pdf_file_dir = pwd();
 in pdf_file_dir end;
 
    
fun no_white_spaces x = if (x = #" ") then "\\ " else (Char.toString x);

fun copy_cert_broucher dockind pdf_file_dir= 
  if ((dockind = "brouchure") orelse (dockind = "brouchure_c"))
   then 
     (let
       val cert_file = pdf_file_dir ^ "/certificate.pdf";
       val brouch_file = pdf_file_dir ^ "/brouchure.pdf";
       
       val cmd = ("/bin/cp", [cert_file, brouch_file]); 
       val () = add_to_message ("<p>Copy the certificate pdf <br><code>" 
         ^ (string_of_command cmd) ^ "</code>");
         val () = run_command cmd;
       in () end)
       else ();
      
       
 fun dowload_files  cert_id dockind= 
  if ((dockind = "brouchure") orelse (dockind = "brouchure_c"))
   then 
     (let
       val cert_image_file = "http://www.theorymine.co.uk/certificates/" ^  cert_id ^ "/certificate_image.jpg";
       val php_file = "/Users/flaminiacavallo/Documents/VTheoremProving/websites/theorymine.co.uk/certificates/php_wget.php";
       val cmd = ("/usr/bin/php", [php_file,cert_image_file ]); 
       val () = add_to_message ("<p>Downloading the certificate image <br><code>" 
         ^ (string_of_command cmd) ^ "</code>");
         val () = run_command cmd;
       in () end)
    else if (dockind = "certificate_image")
     then 
       (let
       val cert_file = "http://www.theorymine.co.uk/certificates/" ^  cert_id ^ "/certificate.pdf";
       val php_file = "/Users/flaminiacavallo/Documents/VTheoremProving/websites/theorymine.co.uk/certificates/php_wget.php";
       val cmd = ("/usr/bin/php", [php_file,cert_file ]); 
       val () = add_to_message ("<p>Downloading the certificate image <br><code>" 
         ^ (string_of_command cmd) ^ "</code>");
         val () = run_command cmd;
       in () end)
     
       
    else ();
       
fun make_images dockind pdf_file_dir= 
    if (dockind = "theorem")
    then 
      (let
         val pdf_filename = pdf_file_dir ^ "/" ^ "certificate.pdf";
         val img_filename = pdf_file_dir ^ "/" ^ "thm.jpg";
         val cmd = ("/usr/local/bin/convert", ["-gravity", "South", "-chop", "0x4000", "-density", "400", pdf_filename, img_filename]);
         val () = add_to_message ("<p>Running convert using command <br><code>" 
         ^ (string_of_command cmd) ^ "</code>");
         val () = run_command cmd;
       in () end)
     else if (dockind = "theory")
     then 
      (let
         val pdf_filename = pdf_file_dir ^ "/" ^ "certificate.pdf";
         val img_filename = pdf_file_dir ^ "/" ^ "thy.jpg";
         val cmd = ("/usr/local/bin/convert", ["-gravity", "South", "-chop", "0x1000", "-density", "400", pdf_filename, img_filename]);
         val () = add_to_message ("<p>Running convert using command <br><code>" 
         ^ (string_of_command cmd) ^ "</code>");
         val () = run_command cmd;
       in () end)
     else if (dockind = "certificate_image")
     then 
      (let
         val pdf_filename = pdf_file_dir ^ "/" ^ "certificate.pdf";
         val img_filename = pdf_file_dir ^ "/" ^ "certificate_image.jpg";
         val cmd = ("/usr/local/bin/convert", ["-density", "400", pdf_filename, img_filename]);
         val () = add_to_message ("<p>Running convert using command <br><code>" 
         ^ (string_of_command cmd) ^ "</code>");
         val () = run_command cmd;
       in () end)
       else ();
       
fun run_latex upload_url_prefix cert_id dockind latex_codeloc pdf_file_dir= 
 if (not(dockind = "certificate_image"))
 then 
    (let 
     val cmd = ("/usr/bin/php", [latex_codeloc,
                                     cert_id,
                                     dockind,
                                     upload_url_prefix ^ "?go=latex",
                                     pdf_file_dir,
                                     "vtp:ca3nyH9ewgHR"
                                    ]);
        val () = add_to_message ("<p> bbb Downloading and compiling latex with command... <br><code>" ^ (string_of_command cmd) ^ "</code><p>\n");
        val () = run_command cmd;
        val () = add_to_message ("DONE! \n");
        (* copy images directory needed for latex *)
        val latex_images_loc = (DOM.getValue codeloc_elem) ^ "/images";
        val cmd = ("/bin/cp",["-r", latex_images_loc, pdf_file_dir ^ "/"]);
        val () = add_to_message ("<p> bbb Copying files needed for latex... <code>"
           ^ (string_of_command cmd) ^ "</code></p>\n");
        val () = run_command cmd;
        val () = add_to_message ("DONE! \n");  
        (* run latex *)
        val cert_filename = pdf_file_dir ^ "/" ^ "certificate.tex";
        val cmd = ("/usr/texbin/pdflatex",["-interaction",  "nonstopmode", "-output-format",  "pdf" ,cert_filename]);
        val () = add_to_message ("<p>Downloaded and compiled Latex in: <code>" 
           ^ pdf_file_dir ^ "</code><br>Running pdflatex... <br>in: <code>"^ (pwd()) ^"</code><br> using command: <br><code>"
           ^ (string_of_command cmd) ^ "</code></p>\n");
        val () = run_command cmd;
        val () = run_command cmd;
        val () = run_command cmd;
        (* completed successfully *)
        val () = add_to_message ("<p>Latex completed for: <code>" ^ cert_filename ^ "</code></p>");
        in () end )
       else (); 
    
fun make_certificate upload_url_prefix cert_id = 
  let 
    val () = empty_message ();
    val latex_codeloc = (DOM.getValue codeloc_elem) ^ "/run_get_latex.php";
    val _ = OS.FileSys.access (latex_codeloc, [OS.FileSys.A_READ])
            orelse raise error_exp ("can't read code location: <code>" ^ latex_codeloc ^ "/<code>");     
    val dockind = DOM.getValue doc_kind_elem;
    val pdf_file_dir = pwd();
    val pdf_file_dir =  String.translate no_white_spaces pdf_file_dir;
    val () = add_to_message ("WE ARE IN THIS DIRECTORY:" ^ pdf_file_dir);
    val () = dowload_files cert_id dockind;
    val () = run_latex upload_url_prefix cert_id dockind latex_codeloc pdf_file_dir;

    
    (*val pdf_file_dir = "/Users/flaminiacavallo/TM_live_certificates";*)

    (* run_cartificate_latex downloads the latex code from the server for this 
       particular id and suns latex on it and stores it locally *)
   
    val () = make_images dockind pdf_file_dir;
    val () = copy_cert_broucher dockind pdf_file_dir;

(*    val process = Unix.execute (cmd,args);
    val exit_status = Unix.reap process; *)    
  in () end handle error_exp s => 
    add_to_message ("<p class='warning'>Failed: " ^ s);

