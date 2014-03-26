<?php    
    /**
     * Bootstrap
     * 
     * @package     MagicPHP
     * @author      André Henrique da Rocha Ferreira <andrehrf@gmail.com>
     * @link        https://github.com/andrehrf/magicphp MagicPHP(tm) 
     * @license     MIT License (http://www.opensource.org/licenses/mit-license.php)
     */

    if(!defined("SP")) define("SP", DIRECTORY_SEPARATOR, true);
     
    class Bootstrap{
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
         * Starting the application
         * 
         * @static
         * @access public
         * @return void
         */
        public static function Start(){
            $oThis = self::CreateInstanceIfNotExists();
            
            //setting autoload
            spl_autoload_register(array($oThis, "AutoLoad")); 
                       
            //Configuring basic directories
            Storage::Set("dir.root", __DIR__ . SP);
            Storage::Set("dir.shell", __DIR__ . SP . "shell" . SP);
            Storage::Set("dir.core", __DIR__ . SP . "core" . SP);
            Storage::Set("dir.cache", __DIR__ . SP . "cache" . SP);
            Storage::Set("dir.modules", __DIR__ . SP . "modules" . SP);
            
            //Setting the default template directories
            Storage::Set("dir.shell.default", Storage::Join("dir.shell", "default" . SP));
            Storage::Set("dir.shell.default.tpl", Storage::Join("dir.shell.default", "tpl" . SP));
            Storage::Set("dir.shell.default.css", Storage::Join("dir.shell.default", "css" . SP));
            Storage::Set("dir.shell.default.js", Storage::Join("dir.shell.default", "js" . SP));
            Storage::Set("dir.shell.default.img", Storage::Join("dir.shell.default", "img" . SP));
                    
            $oThis->LoadModules();
        }
        
        /**
         * Autoload
         * 
         * @static
         * @param string $sClassName Class name
         * @return boolean
         */
        public static function AutoLoad($sClassName){
            if(!class_exists($sClassName, false)){
                $bResult = false;
                
                //putting in small letters the class name
                $sClassName = strtolower($sClassName); 
                $aDiretoryList = array(__DIR__ . SP . "core" . SP, __DIR__ . SP);
                
                if(class_exists("Storage")){
                    $aDynamicList = Storage::Get("class.list");
                    
                    if(is_array($aDynamicList))
                        $aDiretoryList = array_merge($aDiretoryList, $aDynamicList);
                }
                                                
                foreach($aDiretoryList as $sDiretory){
                    if(file_exists($sDiretory . $sClassName . ".class.php") || file_exists($sDiretory . $sClassName . ".php")){
                        if(file_exists($sDiretory . $sClassName . ".class.php"))
                            @require_once($sDiretory . $sClassName . ".class.php");
                        else if(file_exists($sDiretory . $sClassName . ".php"))
                            @require_once($sDiretory . $sClassName . ".php");  
                        
                        $bResult= true;
                        break;
                    }
                }
                
                return $bResult;
            }   
            else{
                return true;
            }
        }
                
        /**
         * Function to load modules
         * 
         * @static
         * @access public
         * @return void
         */
        public static function LoadModules(){
            $aModulesDirectories = glob(Storage::Get("dir.modules") . "*", GLOB_ONLYDIR);
            
            foreach($aModulesDirectories as $sModuleDiretory){
                if(file_exists($sModuleDiretory . SP . "status.txt"))
                    $bStatus = (intval(file_get_contents($sModuleDiretory . SP . "status.txt")) == 1);
                else
                    $bStatus = false;
                
                if(file_exists($sModuleDiretory . SP . "settings.php") && $bStatus)
                    require_once($sModuleDiretory . SP . "settings.php");       
                        
                if(file_exists($sModuleDiretory . SP . "include.php") && $bStatus)
                    require_once($sModuleDiretory . SP . "include.php");                
            }
        }
    }