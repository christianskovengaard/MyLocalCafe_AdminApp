<?php

class MessageController 
{
    private $conPDO;   
    private $oSecurityController;

    public function __construct() {
        
        require_once 'DatabaseController.php';
        $oDatabaseController = new DatabaseController();
        $this->conPDO = $oDatabaseController->ConnectToDatabase();
        
        require_once 'SecurityController.php';
        $this->oSecurityController = new SecurityController();
        
    }
    
    
    //GetMessages
    public function GetMessages()
    {
        
        //Allow all, NOT SAFE
        //header('Access-Control-Allow-Origin: *');  
        
        // Only allow trusted, MUCH more safe
        header('Access-Control-Allow-Origin: mylocalcafe.dk');
        header('Access-Control-Allow-Origin: www.mylocalcafe.dk');
        
         $oMessages = array(
                'sFunction' => 'GetMessages',
                'result' => false,
                'Messages' => ''
            );
        
            //Check if session is started
            if(!isset($_SESSION['sec_session_id']))
            { 
                $this->oSecurityController->sec_session_start();
            }

            //Check if user is logged in
            if($this->oSecurityController->login_check() == true)
            {
                
                //Check if user logged in and get the iRestuarentId
                    $sQuery = $this->conPDO->prepare("SELECT iRestuarentInfoId FROM restuarentinfo                                                     
                                                        INNER JOIN company
                                                        ON company.iCompanyId = restuarentinfo.iFK_iCompanyInfoId
                                                        INNER JOIN users
                                                        ON users.iFK_iCompanyId = company.iCompanyId
                                                        WHERE users.sUsername = :sUsername");
                $sQuery->bindValue(':sUsername', $_SESSION['username']);
                $sQuery->execute();
                $aResult = $sQuery->fetch(PDO::FETCH_ASSOC);
                $iRestuarentInfoId = $aResult['iRestuarentInfoId'];
         
                $sQuery = $this->conPDO->prepare("SELECT * FROM messages WHERE iFK_iRestuarentInfoId = :iRestuarentInfoId ORDER BY dtMessageDate DESC");
                $sQuery->bindValue(":iRestuarentInfoId", $iRestuarentInfoId);
                $sQuery->execute();
                $i = 0;
                while($aResult = $sQuery->fetch(PDO::FETCH_ASSOC)) {

                    $oMessages['Messages'][$i]['sMessageHeadline'] = utf8_encode($aResult['sMessageHeadline']);
                    $oMessages['Messages'][$i]['sMessageBodyText'] = utf8_encode($aResult['sMessageBodyText']);
                    $oMessages['Messages'][$i]['dtMessageDate'] = utf8_encode($aResult['dtMessageDate']);
                    $oMessages['Messages'][$i]['sMessageImage'] = utf8_encode($aResult['sMessageImage']);
                    $i++;
                }

                $oMessages['result'] = true;
                return $oMessages;
            }
        
    }
    
    
    
    //SaveMessage
    public function SaveMessage()
    {
        
        //Allow all, NOT SAFE
        //header('Access-Control-Allow-Origin: *');  
        
        // Only allow trusted, MUCH more safe
        header('Access-Control-Allow-Origin: mylocalcafe.dk');
        header('Access-Control-Allow-Origin: www.mylocalcafe.dk');
        
        
        $oMessage = array(
                'sFunction' => 'SaveMessage',
                'result' => false
            );
        
            //Check if session is started
            if(!isset($_SESSION['sec_session_id']))
            { 
                $this->oSecurityController->sec_session_start();
            }

            //Check if user is logged in
            if($this->oSecurityController->login_check() == true)
            {
        
                if(isset($_POST['sJSON'])) {

                    $aJSONMessage = json_decode($_POST['sJSON']);

                    //Check if user logged in and get the iRestuarentId
                    $sQuery = $this->conPDO->prepare("SELECT iRestuarentInfoId FROM restuarentinfo                                                     
                                                        INNER JOIN company
                                                        ON company.iCompanyId = restuarentinfo.iFK_iCompanyInfoId
                                                        INNER JOIN users
                                                        ON users.iFK_iCompanyId = company.iCompanyId
                                                        WHERE users.sUsername = :sUsername");
                    $sQuery->bindValue(':sUsername', $_SESSION['username']);
                    $sQuery->execute();
                    $aResult = $sQuery->fetch(PDO::FETCH_ASSOC);
                    $iRestuarentInfoId = $aResult['iRestuarentInfoId'];
                                    
                    //Reverse date to fit database format
                    $dMessageStart = date('Y-m-d', strtotime(urldecode($aJSONMessage->dMessageStart)));
                    $dMessageEnd = date('Y-m-d', strtotime( urldecode($aJSONMessage->dMessageEnd)));

                    $image = false;
                    $sQuery = $this->conPDO->prepare("SELECT * FROM images WHERE iImageId = :imageId AND iFK_iRestuarentInfoId = :resturentid");
                    $sQuery->bindValue(':imageId', $aJSONMessage->iMessageImageId);
                    $sQuery->bindValue(':resturentid', $iRestuarentInfoId);
                    $sQuery->execute();
                    $rows = $sQuery->rowCount();
                    if ($rows == 1) {
                        $aResult = $sQuery->fetch(PDO::FETCH_ASSOC);
                        
                        //Change this folder location for the online version OR offline version
                        $imagefolder_location_ONLINE = '../../';
                        $imagefolder_location_OFFLINE = '../../MyLocalMenu/';
                        
                        if (file_exists($imagefolder_location_ONLINE."img_user/" . $aResult['sImageName'])) {
                            $image = $aResult['sImageName'];
                        }
                    }
                    
                    //Save message
                    $sQuery = $this->conPDO->prepare("INSERT INTO messages (sMessageHeadline,sMessageBodyText,dtMessageDate,dMessageDateStart,dMessageDateEnd,sMessageImage,iFK_iRestuarentInfoId) VALUES (:sMessageHeadline,:sMessageBodyText,NOW(),:dMessageDateStart,:dMessageDateEnd,:sMessageImage,:iFK_iRestuarentInfoId)");
                    $sQuery->bindValue(":sMessageHeadline", utf8_decode(urldecode($aJSONMessage->sMessageHeadnline)));
                    $sQuery->bindValue(":sMessageBodyText", utf8_decode(urldecode($aJSONMessage->sMessageBodyText)));
                    $sQuery->bindValue(":dMessageDateStart", $dMessageStart);
                    $sQuery->bindValue(":dMessageDateEnd", $dMessageEnd);
                    $sQuery->bindValue(":iFK_iRestuarentInfoId", $iRestuarentInfoId);

                    if ($image !== false) {

                        require_once "../Classes/PhpImageMagicianClass.php";
                        
                        //Use image folder in MyLocalMenu project
                        $oImageL = new imageLib($imagefolder_location_ONLINE."img_user/" . $image);

                        $oMessageFinishImageAspect = (object)Array(
                            "max" => 1.42857142857,
                            "min" => 0.42857142857
                        );

                        $iNeturalAspect = $oImageL->getHeight() / $oImageL->getWidth();

                        if ($iNeturalAspect < $oMessageFinishImageAspect->min) {
                            $oImageL->resizeImage(700, 300, 4);
                        } else if ($iNeturalAspect > $oMessageFinishImageAspect->max) {
                            $oImageL->resizeImage(700, 1000, 4);
                        } else {
                            $oImageL->resizeImage(700, 700 * $iNeturalAspect, 4);
                        }
                        
                        //Use image folder in MyLocalMenu project
                        $oImageL->saveImage($imagefolder_location_ONLINE."imgmsg_sendt/" . $image);

                        $sQuery->bindParam(":sMessageImage", $image);
                    } else {
                        $blank = "";
                        $sQuery->bindParam(":sMessageImage", $blank);
                    }

                    $sQuery->execute();

                    $oMessage['result'] = true;
                }
            }
        
        
        return $oMessage;
    }
    
    public function __destruct() {
        ;
    }
}

?>
