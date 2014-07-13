<?php

class RestuarentController 
{
    
    private $conPDO;
    private $oRestuarent;
    private $oSecurity;
    
    public function __construct()
    {

        require_once 'DatabaseController.php';
        $oDatabaseController = new DatabaseController();
        $this->conPDO = $oDatabaseController->ConnectToDatabase();
        
        require_once(ROOT_DIRECTORY . '/Classes/RestuarentClass.php');
        $this->oRestuarent = new RestuarentClass();
        
        if(!class_exists('SecurityController') )
        {
            require 'SecurityController.php';
            $this->oSecurity = new SecurityController();
        }
        
        //Check if session is started
        if (!isset($_SESSION['sec_session_id'])) {
            $this->oSecurity->sec_session_start();
        }
    }
    
    public function GetRestuarentInfo()
    {
        
        
        //Allow all, NOT SAFE
        //header('Access-Control-Allow-Origin: *');  
        
        // Only allow trusted, MUCH more safe
        header('Access-Control-Allow-Origin: mylocalcafe.dk');
        header('Access-Control-Allow-Origin: www.mylocalcafe.dk');
        
        $aRestuarentInfo = array(
                'sFunction' => 'GetRestuarentInfo',
                'result' => 'false'
            );
        
        //Get user logged in
        
        $sQuery = $this->conPDO->prepare('SELECT * FROM restuarentinfo WHERE iRestuarentInfoId = :iRestuarentInfoId');
        $sQuery->bindValue(":iRestuarentInfoId", $this->GetResturantId());
        try
        {
            $sQuery->execute();             
        }
        catch (PDOException $e)
        {
           die($e->getMessage()); 
        }
        
        $aResult = $sQuery->fetch(PDO::FETCH_ASSOC);
        
        $aRestuarentInfo['name'] = $aResult['sRestuarentInfoName'];              
        $aRestuarentInfo['result'] = 'true';
        return $aRestuarentInfo;
    }
    
    
    private function GetResturantId()
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
        return $aResult['iRestuarentInfoId'];

    }
    
    public function __destruct() {
        ;
    }
    
}

?>
