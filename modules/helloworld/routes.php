<?php
    /**
     * Routes of Hello World
     * 
     * @package     MagicPHP Hello World
     * @author      André Ferreira <andrehrf@gmail.com>
     * @link        https://github.com/magicphp/magicphp MagicPHP(tm)
     * @license     MIT License (http://www.opensource.org/licenses/mit-license.php)
     */

    Routes::SetOverloadFrontend(true);
    Routes::Set("", "GET", "HelloWorld::Index");
    Routes::SetDynamicRoute(function() {
        Output::SendHTTPCode(404);
    });