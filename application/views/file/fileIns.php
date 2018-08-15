<?php
ini_set('display_errors', 1);	//1 - показывать ошибки, 0 - скрывать
error_reporting(E_ALL);
?>
<!DOCTYPE html>
<html>
    <head>
        <title>php файлы</title>
        <meta charset="utf-8" />
        <link href="/css/style.css" type="text/css" rel="stylesheet"/>
    </head>
    <body>
        <form method="POST" action="/File/fileIns" enctype="multipart/form-data">
            <p><input type="file" name="file"/></p>
            <button>Send</button><br/>
        </form>
    </body>
</html>