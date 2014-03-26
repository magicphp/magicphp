<?php
    /**
     * Events Controller
     * 
     * @package     MagicPHP
     * @author      André Henrique da Rocha Ferreira <andrehrf@gmail.com>
     * @copyright   Copyright (c) 2013, T&M Network, Inc.
     * @link        https://github.com/andrehrf/magicphp MagicPHP(tm)
     * @license     MIT License (http://www.opensource.org/licenses/mit-license.php)
     */
    
    class Events{
        /**
         * List of events
         * 
         * @access private
         * @var array 
         */
        private $aEvents = array();
        
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
         * Function to call the event
         * 
         * @static
         * @access public
         * @param string $sName
         * @param array $aParams
         * @return mixed
         */
        public static function Call($sName, $aParams = null){
            $oThis = self::CreateInstanceIfNotExists();
             
            if(array_key_exists($sName, $oThis->aEvents)){
                switch($oThis->aEvents[$sName]["type"]){
                    case "perroute":
                        $sRoute = $oThis->aEvents[$sName]["method"]."_".$oThis->aEvents[$sName]["route"];
                        
                        if($sRoute == Storage::Get("route"))
                            return call_user_func($oThis->aEvents[$sName]["func"], $aParams);
                    break;
                    case "default": return call_user_func($oThis->aEvents[$sName]["func"], $aParams); break;
                } 
            }
            else{
                return false;
            }
        }
                
        /**
         * Function to set the event
         * 
         * @static
         * @access public
         * @param string $sName
         * @param function $fCallback
         * @return void
         */
        public static function Set($sName, $fCallback){
            $oThis = self::CreateInstanceIfNotExists();
            $oThis->aEvents[$sName] = array("type" => "default", "func" => $fCallback);
        }
        
        /**
         * Function to ser the evenet per route
         * 
         * @static
         * @access public
         * @param string $sName
         * @param string $sRoute
         * @param string $sMethod
         * @param function $fCallback
         * @return void
         */
        public static function SetPerRoute($sName, $sRoute, $sMethod, $fCallback){
            $oThis = self::CreateInstanceIfNotExists();
            $oThis->aEvents[$sMethod."_".$sRoute."_".$sName] = array("type" => "perroute", "func" => $fCallback, "route" => $sRoute, "method" => $sMethod);
        }
        
        /**
         * Function to check existence of event
         * 
         * @static
         * @access public
         * @param string $sName
         * @return boolean
         */
        public static function Has($sName){
            $oThis = self::CreateInstanceIfNotExists();
            return array_key_exists($sName, $oThis->aEvents);
        }
    }
