<?php
    /**
     * Debug
     * 
     * @package     MagicPHP Debug
     * @author      André Ferreira <andrehrf@gmail.com>
     * @link        https://github.com/magicphp/magicphp MagicPHP(tm)
     * @license     MIT License (http://www.opensource.org/licenses/mit-license.php)
     */

    class Debug{
        /**
         * Function to display debug
         * 
         * @static
         * @access public
         * @return void
         */
        public static function Display(){
            //die(Storage::Join("module.debug.shell.tpl", "debug.tpl"));
            Output::SetNamespace("debug");
            Output::SetTemplate(Storage::Join("module.debug.shell.tpl", "debug.tpl"));
            Output::AppendCSS(Storage::Join("module.debug.shell.css", "debug.css"));
            Output::AppendJS(Storage::Join("module.debug.shell.js", "debug.js"));
            
            if(!Session::Started())
                Session::Start("debug");
            
            $aDebug = Session::Get("debug");
                        
            //Global Storage
            $aGlobalStorage = $aDebug["globalstorage"];
            $aStorageDisplay = array();
            
            foreach($aGlobalStorage as $sKey => $mValue){
                if(substr($sKey, 0, 4) != "lng." && substr($sKey, 0, 7) != "module."){
                    ob_start();
                    var_dump($mValue);
                    $sValue = ob_get_clean();
  
                    $aStorageDisplay[$sKey] = array("key" => $sKey, "value" => "<pre>".$sValue."</pre>");
                }
            }
            
            ksort($aStorageDisplay);            
            Storage::Set("debug.total.globalstorage", count($aStorageDisplay));
            Output::ReplaceList("globalstorage", $aStorageDisplay); 
            
            //Request
            $aRequestDisplay = array();
            foreach($aDebug["http_request"] as $sKey => $mValue)
                $aRequestDisplay[$sKey] = array("key" => $sKey, "value" => $mValue);
            
            ksort($aRequestDisplay);         
            Output::ReplaceList("http_request", $aRequestDisplay); 
                        
            //Response
            $aResponseDisplay = array();
            foreach($aDebug["http_response"] as $sKey => $mValue){
                list($sKey, $mValue) = explode(":", $mValue);
                $aResponseDisplay[$sKey] = array("key" => $sKey, "value" => $mValue);
            }
            
            ksort($aResponseDisplay);         
            Output::ReplaceList("http_response", $aResponseDisplay); 
            
            //Queries
            Output::ReplaceList("queries_report", $aDebug["queries_report"]); 
            Output::Send();
        }
        
        /**
         * Function to mark the debug data
         * 
         * @static
         * @access public
         * @return void
         */
        public static function Make(){                                                
            $aDebug = array("globalstorage" => Storage::GetList(),
                            "http_request" => Debug::HTTPRequest(),
                            "http_response" => headers_list());
            
            if(class_exists("Db"))
                $aDebug["queries_report"] = Db::ReturnLogs();

            //Storing in session if memcached is not enabled
            if(!Session::Started())
                Session::Start("debug");
            
            Session::Set("debug", $aDebug);
        }
        
        /**
         * Function to return information request
         * 
         * @static
         * @access public
         * @return array
         */
        public static function HTTPRequest(){
            $aHeaders = array();
      
            foreach($_SERVER as $sName => $sValue){ 
                if(substr($sName, 0, 5) == 'HTTP_'){ 
                    $sName = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($sName, 5))))); 
                    $aHeaders[$sName] = $sValue; 
                }  
                elseif($sName == "CONTENT_TYPE"){ 
                    $aHeaders["Content-Type"] = $sValue; 
                } 
                elseif($sName == "CONTENT_LENGTH"){
                    $aHeaders["Content-Length"] = $sValue; 
                } 
            } 
   
            return $aHeaders; 
        }
    }