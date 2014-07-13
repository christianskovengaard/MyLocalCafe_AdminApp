<?php
//Check if user is logged in
require 'Controllers/SecurityController.php';
$oSecurityController = new SecurityController();
$oSecurityController->sec_session_start(); // Our custom secure way of starting a php session.
if($oSecurityController->login_check() == true) { ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="format-detection" content="telephone=no" />
        <meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height, target-densitydpi=device-dpi" />
        <link rel="stylesheet" href="css/themes/default/jquery.mobile-1.4.0.min.css">
        <link rel="stylesheet" type="text/css" href="css/index.css" />
        <title>MyLocalCafe - Admin app</title>
    </head>
    
    <body>
        <div id="home" data-role="page">
            <div class="logo_home">
                <img src="img/logo_4.png"><br>
                <h1>MyLocal<span>Café</span></h1><h1 id="cafename"></h1>
            </div>
            <div class="menu_home">
                <a href="" onclick="changePage('messages')">Beskeder</a>
            </div>
            <div class="logout">
                <form method="POST" action="logout.php">
                    <input type="submit" value="Log ud">
                </form>
            </div>
        </div>     
        <script src="js/jquery.js"></script>
        <script src="js/jquery.mobile-1.4.0.js"></script>
        <script src="http://code.jquery.com/jquery-migrate-1.2.1.js"></script> <!-- migrate plugin for old jQuery-->
        <script type="text/javascript" src="js/general.js"></script>
        <script type="text/javascript">
        $(document).ready(function() {
            GetRestuarentInfo();
        });       
        function changePage(page){
            $(location).attr('href',page);
        }
        
        </script>
    </body>
</html>
<?php  } else {
    header("location: index");
}
?>