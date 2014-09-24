<?php
    /**
     * Main Controller
     * 
     * @package     MagicPHP
     * @author      André Ferreira <andrehrf@gmail.com>
     * @link        https://github.com/magicphp/magicphp MagicPHP(tm)
     * @license     MIT License (http://www.opensource.org/licenses/mit-license.php)
     */

    if($_SERVER["REMOTE_ADDR"] == "127.0.0.1" || $_SERVER["REMOTE_ADDR"] == "::1"){
        ini_set("display_errors", "on");
        error_log(E_ERROR);
    }

    if(file_exists(__DIR__ . DIRECTORY_SEPARATOR . "vendor" . DIRECTORY_SEPARATOR . "autoload.php"))
        require_once(__DIR__ . DIRECTORY_SEPARATOR . "vendor" . DIRECTORY_SEPARATOR . "autoload.php");

    require_once(__DIR__ . DIRECTORY_SEPARATOR . "bootstrap.php");
    Bootstrap::Start();

    if(file_exists(__DIR__ . DIRECTORY_SEPARATOR . "routes.php"))
        require_once(__DIR__ . DIRECTORY_SEPARATOR . "routes.php"); 

    Bootstrap::AutoLoad("settings");
    Routes::Parse();   
