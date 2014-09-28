<?php
    /**
     * Settings
     */
    
    //Default Settings
    Storage::Set("app.charset", "UTF-8");
    Storage::Set("debug", false);
    Storage::Set("app.minified", false);

    //Smarty Settings
    Storage::Set("smarty.dir.compile", __DIR__ . SP . "public" . SP . "compiles" . SP);
    Storage::Set("smarty.dir.config", __DIR__ . SP . "public" . SP . "configs" . SP);