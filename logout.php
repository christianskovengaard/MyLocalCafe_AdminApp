<?php
    session_start();
    $_SESSION = array();
    session_destroy();
    setcookie("PHPSESSID", "", -3600, "/");
    
    setcookie('sec_session_id', '', time()-3600,'/', '', 0, 0);
?>
<html>
<head></head>
<body>
    <script src="js/jquery.js"></script>
    <script>$(location).attr('href','index');</script>
</body>
</html