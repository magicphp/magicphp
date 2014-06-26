<?php
    /**
     * Initializer module
     * 
     * @package     MagicPHP Debug
     * @author      André Ferreira <andrehrf@gmail.com>
     * @link        https://github.com/magicphp/magicphp MagicPHP(tm)
     * @license     MIT License (http://www.opensource.org/licenses/mit-license.php)
     */

    $oModule = Modules::Append("debug", __DIR__ . SP);
    $oModule->Set("name", "MagicPHP Debug")
            ->Set("author", "André Ferreira <andrehrf@gmail.com>")
            ->Start();
   
    Routes::Set("debug", "GET", "Debug::Display");
    Events::Set("BeforeSendingOutput", "Debug::Make");