MagicPHP
========

The simplest framework for PHP, create project like magic, perform on-demand requests, load only the necessary, and write clean and fast code.

#Hello World
<pre><code>Routes::Set("", "GET", function($mID){        
    $oOutput = new Output();
    $oOutput->SetNamespace("frontend")
            ->SetTemplate(Storage::Join("dir.shell.default.tpl", "index.tpl"))
            ->AppendCSS(Storage::Join("dir.shell.default.css", "index.css"))
            ->Set("title","Hello World")
            ->Send();
});</code></pre>
