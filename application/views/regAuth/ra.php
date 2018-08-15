<!DOCTYPE html>
<html>
    <head>
        <title class="super">php файлы</title>
        <meta charset="utf-8" />
    </head>
    <body>
        <form method="POST" action="/RegAuth/ra/<?=$type?>">
            Логин: <input type="text" name="login" value="<?=$login?>"/><br/>
            Пароль: <input type="text" name="pass"/><br/>
            <button>Send</button><br/>
        </form>
    </body>
</html>