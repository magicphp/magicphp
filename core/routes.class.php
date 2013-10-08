<?php
    /**
     * Route Controller
     * 
     * @package     MagicPHP
     * @author      André Henrique da Rocha Ferreira <andrehrf@gmail.com>
     * @copyright   Copyright (c) 2013, T&M Network, Inc.
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
            $sUri = str_replace($sRoot, "", $_SERVER["REQUEST_URI"]);
            $aParsedRoute = explode("/", $sUri);
            $mID = (array_key_exists(1, $aParsedRoute)) ? $aParsedRoute[1] : null;
            
            Storage::Set("route.root", $_SERVER["REQUEST_SCHEME"]."://".$_SERVER["SERVER_NAME"].str_replace("index.php", "", $_SERVER["SCRIPT_NAME"]));
                        
            if(!empty($aParsedRoute[0]) || !$oThis->bOverloadFrontend)
                Bootstrap::AutoLoad(((!empty($aParsedRoute[0])) ? strtolower($aParsedRoute[0]) : "frontend"));
            
            $sMethod = $oThis->Restful();
                        
            if(array_key_exists($sMethod."_".$sUri, $oThis->aRoutes))
                $oThis->aRoutes[$sMethod."_".$sUri]($mID);
            else if(array_key_exists("__dynamicroute", $oThis->aRoutes))
                $oThis->aRoutes["__dynamicroute"]($sUri, $sMethod);
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
                return $_SERVER["HTTP_X_HTTP_METHOD"];
            else
                return $_SERVER["REQUEST_METHOD"];
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
    }