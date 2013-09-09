<?
restrict_is_admin();

$here_link = "?go=admin&s=setup";

// prefs for database setup
$db_server = set_default($_POST['db_server'], "localhost");
$db_type = set_default($_POST['db_type'], "psql");
$db_user = set_default($_POST['db_user'], "username");
$db = set_default($_POST['db'], $db_user);
$db_pass = set_default($_POST['db_pass'], "");
$db_tables_prefix = set_default($_POST['db_tables_prefix'], "vtp_");
$safe_sql_dir = set_default($_POST['safe_sql_dir'], $safe_sql_dir);
$admin_email = set_default($_POST['admin_email'], $admin_email);
$change_admin_pass = set_default($_POST['change_admin_pass'], $change_admin_pass);
$admin_pass1 = set_default($_POST['admin_pass1'], null);
$admin_pass2 = set_default($_POST['admin_pass2'], null);
$submit = set_default($_POST['submit'], null);
$sqlprefs_fullfilename = $safe_sql_dir . "/" . $sqlprefs_filename;
// act can be overridden by index.php saying something is wrong. 
$act = set_default($act, $_REQUEST['act']);
?> 
  <div class="display-settings">
  <h3> Global Settings </h3>
<?
if($act == 'set_prefs'){
  if($change_admin_pass and ($admin_pass1 == null or $admin_pass1 != $admin_pass2)) {
    $act = 'change_prefs';
    ?><p>
    <span class="warning">Passwords are empty or did not match.</span>
    </p><?
  } 
  if($submit != null and $safe_sql_dir == null){
    $act = 'change_prefs';
    ?><p><span class="warning">No global prefs were given</span>, we need to have at least the safe sql-directory set, try again?
    </p><?
  }
  // if we are still hapyy to change prefs...
  if($act == 'set_prefs'){
    if($change_admin_pass) {
      $admin_pass = MD5($admin_pass1);
    }
    $prefs_file = globalprefs_of_phpfile('prefs/prefs.php.template');
    // file_get_contents('prefs/prefs.php.template');
    // eval("\$prefs_file = \"$prefs_file\";");
    if($Handle = fopen($prefs_filename, 'w')){
      fwrite($Handle, $prefs_file);
      fclose($Handle); 
      load_global_prefs($prefs_filename);
      ?><p>
      <span class="good">The SQL location has been written</span> to: '<code><? print $prefs_filename; ?></code>'.
      </p><?
    } else {
      $act = "change_prefs";
      ?><p><span class="warning">Cannot write global prefs to file</span>: '<code><? print $prefs_filename; ?></code>'. PHP needs to have write access at least to this file. You probably need to make the directory or file writeable by your php on your webserver. Try again?
      </p><?
    }
  }
}

if(! $prefs_loaded) { $act = "change_prefs"; $change_admin_pass = true; }
// user has requested, or been referred to change their global preferences
if($act == "change_prefs"){
  ?>
  <p>
  A safe file system location on the web-server is needed to save the sql database preferences (this needs to be readable by php, but not by other users or through the webserver):
  <p>
  <FORM action="?go=admin&act=set_prefs" method="post">
  <? print_required_field($safe_sql_dir, "SQL preferences location"); ?>: <br>
  <input name="safe_sql_dir" type="text" value="<? print $safe_sql_dir; ?>" size="60" /><br>
  <? print_required_field($admin_email, "Admin email address"); ?>: <br>
  <input name="admin_email" type="text" value="<? print $admin_email; ?>" size="60" />
  <br><br>Change admin password: <input name="change_admin_pass" type="checkbox" <? if($change_admin_pass) { print "checked"; } ?>/><br>
  <? print_required_field((! $change_admin_pass) or $admin_pass1 != null, "Password for admin access"); ?>: <br>
  <input name="admin_pass1" type="password" value="" size="30" />
  <br>re-type password: <br>
  <input name="admin_pass2" type="password" value="" size="30" />
  <br><br>
  <input class="greenbutton" name="submit" type="submit" value="Save and continue" /> &nbsp;&nbsp; <a class="redbutton" href="?go=admin">Cancel</a> 
  </FORM>
  </p>
  <?  
} else {
  ?>
  <p>The location of your SQL preferences is: <code><? print $safe_sql_dir; ?></code><br>
  The admin pass is: <code><? 
  // print $admin_pass; 
  print "[hidden]";
  ?></code><br>
  The admin email is: <code><? print $admin_email; ?></code>
  </p>
  <p><a href="?go=admin&act=change_prefs">Change global preferences</a></p>
  <? 
}
?></div><?

