<?php
    /**
     * Route Controller
     * 
     * @package     MagicPHP
     * @author      André Henrique da Rocha Ferreira <andrehrf@gmail.com>
     * @link        https://github.com/andrehrf/magicphp MagicPHP(tm)
     * @license     MIT License (http://www.opensource.org/licenses/mit-license.php)
     */

    class Routes{
        /**
         * Routes list
         * 
         * @static
         * @access private
         * @var array 
         */
        private $aRoutes = array();
        
        /**
         * Sets the frontend overload
         * 
         * @static
         * @access private
         * @var boolean 
         */
        private $bOverloadFrontend = false;
        
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
         * Function to set the overload frontend
         * 
         * @static
         * @access public
         * @param boolean $bStatus
         * @return void
         */
        public static function SetOverloadFrontend($bStatus = false){
            $oThis = self::CreateInstanceIfNotExists();
            
            if(is_bool($bStatus))
                $oThis->bOverloadFrontend = $bStatus;
        }
        
        /**
         * Function to parse route request
         * 
         * @static
         * @access public
         * @return void
         */
        public static function Parse(){
            $oThis = self::CreateInstanceIfNotExists();
            $sRoot = str_replace("index.php", "", $_SERVER["SCRIPT_NAME"]);
            $sUri = ($sRoot != "/") ? str_replace($sRoot, "", $_SERVER["REQUEST_URI"])."@" : substr($_SERVER["REQUEST_URI"], 1, strlen($_SERVER["REQUEST_URI"])-1)."@";
            $aParsedRoute = explode("/", $sUri);
            $mID = (array_key_exists(1, $aParsedRoute)) ? $aParsedRoute[1] : null;
            $sMethod = $oThis->Restful();  
            $oThis->RestParams();
            
            Storage::Set("route.root", "//".$_SERVER["SERVER_NAME"].str_replace("index.php", "", $_SERVER["SCRIPT_NAME"]));

            if(!$oThis->bOverloadFrontend){
                $bResult = Bootstrap::AutoLoad(((!empty($aParsedRoute[0]) && $aParsedRoute[0] != "/" ) ? strtolower(str_replace("@", "", $aParsedRoute[0])) : "main"));
                
                if(!$bResult)
                    Bootstrap::AutoLoad("main");
            }
            
            //$sUri = str_repeat("*", ".*?", $sUri);//Removendo path
            $sUri = preg_replace("/\?(.*?)@/", "@", $sUri);//Removendo path
            $sUri = preg_replace("/\#(.*?)@/", "@", $sUri);//Removendo path
               
            $bCall = false;
          
            foreach($oThis->aRoutes as $sRoute => $fFunc){
                $sRoute = preg_replace("/{.*?}/i", "(.*?)", $sRoute);
                $sRoute = str_replace("/", "\/", $sRoute);
                                   
                if(preg_match_all("/@".$sRoute."@/", "@".$sMethod."_".$sUri, $aMatches)){
                    $aParams = array();
                    
                    foreach($aMatches as $iKey => $aResult){
                        if($iKey > 0){
                            $sParam = (strpos($aResult[0], "/")) ? preg_replace("/@*\/(.*?)@/", "", "@".$aResult[0]."@") : $aResult[0];
                            $aParams[] = str_replace("@", "", $sParam);
                        }
                    }
                                            
                    $bCall = true; 
                    Storage::Set("route", $sRoute);
                                                             
                    if(is_array($fFunc)){
                        if(is_array($fFunc[1]))
                            $aParams = array_merge($fFunc[1], $aParams);
                        else
                            $aParams = array_merge(array($fFunc[1]), $aParams);
                            
                        call_user_func_array($fFunc[0], $aParams);
                    }
                    else{
                        call_user_func_array($fFunc, $aParams);
                    }
                                        
                    break;
                }              
            }
                          
            if(array_key_exists("__dynamicroute", $oThis->aRoutes) && !$bCall)
                call_user_func($oThis->aRoutes["__dynamicroute"]);  
            else
                Output::SendHTTPCode(404);
        }
        
        /**
         * Function to configure actions to dynamic routes
         * 
         * @static
         * @access public
         * @param function $fCallback Callback function for dynamic routes
         * @return void
         */
        public static function SetDynamicRoute($fCallback){
            $oThis = self::CreateInstanceIfNotExists();
            $oThis->aRoutes["__dynamicroute"] = $fCallback;
        }
        
        /**
         * Function to route configuration
         * 
         * @static
         * @access public
         * @param string $sRoute Route
         * @param string $sRequestType Request type (GET, POST, PUT, DELETE)
         * @param function $fCallback Callback function for route
         * @return void
         */
        public static function Set($sRoute, $sRequestType = "GET", $fCallback){
            $oThis = self::CreateInstanceIfNotExists();
            $oThis->aRoutes[$sRequestType."_".strtolower($sRoute)] = $fCallback;
        }
        
        /**
         * Function to check the type of request to support RESTful
         * 
         * @static
         * @access public
         * @return string
         */
        public static function Restful(){
            if($_SERVER["REQUEST_METHOD"] == "POST" && array_key_exists("HTTP_X_HTTP_METHOD", $_SERVER))
                return strtoupper($_SERVER["HTTP_X_HTTP_METHOD"]);
            else
                return strtoupper($_SERVER["REQUEST_METHOD"]);
        }
        
        /**
         * Function to return parameters passed by PUT or DELETE methods
         * 
         * @static
         * @access public
         * @return void
         */
        public static function RestParams(){
            $sBuffer = file_get_contents("php://input");
            $aParams = explode("&", $sBuffer);
            $aReturn = array();
            
            foreach($aParams as $sParam){
                @list($mKey, $mValue) = @explode("=", $sParam);
                $mValue = urldecode($mValue);
                
                if(!empty($mKey) && !empty($mValue))
                    Storage::Set(strtolower(Routes::Restful()).".".$mKey, $mValue);
            }
        }
        
        /**
         * Function to check if the request is Ajax
         * 
         * @static
         * @access public
         * @return boolean
         */
        public static function IsAjaxRequest(){
           if(array_key_exists("HTTP_X_REQUESTED_WITH", $_SERVER))
               return (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
           else
               return false;
        }
        
        /**
         * Function to return the list of routes
         * 
         * @static
         * @return array
         */
        public static function GetRoutes(){
           $oThis = self::CreateInstanceIfNotExists();
           return  $oThis->aRoutes;
        }
    }