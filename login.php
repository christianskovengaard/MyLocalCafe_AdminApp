<html>
<head></head>
<body>
    <script src="js/jquery.js"></script>
<?php

if(isset($_POST['username']) && isset($_POST['password'])) {
    //echo $_POST['username']. ' ' .$_POST['password'];
    require_once 'Controllers/UserController.php';
    $oUserController = new UserController();
    $loggedIn = $oUserController->LogInUser($_POST['username'], $_POST['password']);
   if($loggedIn['result'] == 'true'){
        //header('location: admin'); 
        //exit;
        ?>       
        <script>
            $(location).attr('href','admin');
        </script>            
        <?php       
   }else if($loggedIn['result'] == 'Account locked'){
       //header("location: index?login=Account_locked#LogInd");
       ?>       
        <script>
            $(location).attr('href','index?login=Account_locked#LogInd');
        </script>            
        <?php 
   }else if($loggedIn['result'] == 'false'){
       //header("location: index?login=false#LogInd");
       ?>       
        <script>
            $(location).attr('href','index?login=false#LogInd');
        </script>            
        <?php 
   }else if($loggedIn['result'] == 'nocafe'){
       //header("location: index?login=nocafe#LogInd");
       ?>       
        <script>
            $(location).attr('href','index?login=nocafe#LogInd');
        </script>            
        <?php 
   }
   
}
else {
    //header("location: index?login=false");
    //exit;
    ?>       
    <script>
        $(location).attr('href','index?login=false');
    </script>            
    <?php     
}
?>
</body>
<html>