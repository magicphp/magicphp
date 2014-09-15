<?php /* Smarty version Smarty-3.1.19-dev, created on 2014-09-15 17:20:30
         compiled from "D:\Dropbox\MagicPHP\magicphp\app\helloworld\shell\tpl\index.tpl" */ ?>
<?php /*%%SmartyHeaderCode:6878541703be22eeb4-53378917%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '9135012a8fc6cf81fc34e31770dcbe5c928a61aa' => 
    array (
      0 => 'D:\\Dropbox\\MagicPHP\\magicphp\\app\\helloworld\\shell\\tpl\\index.tpl',
      1 => 1410794429,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '6878541703be22eeb4-53378917',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'app' => 0,
    'assets' => 0,
    'virtual' => 0,
    'debug' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19-dev',
  'unifunc' => 'content_541703be279243_07809183',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_541703be279243_07809183')) {function content_541703be279243_07809183($_smarty_tpl) {?><!DOCTYPE html>
<html>
<head>
    <title><?php echo $_smarty_tpl->tpl_vars['app']->value['title'];?>
</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['assets']->value['css'];?>
" type="text/css" />
    <link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css" type="text/css" />
</head>
<body>
    <div class="helloworld">
        <img alt="MagicPHP" src="<?php echo $_smarty_tpl->tpl_vars['virtual']->value['helloworld']['shell']['img'];?>
logo.png" />
        <h1>MagicPHP</h1>
    </div> 
    <?php if ($_smarty_tpl->tpl_vars['debug']->value) {?>
        <?php $_smarty_tpl->smarty->loadPlugin('Smarty_Internal_Debug'); Smarty_Internal_Debug::display_debug($_smarty_tpl); ?>
    <?php }?>
</body>
</html><?php }} ?>
