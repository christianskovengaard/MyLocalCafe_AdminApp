<?php

if(isset($_POST['sFunction'])) {
    
    $sFunction = $_POST['sFunction'];

    switch ($sFunction) {
        
        case "GetRestuarentInfo":
            require_once '../Controllers/RestuarentController.php';
            $oRestuarentController = new RestuarentController();
            $result = $oRestuarentController->GetRestuarentInfo();
            $sResult = json_encode($result);
            echo $sResult;
        break;
    
        case "SaveMessage":
            require_once '../Controllers/MessageController.php';
            $oMessageController = new MessageController();
            $result = $oMessageController->SaveMessage();
            $sResult = json_encode($result);
            echo $sResult;
        break;

        

        default:
                $result = '{"sFunction":"'.$sFunction.'","result":"Error - Unknown function"}';
                echo $result;
        break;
    }
}

?>
