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
        <link rel="stylesheet" type='text/css' href="css/jquery-ui-1.8.16.custom.css"/>
        <link rel="stylesheet" type="text/css" href="css/index.css" />
        <title>MyLocalCafe - Admin app</title>
    </head>
    
    <body>
        <div id="home">
            <div class="headermenu">
                <div class="menuBtn">
                    <input type='button' id='menuBtn' onclick="changePage('admin')" value='Menu'/>
                </div>
                <form class='logout' method="POST" action="logout.php">
                    <input id='logoutBtn' type="submit" value="Log ud"">
                </form>
            </div>
            <div class="logo_home">
                <h1>MyLocal<span>Café</span></h1><h1 id="cafename"></h1>
            </div>
            
            <div class="menu_home">
                    <h2>Ny besked</h2>
                    <form class="newmessage form">
                        <input type="text" placeholder="Overskrift" id="sMessageHeadline">
                        <input type="text" placeholder="Besked" id="sMessengerTextarea">
                        <input type="text" class="datepicker" name="datefrom" placeholder="dato fra" id="dMessageStart">
                        <input type="text" class="datepicker" name="dateto" placeholder="dato til" id="dMessageEnd">
                        <input type="file" id="captureimage" accept="image/*;capture=camera"> <!-- This input is hidden -->
                        <input type="button" onclick="CaptureImage();" value="Tag billede">
                        <img id="image_preview" src='' data-urlid='0'>
                        <input type="button" onclick="SaveMessage();" value="Send besked">
                    </form>
                <div class="logo_home">
                    <h2>Sidst sendte besked:</h2>
                </div>
                <div id="currentMessages" class="oldMessenge"></div>
                <div class="logo_home">
                    <h2>Gamle beskeder:</h2>
                </div>
                <div id="oldMessages" class="oldMessenge"></div>
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
            $('.datepicker').datepicker();
            $("#dMessageStart").val($.datepicker.formatDate('dd-mm-yy', new Date()));
            GetMessages();           
            $('body').css('-webkit-overflow-scrolling', 'touch');
            
            //Fix for hiding the native keyboard on phone browser
            $(".datepicker").click(function() {
                $(".datepicker").blur(); // UNFOCUS THE INPUT                          
            });
            //Fix for hiding jQueryMobile div on catureimage file input
            $('#captureimage').parent('div').css('display','none');
        });
        </script>
    </body>
</html>
<?php  } else {
    header("location: index");
}
?>