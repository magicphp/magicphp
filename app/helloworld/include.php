<?php
    /**
     * Initializer module
     * 
     * @package     MagicPHP Hello World 
     * @author      André Ferreira <andrehrf@gmail.com>
     * @license     MIT License (http://www.opensource.org/licenses/mit-license.php) 
     */

    $oModule = App::Append("helloworld", __DIR__ . SP);
    $oModule->Set("name", "MagicPHP Hello World")
            ->Set("author", "André Ferreira <andrehrf@gmail.com>")
            ->Set("website", "https://magicphp.org")
            ->Set("license", "MIT")
            ->Start();