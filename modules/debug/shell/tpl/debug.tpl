<!DOCTYPE html>
<html>
<head>
    <title>MagicPHP Debug</title>
    <meta charset="UTF-8" />
    <meta name="publisher" content="MagicPHP" />
    
    <!-- CSS -->
    <link rel="stylesheet" href="//code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" type="text/css" />
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap.min.css" type="text/css" />
    <link rel="stylesheet" href="{$cache.css}" type="text/css" />
        
    <!-- JS -->
    <script src="//code.jquery.com/jquery-1.10.2.min.js" type="text/javascript"></script>
    <script src="//code.jquery.com/ui/1.10.3/jquery-ui.js" type="text/javascript"></script>
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.3/js/bootstrap.min.js" type="text/javascript"></script>
</head>
<body>
    <div class="debug">
        <ul class="nav nav-tabs" style="position: fixed; top: 0px; left: 0px; right: 0px; background-color: #FFF; padding-top: 10px;">
            <li class="active"><a href="#global-storage" data-toggle="tab">Global Storage ({$debug.total.globalstorage})</a></li>
            <li><a href="#request" data-toggle="tab">Request</a></li>
            <li><a href="#queries" data-toggle="tab">Queries</a></li>
        </ul>
        
        <div class="tab-content" style="margin-top: 50px">
            <div class="tab-pane active" id="global-storage">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" class="table table-striped table-bordered" style="border-top: none !important">
                    <thead>
                        <tr>
                            <th>Name</th>	
                            <th>Value</th>	
                        </tr>
                    </thead>
                    <tbody>
                        {list:globalstorage}
                        <tr>
                            <td>{list.globalstorage.key}</td>
                            <td>{list.globalstorage.value}</td>
                        </tr>
                        {end}
                    </tbody>
                </table>
            </div>
            <div class="tab-pane" id="request">
                <table border="0" cellpadding="0" cellspacing="0" width="100%"  class="table table-striped table-bordered" style="border-top: none !important; margin-bottom: 0px">
                    <thead>
                        <tr>
                            <th colspan="2">HTTP Request Headers</th>	
                        </tr>
                    </thead>
                    <tbody>
                        {list:http_request}
                        <tr>
                            <td style="width: 150px">{list.http_request.key}</td>
                            <td>{list.http_request.value}</td>
                        </tr>
                        {end}
                    </tbody>
                </table>
                <table border="0" cellpadding="0" cellspacing="0" width="100%" class="table table-striped table-bordered" style="border-top: none !important">
                    <thead>
                        <tr>
                            <th colspan="2">HTTP Response Headers</th>	
                        </tr>
                    </thead>
                    <tbody>
                        {list:http_response}
                        <tr>
                            <td style="width: 150px">{list.http_response.key}</td>
                            <td>{list.http_response.value}</td>
                        </tr>
                        {end}
                    </tbody>
                </table>
            </div>
            <div class="tab-pane" id="queries">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" class="table table-striped table-bordered" style="border-top: none !important">
                    <thead>
                        <tr>
                            <th>Query</th>	
                            <th>Runtime</th>	
                            <th>Affected</th>
                        </tr>
                    </thead>
                    <tbody>
                        {list:queries_report}
                        <tr>
                            <td>{list.queries_report.query}</td>
                            <td>{list.queries_report.timer}</td>
                            <td>{list.queries_report.affected}</td>
                        </tr>
                        {end}
                    </tbody>
                </table>

            </div>
        </div>        
    </div>
</body>
</html>