<?php
    /**
     * Session Controller
     * 
     * @package     MagicPHP
     * @author      André Henrique da Rocha Ferreira <andrehrf@gmail.com>
     * @link        https://github.com/andrehrf/magicphp MagicPHP(tm)
     * @license     MIT License (http://www.opensource.org/licenses/mit-license.php)
     */

    class Session{
        /**
         * Session Name
         * 
         * @access private
         * @var string
         */
        private $sName;
        
        /**
         * Controller session initialization
         * 
         * @access private
         * @var boolean
         */
        private $bStarted = false;
        
        /**
         * Login Information
         * 
         * @access private 
         * @var array
         */
        private $aAuth = array();
        
        /**
         * Function to auto instance
         * 
         * @static
         * @access public
         * @return \self
         */
        public static function &CreateInstanceIfNotExists(){
            static $oInstance = null;

            if(!$oInstance instanceof self)
                $oInstance = new self();

            return $oInstance;
        } 
        
        /**
         * Function to start session
         * 
         * @static
         * @access public
         * @param string $sName Session name
         * @param string $sSessionDiretory Storage directory sessions
         * @return boolean
         */
        public static function Start($sName, $sSessionDiretory = null){
            $oThis = self::CreateInstanceIfNotExists();

            if(!empty($sName)){
                $sName = md5($sName);
                
                if(!is_null($sSessionDiretory)){
                    @session_save_path($sSessionDiretory);
                    Storage::Set("session.path", $sSessionDiretory);
                }
                
                @session_name($sName);
                $sSessionName = session_name();

                if($sSessionName != $sName)
                    $oThis->sName = $sSessionName;
                else
                    $oThis->sName = $sName;
                
                $bSession = session_start();

                if($bSession){
                    Storage::Set("session.name", $oThis->sName);
                    Storage::Set("session.id", session_id());

                    switch($_SERVER["SERVER_ADDR"]){
                        case "127.0.0.1"://Setting to developer mode
                        case "::1":
                            $iTimeout = time()+3600;	
                            session_cache_expire(60); //Increases the cache time of the session to 60 minutes
                            session_cache_limiter("nocache"); //Sets the cache limiter to 'nocache'
                            Storage::Set("session.timeout", $iTimeout); //Setting the timeout session ends
                        break;
                        default:
                            $iTimeout = time()+900;
                            session_cache_expire(15); //Shortens the session cache for 15 minutes
                            session_cache_limiter("private"); //Sets the cache limiter to 'private'
                            Storage::Set("session.timeout", $iTimeout); //Setting the timeout session ends
                        break;
                    }

                    //Recording session information
                    Storage::Set("session.cache.limiter", session_cache_limiter());
                    Storage::Set("session.cache.expire", session_cache_expire());
                    Storage::Set("session.cookie.enable", array_key_exists($sName, $_COOKIE));
                   
                    //Verifying authentication information
                    if(array_key_exists($oThis->sName, $_SESSION)){
                        if(array_key_exists("authentication", $_SESSION[$oThis->sName]))
                            $oThis->aAuth = $_SESSION[$oThis->sName]["authentication"];

                        if(!empty($oThis->aAuth)){
                            Storage::Set("user.id", $oThis->aAuth["id"]);
                            Storage::Set("user.name", $oThis->aAuth["name"]);
                            Storage::Set("user.username", $oThis->aAuth["username"]);
                            Storage::Set("user.root", $oThis->aAuth["root"]);
                            Storage::Set("session.timeout.login", $oThis->aAuth["timeout"]);
                        }
                    }
                    
                    Storage::Set("session.enabled", true);
                    $oThis->bStarted = true;
                    return true;
                }
                else{
                    Storage::Set("session.enabled", false);
                    return false;
                }
            }
            else{
                Storage::Set("session.enabled", false);
                return false;
            }
        }
        
        /**
         * Function to check if the session is active
         * 
         * @static
         * @access public
         * @return boolean
         */
        public static function Started(){
            $oThis = self::CreateInstanceIfNotExists();
            return ($oThis->bStarted);
        }
        
        /**
         * Function to perform the login session
         * 
         * @static
         * @access public
         * @param mixed $mId User ID
         * @param string $sUsername Username    
         * @param string $sName Name
         * @param integer $iTimeout Maximum time of permanence in the session
         * @param boolean $bRoot Defines the user as root
         * @return void
         */
        public static function Login($mId, $sUsername, $sName, $iTimeout = 3600, $bRoot = false){
            $oThis = self::CreateInstanceIfNotExists();

            if($oThis->bStarted){
                $iTimeout = time()+$iTimeout;
                $oThis->aAuth = array("id" => $mId, "name" => $sName, "username" => $sUsername, "timeout" => $iTimeout, "root" => $bRoot);											
                $_SESSION[$oThis->sName]["authentication"] = $oThis->aAuth;

                if(!empty($oThis->aAuth)){
                    Storage::Set("user.id", $oThis->aAuth["id"]);
                    Storage::Set("user.name", $oThis->aAuth["name"]);
                    Storage::Set("user.username", $oThis->aAuth["username"]);
                    Storage::Set("user.root", $oThis->aAuth["root"]);
                    Storage::Set("session.timeout.login",  $iTimeout); 
                }
            }
        }
        
        /**
         * Function to logout
         * 
         * @static
         * @access public
         * @return void
         */
        public static function Logout(){
            $oThis = self::CreateInstanceIfNotExists();

            if($oThis->bStarted){
                session_unset();
                unset($_SESSION[$oThis->sName]["authentication"]);
                unset($oThis->aAuth);
            }
        }
        
        /**
         * Function to check the authentication session
         * 
         * @static
         * @access public
         * @return boolean
         */
        public static function CheckAuthentication(){
            $oThis = self::CreateInstanceIfNotExists();
            $bReturn = ($oThis->bStarted) ? (!empty($oThis->aAuth["id"]) && !empty($oThis->aAuth["username"]) && ((intval($oThis->aAuth["timeout"]) > time()) || (intval($oThis->aAuth["timeout"]) <= 0))) : false;
            return $bReturn;
        }
        
        /**
         * Function to set data in a session
         * 
         * @static
         * @access public
         * @param string $sKey Search Key
         * @param mixed $mValue Data to be stored
         * @return boolean
         */
        public static function Set($sKey, $mValue){
            $oThis = self::CreateInstanceIfNotExists();

            if($oThis->bStarted){
                if($sKey != "name" && $sKey != "id" && $sKey != "authentication"){
                    $_SESSION[$oThis->sName][$sKey] = $mValue;
                    return true;
                }
                else{
                    return false;
                }
            }
            else{
                return false;
            }
        }
        
        /**
         * Function to return information stored in session
         * 
         * @static
         * @access public
         * @param string $sKey Search Key
         * @return mixed
         */
        public static function Get($sKey){
            $oThis = self::CreateInstanceIfNotExists();

            if($oThis->bStarted)
                return (!empty($_SESSION[$oThis->sName][$sKey])) ? $_SESSION[$oThis->sName][$sKey] : false;
            else
                return null;
        }
    }