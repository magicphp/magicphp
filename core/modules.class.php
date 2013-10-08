<?php
    /**
     * Modules Controller
     * 
     * @package     MagicPHP
     * @author      André Henrique da Rocha Ferreira <andrehrf@gmail.com>
     * @copyright   Copyright (c) 2013, T&M Network, Inc.
     * @link        https://github.com/andrehrf/magicphp MagicPHP(tm)
     * @license     MIT License (http://www.opensource.org/licenses/mit-license.php)
     */

    class Modules{
        /**
         * List of modules
         * 
         * @access private
         * @var array 
         */
        private $aModules = array();
        
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
         * Function to append a module
         * 
         * @param string $sName Module name
         * @param string $sDirectory Path of module
         * @return \Module
         */
        public static function Append($sName, $sDirectory){
            $oThis = self::CreateInstanceIfNotExists();
            
            if(array_key_exists($sName, $oThis->aModules)){
                return $oThis->aModules[$sName];
            }
            else{
                $oModule = new Module($sName, $sDirectory);
                $oThis->aModules[$sName] = $oModule;
                return $oModule;
            }
        }
        
        /**
         * Function to load module on demand
         * 
         * @static
         * @access public
         * @param string $sModuleName Module name
         * @return boolean
         */
        public static function Load($sModuleName){
            
        }
    }
    
    /**
     * Class of Module
     */
    class Module{
        /**
         * Module name
         * 
         * @access private
         * @var string
         */
        private $sModuleName = null;
        
        /**
         * Module diretory
         * 
         * @access private
         * @var string
         */
        private $sModuleDiretory = null;
        
        /**
         * Dependences list
         * 
         * @access private
         * @var array 
         */
        private $aDependences = array();
        
        /**
         * Class constructor function
         * 
         * @access public
         * @param string $sName
         * @param string $sModuleDiretory
         * @return \Self
         */
        public function __construct($sModuleName, $sModuleDiretory) {
            $this->sModuleName = $sModuleName;
            $this->sModuleDiretory = $sModuleDiretory;
            return $this;
        }
        
        /**
         * Function to set module settings
         * 
         * @access public
         * @param string $sKey Search Key
         * @param mixed $mValue
         * @return \Module
         */
        public function Set($sKey, $mValue){
            if(!empty($this->sModuleName))
                Storage::Set("module.".$this->sModuleName.".".$sKey, $mValue);
            
            return $this;
        }
        
        /**
         * Function to inform the module dependencies
         * 
         * @access public
         * @param string $sDependenceName
         * @param string $sMinimalVersion
         * @return \Module 
         */
        public function Dependence($sDependenceName, $sMinimalVersion){
            $this->aDependences[$sDependenceName] = $sMinimalVersion;
            return $this;
        }
        
        /**
         * Function to start the module
         * 
         * @access public
         * @return void
         */
        public function Start(){
            if(file_exists($this->sModuleDiretory . SP . "status.txt")){
                $iStatus = intval(file_get_contents($this->sModuleDiretory . SP . "status.txt"));
                $bStatus = ($iStatus == 1);
                Storage::Set("module.".$this->sModuleName.".enabled", $bStatus);
            }
            else{
                $bStatus = true;
                file_put_contents($this->sModuleDiretory . SP . "status.txt", "1");
                Storage::Set("module.".$this->sModuleName.".enabled", true);
            }
            
            if($bStatus){
                Storage::SetArray("class.list", "module.".$this->sModuleName, $this->sModuleDiretory . "routes" . SP);
                Storage::SetArray("class.list", "module.".$this->sModuleName, $this->sModuleDiretory . "core" . SP);
                Storage::Set("module.".$this->sModuleName.".shell", $this->sModuleDiretory . "shell" . SP);
                Storage::Set("module.".$this->sModuleName.".shell.css", $this->sModuleDiretory . "shell" . SP . "css" . SP);
                Storage::Set("module.".$this->sModuleName.".shell.tpl", $this->sModuleDiretory . "shell" . SP . "tpl" . SP);
                Storage::Set("module.".$this->sModuleName.".shell.js", $this->sModuleDiretory . "shell" . SP . "js" . SP);
                Storage::Set("module.".$this->sModuleName.".shell.img", $this->sModuleDiretory . "shell" . SP . "img" . SP);
                
                //VERIFICAÇÃO DE DEPENDENCIAS
            }            
        }
    }