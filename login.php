<?php

if(isset($_POST['username']) && isset($_POST['password'])) {
    //echo $_POST['username']. ' ' .$_POST['password'];
    require_once 'Controllers/UserController.php';
    $oUserController = new UserController();
    $loggedIn = $oUserController->LogInUser($_POST['username'], $_POST['password']);
   if($loggedIn['result'] == 'true'){
        header('location: admin'); 
        exit;    
   }else if($loggedIn['result'] == 'Account locked'){
       header("location: index?login=Account_locked#LogInd");
       exit;
   }else if($loggedIn['result'] == 'false'){
       header("location: index?login=false#LogInd");
       exit;
   }else if($loggedIn['result'] == 'nocafe'){
       header("location: index?login=nocafe#LogInd");
       exit;
   }
   
}
else {
    header("location: index?login=false");
    exit;    
}
?>