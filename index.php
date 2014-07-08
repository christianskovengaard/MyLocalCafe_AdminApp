<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="format-detection" content="telephone=no" />
        <meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height, target-densitydpi=device-dpi" />
        <link rel="stylesheet" href="css/themes/default/jquery.mobile-1.4.0.min.css">
        <link rel="stylesheet" type="text/css" href="css/index.css" />
        <title>MyLocalCafe</title>
    </head>
    
    <body>
        <div id="home" data-role="page">
                <div class="logo_home">
                    <img src="img/logo_4.png"><br>
                    <h1>MyLocal<span>Café</span></h1><h1>Administation</h1>
                </div>
                   <div id="Offline" class="Offline">
                       <h2>Du er offline</h2>
                       <h3>Du skal være online for at bruge appen</h3>
                   </div>
                   <div class="form">
                       <form method="POST" action="login.php">
                           <input type="text" value="" placeholder="Email">
                           <input type="text" value="" placeholder="Kodeord">
                           <input type="submit" value="Log ind" class="ui-btn">
                       </form>
                   </div>                  
        </div>     
        <script src="js/jquery.js"></script>
        <script src="js/jquery.mobile-1.4.0.js"></script>
        <script src="http://code.jquery.com/jquery-migrate-1.2.1.js"></script> <!-- migrate plugin for old jQuery-->
        <script type="text/javascript" src="js/general.js"></script>
    </body>
</html>
