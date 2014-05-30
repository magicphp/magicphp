<?php
    /**
     * Controller Hello World
     * 
     * @package     MagicPHP Hello World 
     * @author      André Ferreira <andrehrf@gmail.com>
     * @link        https://github.com/magicphp/magicphp MagicPHP(tm)
     * @license     MIT License (http://www.opensource.org/licenses/mit-license.php)
     */

    class HelloWorld{
        /**
         * Function to display the Hello World screen
         * 
         * @static
         * @access public
         * @return void
         */
        public static function Index(){
            Output::SetNamespace("helloworld");
            Output::SetTemplate(Storage::Join("module.helloworld.shell.tpl", "index.tpl"));
            Output::AppendCSS(Storage::Join("module.helloworld.shell.css", "index.css"));
            Output::AppendCSS(Storage::Join("module.helloworld.shell.css", "debug.css"));
            Output::Send();
        }
    }