if($act != "change_prefs") { // we have global settings!
  
  ?>
    <div class="display-settings"><h3> SQL Settings </h3><?
  // SQL Prefs
  if($act == "set_sqlprefs"){
    // print "<hr>test<br>";
    ob_start();
    include('prefs/sql_prefs.php.template');
    $sqlprefs_file = ob_get_contents();
    ob_end_clean();
    // print "<hr><pre>$sqlprefs_file</pre></hr>";
    // eval("\$sqlprefs_file = \"$sqlprefs_file\";");
    if($Handle = fopen($sqlprefs_fullfilename, 'w')){
      fwrite($Handle, $sqlprefs_file);
      fclose($Handle); 
      ?><p class="good">
      The SQL preferences has been written to <code><? print $sqlprefs_fullfilename; ?></code>
      </p><?
    } else {
      $act = "change_sqlprefs";
      ?><p> <span class="warning">Cannot write the location of the SQL preferences file</span>: '<code><? print $sqlprefs_fullfilename; ?></code>'. PHP needs to have write access at least to the install directory! You probably just need to make the "prefs" subdirectory writeable for php/apache. Try again?
      </p><?
    }
  }
  
  // check if sql prefs exist and load them...
  if(file_exists($sqlprefs_fullfilename)) {
    if(load_sql_prefs($sqlprefs_fullfilename)) {
      $load_sql_prefs_state = "loaded";
      ?><p>Your SQL prefs have been loaded from: <code><?
      print($sqlprefs_fullfilename);
      ?></code></p><?
      
      $connected_to_sql = sql_try_connect();
      if(! $connected_to_sql){
        $act = "change_sqlprefs";
        ?><p class="warning">Something went wrong! could not connect to the database. Fix the settings and try again?
        </p><?
        
      }
      
    } else {
      $connected_to_sql = false;
      $load_sql_prefs_state = "cannot load";
      ?><p><span class="warning">Your SQL prefs exist, but for some reason they cannot be loaded.</span> Check file permisions for: <?
      print($sqlprefs_fullfilename);
      ?> ?</p><?
    }
  } elseif($act != "change_sqlprefs") {
    $connected_to_sql = false;
    $load_sql_prefs_state = "no file";
    $act = "change_sqlprefs";
    ?><p><span class="warning">Your SQL database preferences do not yet exist.</span> This is either the first time you are setting this up, or you need to check file permisions for: <code><?
    print($sqlprefs_fullfilename);
    ?></code></p><?
  }
  
  if(isset($safe_sql_dir) and (! is_writeable($safe_sql_dir))){
    ?>
    <p><span class="warning">The directory where your SQL preferences need to be saved is not writeable</span>:<br>
    <code>safe_sql_dir = <? print $safe_sql_dir; ?> </code>
    <br>
    This may need to be corrected on your server, but you may have mistyped the directory, in which case you should change the SQL preferences location.
    </p>
    <? 
    $seqprefs_writeable = false;
  } else {
    $seqprefs_writeable = true;
  }
  
  //print "can connect: $connected_to_sql ; loadstate: $load_sql_prefs_state ";
  if($load_sql_prefs_state == "loaded" and $connected_to_sql){
    ?>
    <p>
    <span class="good">Database connection works</span>, the settings are: 
    <p/><p><code>
    Database server: <? print $db_server; ?> <br>
    SQL server kind: <? print $db_type; ?><br>
    Database name: <? print $db; ?><br>
    Database login: <? print $db_user; ?><br>
    Database password: <? 
    // print $db_pass; 
    print "[hidden]";
    ?><br>
    SQL table prefix: <? print $db_tables_prefix; ?>
    </code></p>
    <p>
    You can <a href="?go=admin&act=change_sqlprefs">change sql preferences</a> if you want to connect to a different database/server.</p>
    <?    
  } elseif($load_sql_prefs_state == "loaded" and (! $connected_to_sql)) {
    ?>
    <p class="warning">
    There is a problem with your database connection settings, I was unable to connect to the database. You probably need to fix the settings bellow.</p><?
    $act = "change_sqlprefs";
  } elseif(!connected_to_sql) {
    $act = "change_sqlprefs";
  }
  
  if($act == "change_sqlprefs"){
    ?>
    <p>Database connection settings:</p>
    <p>
    <FORM action="?go=admin&act=set_sqlprefs" method="post">
    Server address: <br><input name="db_server" type="text" value="<? print "$db_server"; ?>" size="40"> 
    <BR><br>
    What kind of SQL server is being used: <br>
    <INPUT type="radio" name="db_type" value="mysql" <? if($db_type == "mysql"){ print "checked";} ?>> MySql &nbsp;&nbsp;&nbsp;
    <INPUT type="radio" name="db_type" value="psql" <? if($db_type == "psql"){ print "checked";} ?>> Postgres
    <BR><br>
    Database name: <br><input name="db" type="text" value="<? print "$db"; ?>" size="40">   <BR><br>
    Login for database: <br><input name="db_user" type="text" value="<? print "$db_user"; ?>" size="40"> 
    <BR><br>
    Password for database: <br><input name="db_pass" type="password" value="<? // print "$db_pass"; 
    ?>" size="40"> 
    <BR><BR>
    Database table prefix: <br><input name="db_tables_prefix" type="text" value="<? print "$db_tables_prefix"; ?>" size="40">  
    <br><br>
    <input class="greenbutton" name="submit" type="submit" value="Save and continue"> &nbsp;&nbsp; <a class="redbutton" href="?go=admin">Cancel</a> 
    </form>
    </p>
    <?
  } // setting sql prefs
  // sql display settings box 
  ?></div>
  
  <? 
  if($act == "reset_sql_tables" and 
    set_default($_POST['really_reset'], null) == 'yes')
  { 
    $reset_tables = preg_split("/[\s\n\r,]+/",$_REQUEST['resetlist']);
    reset_sql_tables($reset_tables); 
    if(!(array_search($db_users, $reset_tables) === false)) {
      make_new_user($admin_firstname,$admin_lastname,
        $admin_email,$admin_pass,'admin');
    }
    $reset_sql_tables = 'reset'; 
  }
  ?>
  <div class="display-settings">
  <h3> SQL Tables </h3>
  You need to have the following tables: <? print join(", ", $db_tables); ?>
  <? 
  if($load_sql_prefs_state == "loaded" and $connected_to_sql){
    $res = sql_check_tables($db_tables);
    if(sql_has_tables($db_tables)){
      ?><p><span class="good">Database has the following expected tables:</span><br><code>
      <? print join(', ', $res['available']); ?></code></p><?
    } else {
      ?><p><span class="warning">Database is missing tables:</span> <code>
      <? print join(', ', $res['missing']); ?>
      </code></p><?
    }
  }

  if($reset_sql_tables){
    ?><p><span class="good"><code>
    <?print $_REQUEST['resetlist']; 
    ?></code>
    table(s) have been reset!</span></p><? 
  } elseif($act == "reset_sql_tables") {
    ?><p><span class="warning">Some tables have not been reset.</span> If you really want to reset them, type "yes".  </p>
    <?
  } ?>
  
  <p><FORM action="?go=admin&act=reset_sql_tables" method="post">
  Enter the names of the tables you want to reset: <br>
  <TEXTAREA NAME="resetlist" ROWS="3" COLS="80"><? 
    print $_REQUEST['resetlist']; ?></TEXTAREA>
  <br>
  If you really want to reset the sql-tables, which <b>deletes all data in the tables</b>, type in "yes": <input name="really_reset" type="text" value="no!" size="10"> 
  <input class="danger-button" name="submit" type="submit" value="Reset given tables">
  </form>
  </p>
  </div><?
} // act != change_prefs ==> have global settings
?>
