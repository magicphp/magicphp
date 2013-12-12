<?php
    /**
     * Global Storage
     * 
     * @package     MagicPHP
     * @author      André Henrique da Rocha Ferreira <andrehrf@gmail.com>
     * @link        https://github.com/andrehrf/magicphp MagicPHP(tm)
     * @license     MIT License (http://www.opensource.org/licenses/mit-license.php)
     */

    class Storage{
        /**
         * Storage list
         * 
         * @static
         * @access private
         * @var array 
         */
        private $aList = array();
        
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
         * Function to check existence of data stored
         * 
         * @static
         * @access public
         * @param string $sKey Search key
         * @return boolean
         */
        public static function Has($sKey){
            $oThis = self::CreateInstanceIfNotExists();
            return (array_key_exists($sKey, $oThis->aList));
        }
        
        /**
         * Function to store data 
         * 
         * @static
         * @access public
         * @param string $sKey Search key
         * @param mixed $mValue Value Data to be stored
         * @return void
         */
        public static function Set($sKey, $mValue){
            $oThis = self::CreateInstanceIfNotExists();
            $oThis->aList[$sKey] = $mValue;  
        }
        
        /**
         * Function to store data in list
         * 
         * @static
         * @access public
         * @param string $sKey Search key
         * @param string $sKeyInArray Search key in the list
         * @param mixed $mValue Data to be stored
         * @return void
         */
        public static function SetArray($sKey, $sKeyInArray, $mValue){
            $oThis = self::CreateInstanceIfNotExists();
            
            if(!array_key_exists($sKey, $oThis->aList))
                $oThis->aList[$sKey] = array();
            
            $oThis->aList[$sKey][$sKeyInArray] = $mValue;
        }
             
        /**
         * Function to return data stored
         * 
         * @static
         * @access public
         * @param string $sKey Search key
         * @param mixed $mDefault Default value (returns if no storage)
         * @return mixed
         */
        public static function Get($sKey, $mDefault = false){
            $oThis = self::CreateInstanceIfNotExists();
            return (array_key_exists($sKey, $oThis->aList)) ? $oThis->aList[$sKey] : $mDefault;
        }
        
        /**
         * Function to return data stored in list
         * 
         * @static
         * @access public
         * @param string $sKey Search key
         * @param string $sKeyInArray Search key in the list
         * @param mixed $mDefault Default value (returns if no storage)
         * @return mixed
         */
        public static function GetArray($sKey, $sKeyInArray, $mDefault = false){
            $oThis = self::CreateInstanceIfNotExists();
            return (array_key_exists($sKey, $oThis->aList)) ? ((array_key_exists($sKeyInArray, $oThis->aList[$sKey]) ? $oThis->aList[$sKey][$sKeyInArray] : $mDefault)) : $mDefault;
        }
        
        /**
         * Function to concatenate data stored
         * 
         * @static
         * @access public
         * @param string $sKey Search key
         * @param string $sAppend Text to be concatenated
         * @return string|boolean
         */
        public static function Join($sKey, $sAppend){
            $oThis = self::CreateInstanceIfNotExists();
            return (array_key_exists($sKey, $oThis->aList)) ? ((is_string($oThis->aList[$sKey])) ? $oThis->aList[$sKey].$sAppend : false)  : false;
        }
    }