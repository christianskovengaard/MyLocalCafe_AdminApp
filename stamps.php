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
                <div class="menuBtn">
                    <input type='button' id='menuBtn' onclick="changePage('admin')" value='Menu'/>
                </div>
                <form class='logout' method="POST" action="logout.php">
                    <input id='logoutBtn' type="button" value="Log ud" onclick="submitForm(this);">
                </form>
            </div>
            <div class="logo_home">
                <h1>MyLocal<span>Café</span></h1><h1 id="cafename"></h1>
            </div>
            <div class="menu_home">
                <h2>Stempelkort</h2>
                <p>Sådan ser dit stemplkort ud:</p><br>                   
                    <div class='StampEX' id='StampEX'>
                        <h4></h4>
                    </div>
                    <div class='StampWrapper'>
                        <form class="form">
                        <p>Antal stempler på stempelkortet:</p>
                        <input type='text' placeholder="Antal stempler" id="iMaxStamps" maxlength="2">
                        <input type='button' onclick="SaveStampcard();" value='Gem'>
                        <input type="text" placeholder="Stempelkort tekst" id="sStampcardText" >
                        <div id="sStampcardTextExample">Stempelkort tekst...</div>
                        <input type='button' onclick="UpdateStampcardText();" value='Opdater tekst'>
                        <h3>Stempelkort kode: </h3><p id="RedemeCode"></p>
                        <div class='redemecodes'>
                            <input type="text" class='redemecode' id="RedemeCode1" maxlength="1">
                            <input type="text" class='redemecode' id="RedemeCode2" maxlength="1">
                            <input type="text" class='redemecode' id="RedemeCode3" maxlength="1">
                            <input type="text" class='redemecode' id="RedemeCode4" maxlength="1">
                        </div>
                        <input type='button' onclick="UpdateRedemeCode();" value='Sæt stempelkort kode'>
                        </form>
                    </div>
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

            //$('.redemecode').parent('div').css({"width":"12%","float":"left"});
            //$('.redemecode').parent('div:nth-of-type(2), div:nth-of-type(3), div:nth-of-type(4)').css({"margin-left":"1.2%"});
            //$('.redemecode').parent('div').first().css({"margin-left":"24%"});
        });
        </script>
    </body>
</html>
<?php  } else {
    header("location: index");
}
?>