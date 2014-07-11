<?php
//Use this class to defined what properties a user has
class User
{
    private $sUsername;
    private $sUserPassword;
    private $iUserRole;



    public function __construct() 
    {
        
    }

    public function SetUser($sUsername,$sUserPassword,$iUserRole)
    {
        $this->sUsername = $sUsername;
        $this->sUserPassword = $sUserPassword;
        $this->iUserRole = $iUserRole;
    }
    
    public function GetUser()
    {   
        //Create object user and return it
        
        $oUser = new stdClass();
        $oUser->sUsername = $this->sUsername;
        $oUser->sUserPassword = $this->sUserPassword;
        $oUser->iUserRole = $this->iUserRole;
        
        return $oUser;
    }
}
?>
