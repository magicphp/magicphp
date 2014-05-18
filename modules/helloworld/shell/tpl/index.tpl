<!DOCTYPE html>
<html>
    <head>
        <title>{$app.title}</title>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        {if "{$cache.css}" != "false"}<link rel="stylesheet" href="{$cache.css}" type="text/css" />{endif}
    </head>
    <body>
        <div class="helloworld">
            <img alt="MagicPHP" src="{$route.root}/modules/helloworld/shell/img/logo.png" />
            <h1>MagicPHP</h1>
        </div>        
    </body>
</html>