<?php

class CompanyController 
{
    private $conPDO;
    private $oCompany;
    
    public function __construct() {
       
       if(!class_exists('Database') )
       { 
            
                     
       }
       require_once 'DatabaseController.php';
       $oDatabaseController = new DatabaseController();   
       $this->conPDO = $oDatabaseController->ConnectToDatabase(); 
       
       require_once(ROOT_DIRECTORY . '/Classes/CompanyClass.php');
       $this->oCompany = new CompanyClass();
    }
    
    public function AddCompany($sCompanyName, $sCompanyPhone, $sCompanyAddress, $sCompanyZipcode, $sCompanyCVR)
    {
        
        $this->oCompany->SetCompany($sCompanyName, $sCompanyPhone, $sCompanyAddress, $sCompanyZipcode, $sCompanyCVR);
        
        //Insert into database          
        $sQuery = $this->conPDO->prepare("INSERT INTO company (sCompanyName,sCompanyPhone,sCompanyAddress,sCompanyZipcode,sCompanyCVR) VALUES (:sCompanyName,:sCompanyPhone,:sCompanyAddress,:sCompanyZipcode,:sCompanyCVR)");

        $oCompany = $this->oCompany->GetCompany();
        
        $sQuery->bindValue(':sCompanyName', utf8_decode(urldecode($oCompany->sCompanyName)));
        $sQuery->bindValue(':sCompanyPhone', $oCompany->sCompanyPhone);
        $sQuery->bindValue(':sCompanyAddress', utf8_decode(urldecode($oCompany->sCompanyAddress)));
        $sQuery->bindValue(':sCompanyZipcode', $oCompany->sCompanyZipcode);
        $sQuery->bindValue(':sCompanyCVR', $oCompany->sCompanyCVR);
        $sQuery->execute();
        
        //Get the last inserted id
        $iCompanyId = $this->conPDO->lastInsertId();
        
        return $iCompanyId;
         
    }
    
    public function UpdateCompany($sCompanyName,$sCompanyPhone,$sCompanyAddress,$sCompanyZipcode,$sCompanyCVR,$iFK_iCompanyId)
    {
        $sQuery = $this->conPDO->prepare("UPDATE company SET 
                    sCompanyName = :sCompanyName, sCompanyPhone = :sCompanyPhone, sCompanyAddress = :sCompanyAdress, sCompanyZipcode = :sCompanyZipcode, sCompanyCVR = :sCompanyCVR WHERE iCompanyId = :iCompanyId LIMIT 1");
                $sQuery->bindValue(":sCompanyName", utf8_decode(urldecode($sCompanyName)));
                $sQuery->bindValue(":sCompanyPhone", urldecode($sCompanyPhone));
                $sQuery->bindValue(":sCompanyAdress", utf8_decode(urldecode($sCompanyAddress)));
                $sQuery->bindValue(":sCompanyZipcode", urldecode($sCompanyZipcode));
                $sQuery->bindValue(":sCompanyCVR", urldecode($sCompanyCVR));
                $sQuery->bindValue(":iCompanyId", $iFK_iCompanyId);
                $sQuery->execute();
    }
    
    public function __destruct() {
        ;
    }
}
?>
