<?php

if(isset($_GET['sFunction']))
{    
    $sFunction = $_GET['sFunction'];

    switch ($sFunction)
    {
         case "GetMessages":
            require_once '../Controllers/MessageController.php';
            $oMessageController = new MessageController();
            $result = $oMessageController->GetMessages();
            $sResult = json_encode($result);
            echo $sResult;
         break;
     
     
         case "GetRestuarentInfo":
            require_once '../Controllers/RestuarentController.php';
            $oRestuarentController = new RestuarentController();
            $result = $oRestuarentController->GetRestuarentInfo();
            $sResult = json_encode($result);
            echo $sResult;
        break;
    
    
        case "GetStampcard":
            require_once '../Controllers/StampcardController.php';
            $oStampcard = new StampcardController();
            $result = $oStampcard->GetStampcard();
            $sResult = json_encode($result);
            echo $sResult;
        break;
       
        case "SaveStampcard":
            require_once '../Controllers/StampcardController.php';
            $oStampcard = new StampcardController();
            $result = $oStampcard->SaveStampcard();
            $sResult = json_encode($result);
            echo $sResult;
        break;
        
        case "UpdateRedemeCode":
            require_once '../Controllers/StampcardController.php';
            $oStampcard = new StampcardController();
            $result = $oStampcard->UpdateRedemeCode();
            $sResult = json_encode($result);
            echo $sResult;
        break;
        
        case "UpdateStampcardText":
            require_once '../Controllers/StampcardController.php';
            $oStampcard = new StampcardController();
            $result = $oStampcard->UpdateStampcardText();
            $sResult = json_encode($result);
            echo $sResult;
        break;
    
        default:
                $result = '{"sFunction":"'.$sFunction.'","result":"Error - Unknown function"}';
                echo $result;
        break;
    }
}



if(isset($_POST['sFunction'])) {
    
    $sFunction = $_POST['sFunction'];

    switch ($sFunction) {     
    
        case "SaveMessage":
            require_once '../Controllers/MessageController.php';
            $oMessageController = new MessageController();
            $result = $oMessageController->SaveMessage();
            $sResult = json_encode($result);
            echo $sResult;
        break;
        
        case "UploadImage":
            require_once "../Controllers/ImageController.php";
            $oImageController = new ImageController();
            echo json_encode($oImageController->UploadImage());

        break;
        

        default:
                $result = '{"sFunction":"'.$sFunction.'","result":"Error - Unknown function"}';
                echo $result;
        break;
    }
}

?>
