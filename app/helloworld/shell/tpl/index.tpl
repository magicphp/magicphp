<!DOCTYPE html>
<html>
<head>
    <title>{$app.title}</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="{$assets.css}" type="text/css" />
    <link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css" type="text/css" />
</head>
<body>
    <div class="helloworld">
        <img alt="MagicPHP" src="{$virtual.helloworld.shell.img}logo.png" />
        <h1>MagicPHP</h1>
    </div> 
    {if $debug}
        {debug}
    {/if}
</body>
</html>