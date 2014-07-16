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
        <link rel="stylesheet" href="css/themes/default/jquery.mobile-1.4.0.min.css"/>    
        <link rel="stylesheet" type='text/css' href="css/jquery-ui-1.8.16.custom.css"/>
        <link rel="stylesheet" type="text/css" href="css/index.css" />
        <title>MyLocalCafe - Admin app</title>
    </head>
    
    <body>
        <div id="home" data-role="page">
            <div class="headermenu">
                <div>
                    <input type='button' id='menuBtn' onclick="changePage('admin')" value='Menu'/>
                </div>
                <form class='logout' method="POST" action="logout.php">
                    <input id='logoutBtn' type="button" value="Log ud" onclick="submitForm(this);">
                </form>
            </div>
            <div class="logo_home">
                <!--<img src="img/logo_4.png"><br>-->
                <h1>MyLocal<span>Café</span></h1><h1 id="cafename"></h1>
            </div>
            <div class="menu_home">
                <h3>Stempelkort</h3>
                
            </div>
        </div>     
        <script src="js/jquery.js"></script>
        <script src="js/jquery.mobile-1.4.0.js"></script>
        <script type="text/javascript" src="js/jquery-ui.js"></script>
        <script type="text/javascript" src="js/jquery-da-calendar.js"></script> <!-- danish jQuery calendar-->
        <script src="http://code.jquery.com/jquery-migrate-1.2.1.js"></script> <!-- migrate plugin for old jQuery-->
        <script type="text/javascript" src="js/general.js"></script>
        <script type="text/javascript">
        $(document).ready(function() {
            GetRestuarentInfo();                    
            $('body').css('-webkit-overflow-scrolling', 'touch');
        });
        </script>
    </body>
</html>
<?php  } else {
    header("location: index");
}
?>