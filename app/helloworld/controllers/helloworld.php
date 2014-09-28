<?php
    /**
     * Controller Hello World
     * 
     * @package     MagicPHP Hello World 
     * @author      André Ferreira <andrehrf@gmail.com>
     * @link        https://github.com/magicphp/magicphp MagicPHP(tm)
     * @license     MIT License (http://www.opensource.org/licenses/mit-license.php)
     */

    namespace App\Helloworld\Controllers;
    
    use \Output as Output,
        \Storage as Storage;

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
            Output::UseSmarty();
            Output::SetTemplate(Storage::Join("app.helloworld.shell.tpl", "index.tpl"));
            Output::AppendCSS(Storage::Join("app.helloworld.shell.css", "index.css"));
            Output::Send();
        }
    }