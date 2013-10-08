<?php
    /**
     * Settings
     * 
     */
    error_reporting(E_ALL);
    ini_set("display_errors", "on");

    Storage::Set("app.charset", "UTF-8");
    Storage::Set("debug", true);
