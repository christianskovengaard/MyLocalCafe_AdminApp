<?php


class StampcardController
{
    
    private $conPDO;
    private $oSecurityController;
    
    public function __construct() {
        require_once 'DatabaseController.php';
        $oDatabase = new DatabaseController();
        $this->conPDO = $oDatabase->ConnectToDatabase();
        
        require_once 'SecurityController.php';
        $this->oSecurityController = new SecurityController();
    }
    
    
    public function SaveStampcard() {
        
        $oStampcard = array(
                'sFunction' => 'SaveStampcard',
                'result' => false
            );
        
        if(isset($_GET['sJSONStampcard']))
        {
            $sJSONStampcard = $_GET['sJSONStampcard'];
            $oJSONStampcard = json_decode($sJSONStampcard);
        
            //Check if session is started
            if(!isset($_SESSION['sec_session_id']))
            { 
                $this->oSecurityController->sec_session_start();
            }

            //Check if user is logged in
            if($this->oSecurityController->login_check() == true)
            {
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
                
                //Check if stamcard all ready exsists
                $sQuery = $this->conPDO->prepare("SELECT COUNT(*) AS number FROM stampcard WHERE iFK_iRestuarentInfoId = :iFK_iRestuarentInfoId");
                $sQuery->bindValue(':iFK_iRestuarentInfoId', $iRestuarentInfoId);
                $sQuery->execute();
                $aResult = $sQuery->fetch(PDO::FETCH_ASSOC);
                
                if($aResult['number'] == 1) {
                    $sQuery = $this->conPDO->prepare("UPDATE stampcard SET iStampcardMaxStamps = :iStampcardMaxStamps WHERE iFK_iRestuarentInfoId = :iFK_iRestuarentInfoId");
                } else {
                    //Create new stampcard
                    $sQuery = $this->conPDO->prepare("INSERT INTO stampcard (iStampcardMaxStamps,iFK_iRestuarentInfoId) VALUES (:iStampcardMaxStamps,:iFK_iRestuarentInfoId)");     
                }
                
                $sQuery->bindValue(':iStampcardMaxStamps', $oJSONStampcard->iStampcardMaxStamps);
                $sQuery->bindValue(':iFK_iRestuarentInfoId', $iRestuarentInfoId);
                $sQuery->execute();
                
                $oStampcard['result'] = 'true';
            }
        }
        return $oStampcard;
        
    }   
    
    
    
    /* Get stampcard for the restuarant/menucard*/
    public function GetStampcard() {

         $oStampcard = array(
                'sFunction' => 'GetStampcard',
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
            
            //Get stampcard
            $sQuery = $this->conPDO->prepare("SELECT iStampcardId,iStampcardMaxStamps,iStampcardNumberOfGivenStamps,iStampcardRedemeCode,sStampcardText FROM stampcard 
                                                WHERE iFK_iRestuarentInfoId = :iRestuarentInfoId");
            $sQuery->bindValue(':iRestuarentInfoId', $iRestuarentInfoId);
            $sQuery->execute();
            $aResult = $sQuery->fetch(PDO::FETCH_ASSOC);
            
            $iStampcardId = $aResult['iStampcardId'];
            $iStampcardMaxStamps = $aResult['iStampcardMaxStamps'];
            
            
            $oStampcard['stampcard'] = $aResult;
            $oStampcard['result'] = 'true';
            
            return $oStampcard;
        }
    }
    
    public function UpdateRedemeCode() {
        
        $oStampcard = array(
                'sFunction' => 'UpdateRedemeCode',
                'result' => 'false'
            );
        
        //Check if redemecode is set
        if(isset($_GET['sRedemeCode'])) {
            
                //Check if session is started
                if(!isset($_SESSION['sec_session_id']))
                { 
                    $this->oSecurityController->sec_session_start();
                }

                //Check if user is logged in
                if($this->oSecurityController->login_check() == true)
                {
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

                    $sRedemeCode = $_GET['sRedemeCode'];
                    
                    $sQuery = $this->conPDO->prepare("UPDATE stampcard SET iStampcardRedemeCode = :iStampcardRedemeCode WHERE iFK_iRestuarentInfoId = :iRestuarentInfoId");
                    $sQuery->bindValue(":iStampcardRedemeCode", $sRedemeCode);
                    $sQuery->bindValue(":iRestuarentInfoId", $iRestuarentInfoId);
                    $sQuery->execute();

                    $oStampcard['result'] = 'true';
                }
        }
        
        return $oStampcard;
    }
    
    public function UpdateStampcardText() {
        
        $oStampcard = array(
                'sFunction' => 'UpdateStampcardText',
                'result' => 'false'
            );
        
        if(isset($_GET['sStampcardtext'])) {
            
                $sStampcardtext = $_GET['sStampcardtext'];
            
                //Check if session is started
                if(!isset($_SESSION['sec_session_id'])) { 
                    $this->oSecurityController->sec_session_start();
                }

                //Check if user is logged in
                if($this->oSecurityController->login_check() == true) {
                    
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
        
                    $sQuery = $this->conPDO->prepare("UPDATE stampcard SET sStampcardText = :sStampcardtext WHERE iFK_iRestuarentInfoId = :iRestuarentInfoId");
                    $sQuery->bindValue(":sStampcardtext", $sStampcardtext);
                    $sQuery->bindValue(":iRestuarentInfoId", $iRestuarentInfoId);
                    $sQuery->execute();

                    $oStampcard['result'] = 'true';
                }
        
        }
        
        return $oStampcard;
    }

    public function __destruct() {
        ;
    }
}
?>
