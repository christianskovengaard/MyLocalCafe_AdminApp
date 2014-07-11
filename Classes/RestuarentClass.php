<?php


class RestuarentClass {
    
    private $sRestuarentInfoName;
    private $sRestuarentInfoPhone;
    private $sRestuarentInfoAddress;
    private $iFK_iCompanyInfoId;
    
    public function __construct() {
        ;
    }
    
    public function SetRestaurent($sRestuarentInfoName,$sRestuarentInfoPhone,$sRestuarentInfoAddress,$iFK_iCompanyInfoId)
    {
        $this->sRestuarentInfoName = $sRestuarentInfoName;
        $this->sRestuarentInfoPhone = $sRestuarentInfoPhone;
        $this->sRestuarentInfoAddress = $sRestuarentInfoAddress;
        $this->iFK_iCompanyInfoId = $iFK_iCompanyInfoId;

    }
    
    public function GetRestuarent()
    {
        $oRestuarent = new stdClass();
        $oRestuarent->sRestuarentInfoName = $this->sRestuarentInfoName;
        $oRestuarent->sRestuarentInfoPhone = $this->sRestuarentInfoPhone;
        $oRestuarent->sRestuarentInfoAddress = $this->sRestuarentInfoAddress;
        $oRestuarent->iFK_iCompanyInfoId = $this->iFK_iCompanyInfoId;
        
        return $oRestuarent;
    }
}

?>
