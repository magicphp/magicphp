<?php
    /**
     * Output Controller
     * 
     * @package     MagicPHP
     * @author      André Henrique da Rocha Ferreira <andrehrf@gmail.com>
     * @link        https://github.com/andrehrf/magicphp MagicPHP(tm)
     * @license     MIT License (http://www.opensource.org/licenses/mit-license.php)
     */

    class Output{
        /**
         * Lista de status do protocolo HTTP
         * 
         * @var array
         * @access private
         */
        private $aHTTPStatus = array(100 => "HTTP/1.1 100 Continue",
                                     101 => "HTTP/1.1 101 Switching Protocols",
                                     200 => "HTTP/1.1 200 OK",
                                     201 => "HTTP/1.1 201 Created",
                                     202 => "HTTP/1.1 202 Accepted",
                                     203 => "HTTP/1.1 203 Non-Authoritative Information",
                                     204 => "HTTP/1.1 204 No Content",
                                     205 => "HTTP/1.1 205 Reset Content",
                                     206 => "HTTP/1.1 206 Partial Content",
                                     300 => "HTTP/1.1 300 Multiple Choices",
                                     301 => "HTTP/1.1 301 Moved Permanently",
                                     302 => "HTTP/1.1 302 Found",
                                     303 => "HTTP/1.1 303 See Other",
                                     304 => "HTTP/1.1 304 Not Modified",
                                     305 => "HTTP/1.1 305 Use Proxy",
                                     307 => "HTTP/1.1 307 Temporary Redirect",
                                     400 => "HTTP/1.1 400 Bad Request",
                                     401 => "HTTP/1.1 401 Unauthorized",
                                     402 => "HTTP/1.1 402 Payment Required",
                                     403 => "HTTP/1.1 403 Forbidden",
                                     404 => "HTTP/1.1 404 Not Found",
                                     405 => "HTTP/1.1 405 Method Not Allowed",
                                     406 => "HTTP/1.1 406 Not Acceptable",
                                     407 => "HTTP/1.1 407 Proxy Authentication Required",
                                     408 => "HTTP/1.1 408 Request Time-out",
                                     409 => "HTTP/1.1 409 Conflict",
                                     410 => "HTTP/1.1 410 Gone",
                                     411 => "HTTP/1.1 411 Length Required",
                                     412 => "HTTP/1.1 412 Precondition Failed",
                                     413 => "HTTP/1.1 413 Request Entity Too Large",
                                     414 => "HTTP/1.1 414 Request-URI Too Large",
                                     415 => "HTTP/1.1 415 Unsupported Media Type",
                                     416 => "HTTP/1.1 416 Requested range not satisfiable",
                                     417 => "HTTP/1.1 417 Expectation Failed",
                                     500 => "HTTP/1.1 500 Internal Server Error",
                                     501 => "HTTP/1.1 501 Not Implemented",
                                     502 => "HTTP/1.1 502 Bad Gateway",
                                     503 => "HTTP/1.1 503 Service Unavailable",
                                     504 => "HTTP/1.1 504 Gateway Time-out");
        
        /**
         * Namespace output 
         * 
         * @static
         * @access private
         * @var string 
         */
        private $sNamespace = null;
        
        /**
         * Buffer
         * 
         * @static
         * @access private
         * @var string 
         */
        private $sBuffer = null; 
        
        /**
         * List of CSS files output
         * 
         * @static
         * @access private
         * @var array 
         */
        private $aCSS = array();
        
        /**
         * List of Javascript files output
         * 
         * @static
         * @access private
         * @var array 
         */
        private $aJS = array();

        
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
         * Function to configure namespace output
         * 
         * @access public
         * @param string $sNamespace
         * @return void
         */
        public static function SetNamespace($sNamespace){
            $oThis = self::CreateInstanceIfNotExists();
            
            if(is_string($sNamespace)){
                $sNamespace = strtolower(str_replace(" ", "_", $sNamespace));                
                $oThis->sNamespace = $sNamespace;
            }
        }

        /**
         * Function to set the masterpage and template
         * 
         * @access public
         * @param string $sTemplateFilename
         * @param string $sMasterpageFilename
         * @return void
         */
        public static function SetTemplate($sTemplateFilename, $sMasterpageFilename = null){
            $oThis = self::CreateInstanceIfNotExists();
            $sTemplate = (file_exists($sTemplateFilename)) ? file_get_contents($sTemplateFilename) : null;
            $sMasterpage = (file_exists($sMasterpageFilename)) ? file_get_contents($sMasterpageFilename) : null;
            
            if(!is_null($sMasterpage))
                $oThis->sBuffer = str_replace("{\$template}", $sTemplate, $sMasterpage);
            else
                $oThis->sBuffer = $sTemplate;
            
            $oThis->IncludeTemplate();
        }

        /**
         * Function to add CSS file to template
         * 
         * @access public
         * @param string $sFilenameCSS Path to the CSS file
         * @return void
         */
        public static function AppendCSS($sFilenameCSS){
            $oThis = self::CreateInstanceIfNotExists();
            
            if(file_exists($sFilenameCSS))      
                $oThis->aCSS[] = file_get_contents($sFilenameCSS);
        }
        
        /**
         * Function to add Javascript file to template
         * 
         * @access public
         * @param string $sFilenameJS Path to the Javascript file
         * @return void
         */
        public static function AppendJS($sFilenameJS){
            $oThis = self::CreateInstanceIfNotExists();
            
            if(file_exists($sFilenameJS))      
                $oThis->aJS[] = file_get_contents($sFilenameJS);
        }
        
        /**
         * CACHE FUNCTIONS
         */
        
        /**
         * Function to create the CSS file cache
         * 
         * @access public
         * @param boolean $bForce Forcing creation
         * @return void
         */
        private static function CreateCacheCSS($bForce){ 
            $oThis = self::CreateInstanceIfNotExists();
            
            if(count($oThis->aCSS) > 0){
                $sCacheFilename = Storage::Join("dir.cache", strtolower($oThis->sNamespace).".css");
                Storage::Set("cache.css", Storage::Join("route.root", "cache/".strtolower($oThis->sNamespace).".css"));
                
                if(!file_exists($sCacheFilename) || $bForce || Storage::Get("debug", false)){
                    $sBuffer = "";

                    foreach($oThis->aCSS as $sAppendBuffer)
                        $sBuffer .= $sAppendBuffer;
                    
                    $sBuffer = $oThis->MinifyCSS($sBuffer);
                    file_put_contents($sCacheFilename, $sBuffer);
                }
            } 
        }
        
        /**
         * Function to compress CSS file cache
         * 
         * @access public
         * @param string $sStr
         * @return string
         */
        private static function MinifyCSS($sStr){ 
            $sStr = preg_replace('!/\*.*?\*/!s','', $sStr); 
            $sStr = preg_replace('/\n\s*\n/',"\n", $sStr); 
            $sStr = preg_replace('/[\n\r \t]/',' ', $sStr); 
            $sStr = preg_replace('/ +/',' ', $sStr); 
            $sStr = preg_replace('/ ?([,:;{}]) ?/','$1',$sStr); 
            $sStr = preg_replace('/;}/','}',$sStr); 
            return $sStr; 
        } 
        
        /**
         * Function to create the Javascript file cache
         * 
         * @access public
         * @param boolean $bForce Forcing creation
         * @return void
         */
        private static function CreateCacheJS($bForce){ 
            $oThis = self::CreateInstanceIfNotExists();
            
            if(count($oThis->aJS) > 0){
                $sCacheFilename = Storage::Join("dir.cache", strtolower($oThis->sNamespace).".js");
                Storage::Set("cache.js", Storage::Join("route.root", "cache/".strtolower($oThis->sNamespace).".js"));
                
                if(!file_exists($sCacheFilename) || $bForce || Storage::Get("debug", false)){
                    $sBuffer = "";

                    foreach($oThis->aJS as $sAppendBuffer)
                        $sBuffer .= $sAppendBuffer;
                    
                    file_put_contents($sCacheFilename, $sBuffer);
                }
            } 
        }
        
        /**
         * FUNCTIONS OF TREATMENT OF OUTPUT
         */
        
        /**
         * Function to include subtemplates
         * 
         * @access public
         * @param string $sBuffer
         * @return void
         */
        private static function IncludeTemplate(){
            $oThis = self::CreateInstanceIfNotExists();
            
            $oThis->ReplaceVars();
            $oThis->sBuffer = trim($oThis->sBuffer);
            $Offset = 0;

            while(preg_match('/{include\s*(.*?)}/i', $oThis->sBuffer, $aMatches, PREG_OFFSET_CAPTURE, $Offset)){
                $sFilename = str_replace(array("'", '"'), "", $aMatches[1][0]);
                
                if(file_exists($sFilename)){
                    $sBufferAppend = file_get_contents($sFilename);
                    $oThis->sBuffer = str_replace($aMatches[0][0], $sBufferAppend, $oThis->sBuffer);
                }
                else{
                   $oThis->sBuffer = str_replace($aMatches[0][0], "", $oThis->sBuffer); 
                }            
                
                $Offset = $aMatches[0][1]+strlen($aMatches[0][0]);
            }
        }
              
        /**
         * Function to handle variables required in the template
         * 
         * @access public
         * @param string $sBuffer Buffer Template
         * @return void
         */
        private static function ReplaceVars(){	
            $oThis = self::CreateInstanceIfNotExists();
            
            $aP = array();
            $iOffset = 0;

            while(preg_match('/(\{\$(.*?)\})/i', $oThis->sBuffer, $aMatches, PREG_OFFSET_CAPTURE, $iOffset)){
                $mVar = $aMatches[2][0];

                if(Storage::Has($mVar)){
                    $mTo = Storage::Get($mVar);

                    if(is_bool($mTo))
                        $mTo = ($mTo) ? "true" : "false";

                    $mTo = html_entity_decode($mTo, ENT_NOQUOTES/*, Config::Read("l10n.charset")*/);
                    $oThis->sBuffer = str_replace($aMatches[0][0], $mTo, $oThis->sBuffer);
                }

                $iOffset = $aMatches[0][1]+strlen($aMatches[0][0]);
            }
        }
        
        /**
         * Function to remove variables that were not stored
         * 
         * @access public
         * @param string $sBuffer Buffer Template
         * @return void
         */
        private static function RemoveUndefinedVars(){
            $oThis = self::CreateInstanceIfNotExists();
            $iOffset = 0;

            while(preg_match('/{\$(.*?)}/i', $oThis->sBuffer, $aMatches, PREG_OFFSET_CAPTURE, $iOffset)){
                $oThis->sBuffer = str_replace($aMatches[0][0], "false", $oThis->sBuffer);
                $iOffset = $aMatches[0][1]+strlen($aMatches[0][0]);
            }
        }
        
        /**
         * Function for treatment of conditionals in the template
         * 
         * @access public
         * @param string $sBuffer Buffer Template
         * @return void
         */
        private static function CheckConditions(){
            $oThis = self::CreateInstanceIfNotExists();
            $oThis->sBuffer = str_replace("{else}", "<?php } else { ?>", $oThis->sBuffer);
            $oThis->sBuffer = str_replace("{endif}", "<?php } ?>", $oThis->sBuffer);
            $oThis->sBuffer = str_replace(array('{if }', '{if  }'), "<?php if(false){ ?>", $oThis->sBuffer);

            //IF 
            $Offset = 0;

            while(preg_match('/{if\s*(.*?)}/i', $oThis->sBuffer, $aMatches, PREG_OFFSET_CAPTURE, $Offset)){
                $oThis->sBuffer = str_replace($aMatches[0][0], "<?php if(".$aMatches[1][0]."){ ?>", $oThis->sBuffer);
                $Offset = $aMatches[0][1]+strlen($aMatches[0][0]);
            }	

            //Elseif
            $Offset = 0;

            while(preg_match('/{elseif\s*(.*?)}/i', $oThis->sBuffer, $aMatches, PREG_OFFSET_CAPTURE, $Offset)){
                $oThis->sBuffer = str_replace($aMatches[0][0], "<?php } elseif(".$aMatches[1][0]."){ ?>", $oThis->sBuffer);
                $Offset = $aMatches[0][1]+strlen($aMatches[0][0]);
            }
        }
        
        /**
         * Function to replace the template lists
         * 
         * @static
         * @access public
         * @param string $sListName
         * @param array $aData
         * @return void
         */
        public static function ReplaceList($sListName, $aData){
            $oThis = self::CreateInstanceIfNotExists();
            $oThis->sBuffer = str_replace("{end}", "<?php } ?>", $oThis->sBuffer);
            $Offset = 0;
            
            while(preg_match('/{list\:(.*?)}/i', $oThis->sBuffer, $aMatches, PREG_OFFSET_CAPTURE, $Offset)){
                if($aMatches[1][0] == $sListName){
                    $sList = "\$aList = json_decode('". json_encode($aData) ."', true);";
                    $oThis->sBuffer = str_replace($aMatches[0][0], "<?php ".$sList."\r\n foreach(\$aList as \$aItem){ ?>", $oThis->sBuffer);
                }
                
                $Offset = $aMatches[0][1]+strlen($aMatches[0][0]);
            }
            
            $Offset = 0;
            
            while(preg_match('/{list\.(.*?)}/i', $oThis->sBuffer, $aMatches, PREG_OFFSET_CAPTURE, $Offset)){
                $oThis->sBuffer = str_replace($aMatches[0][0], "<?php echo \$aItem[\"".$aMatches[1][0]."\"]; ?>", $oThis->sBuffer);
                $Offset = $aMatches[0][1]+strlen($aMatches[0][0]);
            }
        }
             
        /**
         * Function to return HTTP exceptions
         * 
         * @static
         * @access public
         * @param integer $iCode Exception Code HTTP
         * @return void
         */
        public static function SendHTTPCode($iCode){
            $oThis = self::CreateInstanceIfNotExists();
            
            if(array_key_exists($iCode, $oThis->aHTTPStatus)){
                header($oThis->aHTTPStatus[$iCode]);
                header("Connection: close");
                die();                
            }
        }
        
        /**
         * Function to output redirection
         * 
         * @static
         * @access public
         * @param string $sUrl Url redirection
         * @return void
         */
        public static function Redirect($sUrl){
            $oThis = self::CreateInstanceIfNotExists();
           
            header($oThis->aHTTPStatus[301]);
            header("Location: ".$sUrl);
            die();
        }
        
        /**
         * Function to precompile template
         * 
         * @static
         * @return void
         */
        public static function PreCompile(){
            $oThis = self::CreateInstanceIfNotExists();
            $oThis->IncludeTemplate();
            $oThis->ReplaceVars(); 
            $oThis->RemoveUndefinedVars(); 
            $oThis->CheckConditions(); 
        }
        
        /**
         * Function to send output
         * 
         * @access public
         * @return void
         */
        public static function Send(){
            $oThis = self::CreateInstanceIfNotExists();
            $oThis->CreateCacheCSS(Storage::Get("debug", false));
            $oThis->CreateCacheJS(Storage::Get("debug", false));
            $oThis->IncludeTemplate();
            $oThis->ReplaceVars(); 
            $oThis->RemoveUndefinedVars(); 
            $oThis->CheckConditions(); 
            //Events::BeforeSendingOutput();
            
            header('HTTP/1.1 200 OK');
            header("Content-Type: text/html; charset=" . strtoupper(Storage::Get("app.charset", "UTF-8")), true);
                
            /*if(Storage::Get("debug", false)){
                header("Expires: 0");
                header("Cache-Control: no-cache, must-revalidate");
                header("Pragma: no-cache"); 
                header("Cache: no-cache");  
            }
            else{
                header("Expires: " . date("D, d M Y H:i:s", time() + ( 365 * 24 * 60 * 60 )) . " GMT"); //Adicionando timeout de 1 ano o cache para o cache
                header("Cache-Control: private, no-cache, no-store, must-revalidate");
                header("Pragma: no-cache"); 
            }
            
            header("MagicPHP 0.1b");
            header("Connection: close");*/
                    
            try{
                //var_dump($oThis->sBuffer);die();
                
                eval('?> ' . $oThis->sBuffer);
                die();
            }
            catch(Exception $e){
                die($e->getMessage());
            }
        }
    }