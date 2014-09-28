<!DOCTYPE html>
<html>
<head>
    <title>{$app.title}</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="{$assets.css}" type="text/css" />
</head>
<body>
    <!-- Hello World like Laravel -->
    <div id="header" role="header" class="maHeaderIndex">
        <img src="{$route.root}public/assets/img/icon.png" class="maBand" />
        <h1>It's time for magic!</h1>
    </div>
    {if $debug}
        {debug}
    {/if}
</body>
</html>