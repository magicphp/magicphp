MagicPHP
========

The simplest framework for PHP, create project like magic, perform on-demand requests, load only the necessary, and write clean and fast code.

#Hello World
<pre><code>Routes::SetDynamicRoute(function(){   
    Storage::Set("title","Hello World");
    Output::SetNamespace("helloworld");
    Output::SetTemplate(Storage::Join("dir.shell.default.tpl", "index.tpl"));
    Output::AppendCSS(Storage::Join("dir.shell.default.css", "index.css"));
    Output::Send();
});</code></pre>

See the documentation at:

http://magicphp.com.br
