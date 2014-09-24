<?php    
    /**
     * Bootstrap
     * 
     * @package     MagicPHP
     * @author      André Ferreira <andrehrf@gmail.com>
     * @link        https://github.com/magicphp/magicphp MagicPHP(tm) 
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
            Storage::Set("dir.public", __DIR__ . SP . "public" . SP);
            Storage::Set("dir.public.assets", __DIR__ . SP . "public" . SP . "assets" . SP);
            Storage::Set("dir.public.compiles", __DIR__ . SP . "public" . SP . "compiles" . SP);
            Storage::Set("dir.public.configs", __DIR__ . SP . "public" . SP . "configs" . SP);
            Storage::Set("dir.app", __DIR__ . SP . "app" . SP);
            
            //Configuring default route
            Storage::Set("route.root", "//".$_SERVER["SERVER_NAME"].str_replace(array("index.php", " "), array("", "%20"), $_SERVER["SCRIPT_NAME"]));//Bugfix
                    
            $oThis->LoadApp();
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
                $sClassName = str_replace("\\", SP, strtolower($sClassName));
                $aDiretoryList = array(__DIR__ . SP);
                
                if(class_exists("Storage")){
                    $aDynamicList = Storage::Get("class.list");
                    
                    if(is_array($aDynamicList))
                        $aDiretoryList = array_merge($aDiretoryList, $aDynamicList);
                }
                                                
                if(file_exists(__DIR__ . SP . $sClassName. ".class.php") || file_exists(__DIR__ . SP . $sClassName. ".php")){
                    if(file_exists(__DIR__ . SP . $sClassName . ".class.php"))
                        require_once(__DIR__ . SP . $sClassName . ".class.php");
                    else if(file_exists(__DIR__ . SP . $sClassName . ".php"))
                        require_once(__DIR__ . SP . $sClassName . ".php");  
                }
                else{
                    foreach($aDiretoryList as $sDiretory){
                        if(file_exists($sDiretory . $sClassName . ".class.php") || file_exists($sDiretory . $sClassName . ".php")){
                            if(file_exists($sDiretory . $sClassName . ".class.php"))
                                require_once($sDiretory . $sClassName . ".class.php");
                            else if(file_exists($sDiretory . $sClassName . ".php"))
                                require_once($sDiretory . $sClassName . ".php");  
                            
                            $bResult= true;
                            break;
                        }
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
        public static function LoadApp(){
            $aModulesDirectories = glob(Storage::Get("dir.app") . "*", GLOB_ONLYDIR);
            
            foreach($aModulesDirectories as $sModuleDiretory){
                if(file_exists($sModuleDiretory . SP . "status.txt"))
                    $bStatus = (intval(file_get_contents($sModuleDiretory . SP . "status.txt")) == 1);
                else
                    $bStatus = false;
                
                if($bStatus){
                    if(file_exists($sModuleDiretory . SP . "settings.php") && $bStatus)
                        require_once($sModuleDiretory . SP . "settings.php");       

                    if(file_exists($sModuleDiretory . SP . "include.php") && $bStatus)
                        require_once($sModuleDiretory . SP . "include.php");  

                    if(file_exists($sModuleDiretory . SP . "routes.php") && $bStatus)
                        require_once($sModuleDiretory . SP . "routes.php");

                    if(file_exists($sModuleDiretory . SP . "events.php") && $bStatus)
                        require_once($sModuleDiretory . SP . "events.php");
                }
            }
        }
    }