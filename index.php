<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="format-detection" content="telephone=no" />
        <meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height, target-densitydpi=device-dpi" />
        

        <link rel="stylesheet" type="text/css" href="css/index.css" />
        <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
        <link rel="stylesheet" href="css/idangerous.swiper.css">

        <title>MyLocalCafe - AdminApp</title>
    </head>
    
    <body>

        <div id="home">
                <div class="logo_home">
                    <img src="img/logo_4.png"><br>
                    <h1>MyLocal<span>Café</span></h1>
                    <h1>Admin</h1>
                </div>
            <div class="form" id="login">
               <form class="login_form" method="POST" action="login.php">
                   <input type="text" value="" name="username" placeholder="Email">
                   <input type="password" value="" name="password" placeholder="Kodeord">
                   <input type="submit" value="Log ind" class="button">
               </form>
               <?php
                if(isset($_GET['login'])){
                    if($_GET['login'] == 'false'){
                        echo '<p class="fail_login">Brugernavn eller kodeord er forkert</p>';               
                    }
                }
               ?>
           </div>  
        </div>
        <script type="text/javascript" src="js/general.js"></script>
        <script src="js/jquery.js"></script>
        <script type="text/javascript" src="js/jquery.velocity.min.js"></script>
        <script type="text/javascript" src="js/velocity.ui.js"></script>     
        <script type="text/javascript" src="js/idangerous.swiper-2.1.min.js"></script>
        <script type="text/javascript">
        if (screen.width >= 720) {
            //window.location = "../mylocalmenu/index.php";
        }
        </script>
    </body>
</html>

