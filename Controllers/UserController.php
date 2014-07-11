<?php

class UserController
{
    private $conPDO;
    private $oUser;
    private $oBcrypt;
    private $oSecurity;
    
    
    public function __construct() 
    {

        //Connect to database
        require_once 'DatabaseController.php';
        $oDatabaseController = new DatabaseController();
        $this->conPDO = $oDatabaseController->ConnectToDatabase();

        
        //Initiate the UserClass     
        require_once(ROOT_DIRECTORY . '/Classes/UserClass.php');
        $this->oUser = new User();
        
        //Initiate the Bcrypt class
        require_once(ROOT_DIRECTORY . '/Classes/bcrypt.php');
        $this->oBcrypt = new Bcrypt();
        

        require_once 'SecurityController.php';
        $this->oSecurity = new SecurityController();
    }


    
    
    
    public function LogInUser($sUsername,$sUserPassword)
    {
        
        $aLogin = array(
            'result' => false
        );
        
        $sUsername = utf8_decode($sUsername);
        
        $sQuery = $this->conPDO->prepare("SELECT sUserPassword,iUserIdHashed,iUserId,sUsername,iFK_iCompanyId,sUserCreateToken FROM users WHERE sUsername = ? LIMIT 1");
	$sQuery->bindValue(1, $sUsername);
        
	$sQuery->execute();

	//Fetch the result as assoc array
        $aUser = $sQuery->fetch(PDO::FETCH_ASSOC);
	       
        
        $sUserPasswordFromDatabase = $aUser['sUserPassword']; // stored hashed password
        $user_id_hashed = $aUser['iUserIdHashed']; // iUserId hashed
        $user_id =  $aUser['iUserId']; //iUserId
        $username = $aUser['sUsername']; // sUsername
        $password = hash('sha512', $sUserPasswordFromDatabase); // hash the password with the unique password from DB.
        
        
        
        //Check if num result is 1
        if($sQuery->rowCount() == 1)
        {
           // We check if the account is locked from too many login attempts
           if($this->oSecurity->checkbrute($user_id,$this->conPDO) == true)
           { 
               
                //Create user token for the user to reset there password
                $number = uniqid();
                $sUserToken = $this->oBcrypt->genHash($number);
                
               
                // Account is locked for 2 hours
                $sMessage = "Din konto er blevet spærret i 2 timer. Klik <a href='http://mylocalcafe.dk/user?sUserToken=$sUserToken'>her</a> for at genåbne din konto"; //TODO: Change this when in production mode
                $sTo = 'christianskovengaard@gmail.com'; //TODO: Change to $username when in production mode
                $sFrom = 'support@mylocalcafe.dk';
                $sSubject = 'Konto spærret';
                $this->oEmail->SendEmail($sTo, $sFrom, $sSubject, $sMessage);
                $aLogin['result'] = 'Account locked';
                return $aLogin;
           }
           else
           {
               
                //Check if the users has a menucard, if no new card send register mail again
                if($aUser['iFK_iCompanyId'] == NULL) {

                    $sUserToken = $aUser['sUserCreateToken'];
                    //Set new UserCreateToken if it is empty
                    if($sUserToken == ''){
                        $sUserToken = $this->oBcrypt->genHash($aUser['sUsername']);
                        //Update the user with the new UserCreateToken
                        $sQuery = $this->conPDO->prepare("UPDATE users SET sUserCreateToken = :sUserCreateToken WHERE sUsername = :sUsername");
                        $sQuery->bindValue(":sUsername", $aUser['sUsername']);
                        $sQuery->bindValue(":sUserCreateToken", $sUserToken);
                        $sQuery->execute();
                    }

                    //Send register email again
                    $sTo = $aUser['sUsername'];
                    $sFrom = 'support@mylocalcafe.dk';
                    $sSubject = 'Ny konto hos My Local Café';
                    $sMessage = "Ny bruger til My Local Café, Tryk på dette <a href='http://mylocalcafe.dk/register?sUserToken=$sUserToken'>link</a> for at oprette din profil";               
                    //Send email with link to reset password
                    $this->oEmail->SendEmail($sTo, $sFrom, $sSubject, $sMessage);

                    $aLogin['result'] = 'nocafe';
                    return $aLogin;
                }
                             
                // using the verify method to compare the password with the stored hashed password.
                if($this->oBcrypt->verify($sUserPassword, $sUserPasswordFromDatabase) === true)
                { 
                    //Start a secure session
                    $this->oSecurity->sec_session_start();
                    
                    $ip_address = $_SERVER['REMOTE_ADDR']; // Get the IP address of the user. 
                    $user_browser = $_SERVER['HTTP_USER_AGENT']; // Get the user-agent string of the user.

                    //$user_id_hashed = preg_replace("/[^0-9]+/", "", $user_id_hashed); // XSS protection as we might print this value
                    $_SESSION['user_id'] = $user_id_hashed; 
                    //$username = preg_replace("/[^a-zA-Z0-9_\-]+/", "", $username); // XSS protection as we might print this value
                    $_SESSION['username'] = $username;
                    $_SESSION['login_string'] = hash('sha512', $password.$ip_address.$user_browser);
                    // Login successful.
                    $aLogin['result'] = 'true';
                    return $aLogin;
                }
                else
                {
                    // Password is not correct
                    // We record this attempt in the database
                    $now = time();
                    $sQuery = $this->conPDO->prepare("INSERT INTO login_attempts (iFK_iUserId, time) VALUES (:user_id,:now)");
                    $sQuery->bindValue(':user_id', $user_id);
                    $sQuery->bindValue(':now', $now);
                    $sQuery->execute();
                    $aLogin['result'] = 'false';
                    return $aLogin;	
                }  
           }
        }
        else 
        {
            // No user exists. 
            //echo 'No user';
            $aLogin['result'] = 'false';
            return $aLogin;
        }
        
     }
     
       
}
?>