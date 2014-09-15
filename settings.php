<?php
    /**
     * Settings
     */

    error_reporting(0);//Disable Errors
    
    //Default Settings
    Storage::Set("app.charset", "UTF-8");
    Storage::Set("debug", true);
    Storage::Set("app.minified", true);

    //Smarty Settings
    Storage::Set("smarty.dir.compile", __DIR__ . SP . "public" . SP . "compiles" . SP);
    Storage::Set("smarty.dir.config", __DIR__ . SP . "public" . SP . "configs" . SP);