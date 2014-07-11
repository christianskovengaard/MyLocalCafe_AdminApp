<?php

class SecurityController 
{
    private $conPDO;
    
    public function __construct() 
    {
        
    }
    
    //SECURE SESSION START FUNCTION - run this if you want to use session
    public function sec_session_start() 
    {
            $session_name = 'sec_session_id'; // Set a custom session name
            $secure = false; // Set to true if using https.
            $httponly = true; // This stops javascript being able to access the session id. 

            ini_set('session.use_only_cookies', 1); // Forces sessions to only use cookies. 
            $cookieParams = session_get_cookie_params(); // Gets current cookies params.
            session_set_cookie_params($cookieParams["lifetime"], $cookieParams["path"], $cookieParams["domain"], $secure, $httponly); 
            session_name($session_name); // Sets the session name to the one set above.
            //Only start session if no session has been started
            if (session_status() == PHP_SESSION_NONE) {session_start();} // Start the php session PHP version 5.4
            if (session_id() == '') {session_start();} // PHP version 5.3
            //session_regenerate_id(true); // regenerated the session, delete the old one.     //TODO: This causes problems on localhost with WAMP server
    }
    
    // LOGIN CHECK FUNCTION - BRUTE FORCE
    public function checkbrute($user_id,$conPDO) 
    {
       // Get timestamp of current time
       $now = time();
       // All login attempts are counted from the past 2 hours. 
       $valid_attempts = $now - (2 * 60 * 60); 

       if ($sQuery = $conPDO->prepare("SELECT time FROM login_attempts WHERE iFK_iUserId = :iUserId AND time > '$valid_attempts'"))
       { 
          $sQuery->bindValue(':iUserId', $user_id); 
          // Execute the prepared query.
          $sQuery->execute();
          // If there has been more than 5 failed logins
          if($sQuery->rowCount() > 5) 
          {
             return true;
          } 
          else
          {
             return false;
          }
       }
    }
    
    
    //CREATE LOGIN CHECK FUNCTION - Logged Status, check if user is loggede in
    public function login_check() 
    {
        //Check if DatabaseClass is declared, this applies when checking for if user is logged in at admin.php
        require_once 'DatabaseController.php';   
        $oDatabase = new DatabaseController();
        $this->conPDO = $oDatabase->ConnectToDatabase();
        
            // Check if all session variables are set
        if(isset($_SESSION['user_id'], $_SESSION['username'], $_SESSION['login_string'])) 
        {
            $user_id = $_SESSION['user_id'];
            $login_string = $_SESSION['login_string'];
            //$username = $_SESSION['username'];
            $ip_address = $_SERVER['REMOTE_ADDR']; // Get the IP address of the user. 
            $user_browser = $_SERVER['HTTP_USER_AGENT']; // Get the user-agent string of the user.

            if ($sQuery = $this->conPDO->prepare("SELECT sUserPassword FROM users WHERE iUserIdHashed = :iUserIdHashed LIMIT 1")) 
            { 
                $sQuery->bindValue(':iUserIdHashed', $user_id); // Bind "$user_id" to parameter.
                $sQuery->execute(); // Execute the prepared query.

                if($sQuery->rowCount() == 1) 
                {   // If the user exists
                $aUser = $sQuery->fetch(PDO::FETCH_ASSOC);

                $password = $aUser['sUserPassword']; // get variables from result. The userpassword hashed
                $password = hash('sha512', $password);
                $login_check = hash('sha512', $password.$ip_address.$user_browser);
                //echo "login string: ".$login_string;
                //echo "<br> login check: ".$login_check.'<br>';
                if($login_check == $login_string) 
                {
                    // Logged In!!!!
                    return true;
                } else {
                    // Not logged in
                    //return 1;
                    return false;
                }
                } else {
                    // Not logged in
                    //return 2;
                    return false;
                }
            } else {
                // Not logged in
                //return 3;
                return false;
            }
        } else {
            // Not logged in
            //return 4;
            return false;
        }
    }
    
    public function __destruct() {
        ;
    }
}

?>
