<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="format-detection" content="telephone=no" />
        <meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height, target-densitydpi=device-dpi" />
        <link rel="stylesheet" href="css/themes/default/jquery.mobile-1.4.0.min.css">
        <link rel="stylesheet" type='text/css' href="css/jquery-ui-1.8.16.custom.css"/>
        <link rel="stylesheet" type="text/css" href="css/index.css" />
        <title>MyLocalCafe - Admin app</title>
    </head>
    
    <body>
        <div id="home" data-role="page">
                <div class="logo_home">
                    <img src="img/logo_4.png"><br>
                    <h1>MyLocal<span>Café</span></h1><h1>Administation</h1>
                </div>
                   <div class="form menu_home" id="login">
                       <form method="POST" action="login.php">
                           <input type="text" value="" name="username" placeholder="Email">
                           <input type="text" value="" name="password" placeholder="Kodeord">
                           <input type="button" value="Log ind" id='loginButton' onclick="submitForm(this)">
                       </form>
                   </div>                  
        </div>     
        <script src="js/jquery.js"></script>       
        <script src="js/jquery.mobile-1.4.0.js"></script>
        <script type="text/javascript" src="js/jquery-ui.js"></script>
        <script src="http://code.jquery.com/jquery-migrate-1.2.1.js"></script> <!-- migrate plugin for old jQuery-->
        <script type="text/javascript" src="js/general.js"></script>
        <script>
           var url = window.location.search.substring(7);
           if (url === "false") {
               $("#loginButton").before("<div class='WrongPassword'><p>Email eller kodeord er forkert</p></div>");
           }
           if(url === "nocafe"){
               $("#loginButton").before("<div class='WrongPassword'><p>Du har endnu ikke oprettet en café</p><p>Besøg <a id='a_link' data-ajax='false' href='http://www.mylocalcafe.dk/login-page#LogInd'>mylocalcafe.dk</a> og opret din café</p></div>");             
           }
           if(url === "Account_locked"){
               $("#loginButton").before("<div class='WrongPassword'><p>Kontoen er blevet låst i 2 timer pga. for mange log ind forsøg</p></div>");               
          }
        </script>
    </body>
</html>
