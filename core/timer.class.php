<?php
    /**
     * Timer startup
     * 
     * @package     MagicPHP
     * @author      André Henrique da Rocha Ferreira <andrehrf@gmail.com>
     * @link        https://github.com/andrehrf/magicphp MagicPHP(tm)
     * @license     MIT License (http://www.opensource.org/licenses/mit-license.php)
     */

    class Timer{
        /** 
         * Variable to store the times
         *  
         * @var array 
         * @access private 
         */
        private $aTime = array();
        
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
         * Function to start the clock
         *  
         * @access public 
         * @return void 
         * @static 
         */
        public static function Start(){ 
            $oThis = self::CreateInstanceIfNotExists(); 
            $oThis->aTime = array("start" => microtime(true));
        } 
        
        /** 
         * Function to end the timing 
         *  
         * @access public 
         * @return void 
         * @static 
         */
        public static function End(){ 
            $oThis = self::CreateInstanceIfNotExists(); 
  
            if(array_key_exists("start", $oThis->aTime)){ 
                $oThis->aTime["end"] = microtime(true); 
                $oThis->aTime["loading"] = number_format(abs($oThis->aTime["end"] - $oThis->aTime["start"]), 4, ".", ""); 
            } 
        } 
        
        /** 
         * Function to mark the time of a specific action 
         *  
         * @access public 
         * @param string $sDescription Description of the mark
         * @return void 
         * @static 
         */
        public static function Make($sDescription){ 
            $oThis = self::CreateInstanceIfNotExists(); 
            $iMicrotime = microtime(true); 
            $iLoading = number_format(abs($iMicrotime - $oThis->aTime["start"]), 4, ".", ""); 
            $oThis->aTime[] = array("description" => $sDescription, "time" => $iMicrotime, "loading" => $iLoading); 
        } 
        
        /**
         * Function to return time
         * 
         * @static
         * @access public
         * @return array
         */
        public static function ReturnTimes(){
            $oThis = self::CreateInstanceIfNotExists(); 
            return $oThis->aTime;
        }
    }