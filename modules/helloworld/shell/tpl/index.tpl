<!DOCTYPE html>
<html>
<head>
    <title>{$app.title}</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css" type="text/css" />
    {if "{$cache.css}" != "false"}<link rel="stylesheet" href="{$cache.css}" type="text/css" />{endif}
</head>
<body>
    <div class="helloworld">
        <img alt="MagicPHP" src="{$route.root}/modules/helloworld/shell/img/logo.png" />
        <h1>MagicPHP</h1>
    </div>      

    {if {$debug}}
    <div id="consoleDebug">
        <iframe frameborder="0" scrolling="yes" id="ifmDebug"></iframe>
        <button id="consoleDebugClose"><span class="glyphicon glyphicon-remove"></span></button>
    </div>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js" type="text/javascript"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js" type="text/javascript"></script>
    <script type="text/javascript">
        $(document).ready(function(){ 
            $("#ifmDebug").attr("src", "{$route.root}debug");
            $("#consoleDebug").resizable({handles: "n", minHeight: 100, grid: 10});
            $("#consoleDebugClose").click(function(){ $("#baDebug").slideToggle(); });
            $(document).keydown(function(e){ 
                if(e.keyCode == 120)
                    $("#consoleDebug").slideToggle(); 
            });
        });
    </script>
    {endif}
</body>
</html>