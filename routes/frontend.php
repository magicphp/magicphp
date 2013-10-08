<?php
    /**
     * Hello World
     * 
     * @package     MagicPHP
     * @author      André Henrique da Rocha Ferreira <andrehrf@gmail.com>
     * @copyright   Copyright (c) 2013, T&M Network, Inc.
     * @link        https://github.com/andrehrf/magicphp MagicPHP(tm)
     * @license     MIT License (http://www.opensource.org/licenses/mit-license.php)
     */

    Routes::SetDynamicRoute(function($sUri, $sMethod){   
        Storage::Set("title","Hello World");
        
        Output::SetNamespace("frontend");
        Output::SetTemplate(Storage::Join("dir.shell.default.tpl", "index.tpl"));
        Output::AppendCSS(Storage::Join("dir.shell.default.css", "index.css"));
        Output::Send();
    });