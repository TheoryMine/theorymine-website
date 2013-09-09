<?php

$here_link = "?go=logout";

new_msg($thislang['logout']);

force_logout();

include 'pages/overview.php';

?>
